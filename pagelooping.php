<?php

function http_request($url)
{
    // persiapkan curl
    $ch = curl_init();

    // set url 
    curl_setopt($ch, CURLOPT_URL, $url);

    // set user agent    
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // $output contains the output string 
    $output = curl_exec($ch);

    // tutup curl 
    curl_close($ch);

    // mengembalikan hasil curl
    return $output;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Curl Data JSON</title>
</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION['cart_item'])) {
        // create cart
        $_SESSION['cart_item'] = array();
    } else {
        error_reporting(0);
        if ($_GET['check'] == "1") {
            // insert to cart

            // check if cart have same product
            $count = 0;
            $same = 0;
            foreach ($_SESSION['cart_item'] as $item) {
                if ($_SESSION['cart_item'][$count]['id'] == $_GET['id']) {
                    $same++;
                }
                $count++;
            }

            if ($same <= 0) {
                $_SESSION['cart_item'][] = array('id' => $_GET['id'], 'name' => $_GET['name'], 'price' => $_GET['price'], 'image' => $_GET['image'], 'shop_name' => $_GET['shop_name']);
            } else {
                echo '<script> alert("Sudah ada dalam list") </script>';
            }

            // print "<pre>";
            // print_r($_SESSION['cart_item']);
            // print "</pre>";

            // show item in cart
            $count = 0;
            foreach ($_SESSION['cart_item'] as $item) {
                echo "<img style='width: 100px;' src=" . $_SESSION['cart_item'][$count]['image'] . "><br>";
                echo $_SESSION['cart_item'][$count]['name'] . "<br>";
                echo $_SESSION['cart_item'][$count]['price'] . "<br>";
                echo $_SESSION['cart_item'][$count]['shop_name'] . "<br>";
                echo "<a href='pagelooping.php?id=" . $_SESSION['cart_item'][$count]['id'] . "&delete=1&search=" . $_GET['search'] . "&show_amount=".$_GET['show_amount']."'>Delete</a>" . "<br><br>";
                $count++;
            }
        }
        if ($_GET['delete'] == "1") {
            //delete cart item
            $count = 0;
            foreach ($_SESSION['cart_item'] as $item) {
                if ($_SESSION['cart_item'][$count]['id'] == $_GET['id']) {
                    array_splice($_SESSION['cart_item'], $count, 1);
                }
                $count++;
            }
            // show item in cart
            $count = 0;
            foreach ($_SESSION['cart_item'] as $item) {
                echo "<img style='width: 100px;' src=" . $_SESSION['cart_item'][$count]['image'] . "><br>";
                echo $_SESSION['cart_item'][$count]['name'] . "<br>";
                echo $_SESSION['cart_item'][$count]['price'] . "<br>";
                echo $_SESSION['cart_item'][$count]['shop_name'] . "<br>";
                echo "<a href='pagelooping.php?id=" . $_SESSION['cart_item'][$count]['id'] . "&delete=1&search=" . $_GET['search'] . "&show_amount=".$_GET['show_amount']."'>Delete</a>" . "<br><br>";
                $count++;
            }
        }
    }
    ?>
    <form action="pagelooping.php" method="POST">
        <input type="text" name="text_value"><input type="submit" value="search" name="find">
        <select name="show_amount" id="show_amount">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
            <option value="25">25</option>
        </select>
        <?php
        $city_url = "https://ace.tokopedia.com/v4/dynamic_attributes?scheme=https&device=desktop&related=true&page=9&ob=23&st=product&fcity=0&source=search&q=" . $search . "&unique_id=c8c1be17273f45f9b5d1c5346b116570&safe_search=false";
        $data_city = http_request($city_url);
        $data_city = json_decode($data_city, TRUE);

        $city_counter = 0;
        echo "<select name='location'>";
        echo "<option value='none'>None</option>";
        foreach ($data_city["data"]["filter"][0]["options"] as $dat_city) {
            echo "<option value=" . $data_city['data']['filter'][0]['options'][$city_counter]["value"] . ">" . $data_city["data"]["filter"][0]["options"][$city_counter]["name"] . "</option>";
            $city_counter++;
        }
        echo "</select><br>";
        ?>
    </form>
