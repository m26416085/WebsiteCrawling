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
            $_SESSION['cart_item'] = array();
        }
        else{
            error_reporting(0);
            if ($_GET['check'] == "1"){
                // array_push($_SESSION['cart_item'], $_GET['id_product']);
                $count = 0;
                $same = 0;
                foreach($_SESSION['cart_item'] as $item){
                    if ($_SESSION['cart_item'][$count]['id'] == $_GET['id']){
                        $same++;
                    }
                    $count++;
                }

                if ($same <= 0){
                    $_SESSION['cart_item'][] = Array('id' => $_GET['id'], 'name' => $_GET['name'], 'price_format' => $_GET['price_format'], 'image' => $_GET['image'], 'shop_name' => $_GET['shop_name']);
                }
                else{
                    echo '<script> alert("Sudah ada dalam list") </script>';
                }
                
                //$_SESSION['cart_item'][] += array($_GET['name'] => $_GET['price_format']);
                
                // print "<pre>";
                // print_r($_SESSION['cart_item']);
                // print "</pre>";
                
                $count = 0;
                foreach($_SESSION['cart_item'] as $item){
                    echo "<img style='width: 100px;' src=".$_SESSION['cart_item'][$count]['image']."><br>";
                    echo $_SESSION['cart_item'][$count]['name']."<br>";
                    echo $_SESSION['cart_item'][$count]['price_format']."<br>";
                    echo $_SESSION['cart_item'][$count]['shop_name']."<br><br>";
                    
                    $count++;
                }
            }
        }
    ?>
    <form action="index.php" method="POST">
        <input type="text" name="text_value"><input type="submit" value="search" name="find">
    </form>
</body>
</html>

<?php
    error_reporting(0);
    if ($_GET['search'] == null){
        $search = $_POST['text_value'];
    }
    else{
        echo $search;
        $search = $_GET['search'];
    }

    $search = str_replace(' ', '%20', $search);

    $url = "https://ta.tokopedia.com/promo/v1/display/ads?user_id=0&ep=product&item=20&src=search&device=desktop&page=2&q=" . $search . "&fshop=1";

    $data = http_request($url);

    // ubah string JSON menjadi array
    $data = json_decode($data, TRUE);

    //add from json array to php array
    $x = 0;
    // foreach($data["data"] as $php_array){
    //     $php_array[]
    // }

    #echo $profile["data"][0]["product"]["name"];
    $i = 0;
    foreach ($data["data"] as $profil) {
        echo '<img src=' . $data["data"][$i]["product"]["image"]["m_url"] . '><br>';
        echo $data["data"][$i]["product"]["name"] . '<br>';
        echo $data["data"][$i]["product"]["price_format"] . '<br>';
        echo $data["data"][$i]["shop"]["name"] . '<br>';
        echo $data["data"][$i]["shop"]["location"] . '<br>';
        echo '<a href=' . $data["data"][$i]["product"]["uri"] . '>Link</a>' . '<br>';
        echo '<a href="index.php?id='.$data["data"][$i]["product"]["id"].'&name=' . $data["data"][$i]["product"]["name"] .'&price_format='.$data["data"][$i]["product"]["price_format"].'&image='.$data["data"][$i]["product"]["image"]["m_url"].'&shop_name='.$data["data"][$i]["shop"]["name"].'&check=1'.'&search='.$search.'" id="addtocart">Add to Cart</a><br><br>';
        $i = $i + 1;
    }
?>