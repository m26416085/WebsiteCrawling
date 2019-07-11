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
                $_SESSION['cart_item'][] = array('id' => $_GET['id'], 'name' => $_GET['name'], 'price_format' => $_GET['price_format'], 'image' => $_GET['image'], 'shop_name' => $_GET['shop_name']);
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
                echo $_SESSION['cart_item'][$count]['price_format'] . "<br>";
                echo $_SESSION['cart_item'][$count]['shop_name'] . "<br>";
                echo "<a href='index.php?id=" . $_SESSION['cart_item'][$count]['id'] . "&delete=1&search=" . $_GET['search'] . "'>Delete</a>" . "<br><br>";
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
                echo $_SESSION['cart_item'][$count]['price_format'] . "<br>";
                echo $_SESSION['cart_item'][$count]['shop_name'] . "<br>";
                echo "<a href='index.php?id=" . $_SESSION['cart_item'][$count]['id'] . "&delete=1&search=" . $_GET['search'] . "'>Delete</a>" . "<br><br>";
                $count++;
            }
        }
    }
    ?>
    <form action="index.php" method="POST">
        <input type="text" name="text_value"><input type="submit" value="search" name="find">
        <select name="show_amount" id="show_amount">
            <option value="5">5</option>
            <option value="10">10</option>
        </select>
        <input type="text" name="location">
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

$show_amount = $_POST['show_amount'];
$location = $_POST['location'];

$search = str_replace(' ', '%20', $search);


$url = "https://ta.tokopedia.com/promo/v1/display/ads?user_id=0&ep=product&item=1000&src=search&device=desktop&page=2&q=" . $search . "&fshop=1";

$data = http_request($url);

// ubah string JSON menjadi array
$data = json_decode($data, TRUE);

#echo $profile["data"][0]["product"]["name"];
$i = 0;
$show_count = 0;
foreach ($data["data"] as $profil) {
    if ($data['data'][$i]['shop']['location'] == $location) {
        echo '<img src=' . $data["data"][$i]["product"]["image"]["m_url"] . '><br>';
        echo $data["data"][$i]["product"]["name"] . '<br>';
        echo $data["data"][$i]["product"]["price_format"] . '<br>';
        echo $data["data"][$i]["shop"]["name"] . '<br>';
        echo $data["data"][$i]["shop"]["location"] . '<br>';
        echo '<a href=' . $data["data"][$i]["product"]["uri"] . '>Link</a>' . '<br>';
        echo '<a href="index.php?id=' . $data["data"][$i]["product"]["id"] . '&name=' . $data["data"][$i]["product"]["name"] . '&price_format=' . $data["data"][$i]["product"]["price_format"] . '&image=' . $data["data"][$i]["product"]["image"]["m_url"] . '&shop_name=' . $data["data"][$i]["shop"]["name"] . '&check=1' . '&search=' . $search . '" id="addtocart">Add to Cart</a><br><br>';
        $show_count++;
    }
    if ($location == null){
        echo '<img src=' . $data["data"][$i]["product"]["image"]["m_url"] . '><br>';
        echo $data["data"][$i]["product"]["name"] . '<br>';
        echo $data["data"][$i]["product"]["price_format"] . '<br>';
        echo $data["data"][$i]["shop"]["name"] . '<br>';
        echo $data["data"][$i]["shop"]["location"] . '<br>';
        echo '<a href=' . $data["data"][$i]["product"]["uri"] . '>Link</a>' . '<br>';
        echo '<a href="index.php?id=' . $data["data"][$i]["product"]["id"] . '&name=' . $data["data"][$i]["product"]["name"] . '&price_format=' . $data["data"][$i]["product"]["price_format"] . '&image=' . $data["data"][$i]["product"]["image"]["m_url"] . '&shop_name=' . $data["data"][$i]["shop"]["name"] . '&check=1' . '&search=' . $search . '" id="addtocart">Add to Cart</a><br><br>';
        $show_count++;
    }
    if ($show_count == $show_amount){
        
    }
    $i = $i + 1;
}
?>