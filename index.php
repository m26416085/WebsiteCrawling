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
            echo "masuk create";
        }
        else{
            // array_push($_SESSION['cart_item'], $_GET['id_product']);
            array_push($_SESSION['cart_item'], $i);
            print_r($_SESSION['cart_item']);
            echo "bukan create";
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
        echo '<a href="index.php?id_product=' . $data["data"][$i]["id"] . '&search='.$search.'" id="addtocart">Add to Cart</a><br><br>';
        $i = $i + 1;
    }
?>