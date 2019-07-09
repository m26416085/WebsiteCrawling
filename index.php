<?php

function http_request($url){
    // persiapkan curl
    $ch = curl_init(); 

    // set url 
    curl_setopt($ch, CURLOPT_URL, $url);
    
    // set user agent    
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);   

    // mengembalikan hasil curl
    return $output;
}

if (isset($_POST['find']))
	{
        $search = $_POST['text_value'];

        $search = str_replace(' ', '%20', $search);

        $url = "https://ta.tokopedia.com/promo/v1/display/ads?user_id=0&ep=product&item=20&src=search&device=desktop&page=2&q=".$search."&fshop=1";

        $profile = http_request($url);

        // ubah string JSON menjadi array
        $profile = json_decode($profile, TRUE);

        #echo $profile["data"][0]["product"]["name"];
        $i = 0;
        foreach($profile["data"] as $profil){
            echo '<img src='.$profile["data"][$i]["product"]["image"]["m_url"].'><br>';
            echo $profile["data"][$i]["product"]["name"].'<br>';
            echo $profile["data"][$i]["product"]["price_format"].'<br>';
            echo $profile["data"][$i]["shop"]["name"].'<br>';
            echo $profile["data"][$i]["shop"]["location"].'<br>';
            echo '<a href='.$profile["data"][$i]["product"]["uri"].'>Link</a>'.'<br>';
            $i = $i + 1;
        }
    }



?>

<!DOCTYPE html>
<html>
<head>
    <title>Curl Data JSON</title>
</head>
<body>
<form action = "index.php" method = "POST">
    <input type = "text" name="text_value"><input type = "submit" value = "search" name = "find">
</form>
<!-- <img src="<?php echo $profile['avatar_url']; ?>" width="64" /> -->
<br>
<p>
<!-- Nama: <?php echo $profile["data"][0]["product"]["name"] ?><br>
URL: <a href="<?php echo $profile["uri"] ?>"><?php echo $profile["uri"] ?></a><br>
Lokasi: <?php echo $profile["location"] ?> -->
</p>

</body>
</html>