</body>

</html>

<?php
error_reporting(0);
if ($_GET['search'] == null) {
    $search = $_POST['text_value'];
} else {
    echo $search;
    $search = $_GET['search'];
}

if ($_GET['show_amount'] == null){
    $show_amount = $_POST['show_amount'];
    echo "Ini yang null".$show_amount;
}
else{
    $show_amount = $_GET['show_amount'];
    echo "Ini yang get".$show_amount;
}

$location = $_POST['location'];

$search = str_replace(' ', '%20', $search);

//$url = "https://ta.tokopedia.com/promo/v1/display/ads?user_id=0&ep=product&item=1000&src=search&device=desktop&page=".$f."&q=" . $search . "&fshop=1";
//$url = "https://ta.tokopedia.com/promo/v1.1/display/ads?page=".$f."&ob=23&q=".$search."&ep=product&item=20&src=search&device=desktop&user_id=3746750&minimum_item=10&no_autofill_range=5-14";
$start = 0;
$show_count = 0;
for ($go = 0; $go < 200; $go++) {

    if($location == "none"){
        $url = "https://ace.tokopedia.com/search/product/v3?scheme=https&device=desktop&related=true&catalog_rows=5&source=search&ob=23&st=product&rows=200&start=" . $start . "&q=" . $search . "&unique_id=a5e21c08aa434ccda179065dc7e41c73&safe_search=false";
    }
    else{
        $url = "https://ace.tokopedia.com/search/product/v3?scheme=https&device=desktop&related=true&catalog_rows=5&source=search&ob=23&st=product&fcity=".$location."&rows=200&start=".$start."&q=".$search."&unique_id=a5e21c08aa434ccda179065dc7e41c73&safe_search=false";
    }

    $data = http_request($url);

    // ubah string JSON menjadi array
    $data = json_decode($data, TRUE);

    // print "<pre>";
    // print_r($data);
    // print "</pre>";

    #echo $profile["data"][0]["product"]["name"];

    if ($search != "") {

        $i = 0;

        foreach ($data["data"]["products"] as $profil) {

            if ($data['data']["products"][$i]['shop']['location']) {
                echo '<img src=' . $data["data"]["products"][$i]["image_url"] . '><br>';
                echo $data["data"]["products"][$i]["name"] . '<br>';
                echo $data["data"]["products"][$i]["price"] . '<br>';
                echo $data["data"]["products"][$i]["shop"]["name"] . '<br>';
                echo $data["data"]["products"][$i]["shop"]["location"] . '<br>';
                echo '<a href=' . $data["data"]["products"][$i]["url"] . '>Link</a>' . '<br>';
                echo '<a href="pagelooping.php?id=' . $data["data"]["products"][$i]["id"] . '&name=' . $data["data"]["products"][$i]["name"] . '&price=' . $data["data"]["products"][$i]["price"] . '&image=' . $data["data"]["products"][$i]["image_url"] . '&shop_name=' . $data["data"]["products"][$i]["shop"]["name"] . '&check=1' . '&search=' . $search . '&show_amount='.$show_amount.'" id="addtocart">Add to Cart</a><br><br>';
                $show_count++;
            }
            if ($show_count == $show_amount) {
                echo "dekat break ".$show_amount;
                $show_count = 0;
                break;
            }
            $i = $i + 1;
        }
        if ($i == 200) {
            //echo "start sebelum tambah 200: ". $start;
            $start = $start + 200;
            //echo "start setelah tambah 200: ".$start;
            if ($start == 20000) {
                //echo "masuk start 0";
                $start = 0;
            }
        }
    }
    $go++;
    if ($show_count == 0) {
        break;
    }
}
?>