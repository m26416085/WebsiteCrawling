<?php

function http_request($url){

    $ch = curl_init(); 

    // set url 
    curl_setopt($ch, CURLOPT_URL, $url);
    
    // set user agent    
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    //close curl 
    curl_close($ch); 

    // return output
    return $output;
}

if (isset($_POST['find']))
	{
        $search = $_POST['text_value'];

        $search = str_replace(' ', '%20', $search);

        //url search
        $url = "https://shopee.co.id/api/v2/search_items/?by=relevancy&keyword=".$search."&limit=10&newest=0&order=desc&page_type=search";

        //dapat id semua item
        $profile = http_request($url);

        $profile = json_decode($profile, TRUE);

        //semua id di loop pakai link json punya detail
        $x = 0;
        foreach ($profile["items"] as $profil){
            

            $name = $profile["items"][$x]["name"];

            //start get product price
            $shopid = $profile["items"][$x]["shopid"];
            $itemid = $profile["items"][$x]["itemid"];
            
            $url_detail = "https://shopee.co.id/api/v2/item/get?itemid=".$itemid."&shopid=".$shopid;

            $data_detail = http_request($url_detail);
            $data_detail = json_decode($data_detail, TRUE);

            
            $price = substr($data_detail["item"]["price"], 0, -5);
            //end get product price

            $img_url = 'https://cf.shopee.co.id/file/'.$data_detail["item"]["image"];

            //get shop name
            $shop_detail = 'https://shopee.co.id/api/v2/shop/get?is_brief=1&shopid='.$data_detail["item"]["shopid"];
            $shop_detail = http_request($shop_detail);
            $shop_detail = json_decode($shop_detail, TRUE);
            $shop_name = $shop_detail["data"]['name'];

            $link_detail = 'https://shopee.co.id/'.str_replace(' ', '-', $profile["items"][$x]["name"]).'-i.'.$data_detail["item"]["shopid"].'.'.$data_detail["item"]["itemid"];

            echo '<img src='.$img_url.'><br>';
            echo $profile["items"][$x]["name"].'<br>';
            echo 'Rp '.$price.'<br>';
            echo $shop_name.'<br>';
            echo $data_detail["item"]["shop_location"].'<br>';
            echo '<a href='.$link_detail.'>Link</a><br>';
            
            



            $x = $x + 1;
        }
    }



?>

<!DOCTYPE html>
<html>
<head>
    <title>Curl Data JSON</title>
</head>

<body>
<form action = "index2.php" method = "POST">
    <input type = "text" name="text_value"><input type = "submit" value = "search" name = "find">
</form>

</body>
</html>