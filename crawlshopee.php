<?php
ini_set('max_execution_time', 5000); 
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Curl Data JSON</title>
</head>

<body>
<form action = "crawlshopee.php" method = "POST">
    <input type="text" name="text_value"><input type="submit" value="search" name="find">
    <select name="show_amount" id="show_amount">
        <option value="5">5</option>
        <option value="10">10</option>
        <option value="15">15</option>
        <option value="20">20</option>
        <option value="25">25</option>
    </select>
    <?php
        $city_url = "https://shopee.co.id/api/v2/location_filter/get_all";
        $data_city = http_request($city_url);
        $data_city = json_decode($data_city, TRUE);

        $group_counter = 0;
        echo "<select name='location'>";
        echo "<option value='none'>None</option>";
        foreach ($data_city["data"] as $group) {
            $city_counter=0;
            //masih belum bisa ambil value
            foreach ($data_city["data"][$group_counter]["locations"] as $city) {
                echo '<option value="'.  $data_city["data"][$group_counter]["locations"][$city_counter] . '">' . $data_city["data"][$group_counter]["locations"][$city_counter] . '</option>';
                $city_counter++;
            }
            $group_counter++;
        }
        echo "</select><br>";
    ?>
</form>

</body>
</html>

<?php
   
    if (isset($_POST['find']))
    {
        //get limiter
        $limit= $_POST['show_amount'];
        $location = $_POST['location'];
        $itemshowed = 0;
        if($location=='none')
        {
            $search = $_POST['text_value'];

            $search = str_replace(' ', '%20', $search);

            //url search
            //newest diubah berubah listnya
            //$url = "https://shopee.co.id/api/v2/search_items/?by=relevancy&keyword=mouse&limit=50&locations=Jawa%2520Barat&newest=0&order=desc&page_type=search";
            $newestcount = 0;
            
            //looping newest untuk dapat banyak data
            //for ($go = 0; $go < 100; $go++){
                
                //if($go>=1)
                //{
                //   $newestcount=$newestcount+50;
                //}

                //limit hanya bisa 50 kalau mau banyak pakai fungsi newest diatas.
                //url dengan filter harga
                $url = "https://shopee.co.id/api/v2/search_items/?by=relevancy&keyword=".$search."&limit=50&newest=".$newestcount."&order=desc&page_type=search&price_max=0&price_min=0";

                
                //dapat id semua item
                $profile = http_request($url);

                $profile = json_decode($profile, TRUE);

                //semua id di loop pakai link json punya detail
                $x = 0;
                foreach ($profile["items"] as $profil){
                    if($itemshowed>=$limit){
                        break;
                    }

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

                    echo '<img style="width:350px"src='.$img_url.'><br>';
                    echo $profile["items"][$x]["name"].'<br>';
                    echo 'Rp '.$price.'<br>';
                    echo $shop_name.'<br>';
                    echo $data_detail["item"]["shop_location"].'<br>';
                    echo '<a href='.$link_detail.'>Link</a><br>';
                    $itemshowed++;
                    $x = $x + 1;
                }
            //tutup loop newest loop
            //}
        }
        else 
        {
            $location = $_POST['location'];
            $countspaces=substr_count($location, ' ');

            //kalau ada space diganti jadi %2520 kalau tidak dibiarkan
            if($countspaces > 0){
                $location=str_replace(' ', '%2520', $location);
            }
            $search = $_POST['text_value'];

            $search = str_replace(' ', '%20', $search);

            //url search
            //newest diubah berubah listnya
            //$url = "https://shopee.co.id/api/v2/search_items/?by=relevancy&keyword=mouse&limit=50&locations=Jawa%2520Barat&newest=0&order=desc&page_type=search";
            $newestcount = 0;
            
            //looping newest untuk dapat banyak data
            //for ($go = 0; $go < 100; $go++){
                
                //if($go>=1)
                //{
                    //$newestcount=$newestcount+50;
                //}

                //limit hanya bisa 50 kalau mau banyak pakai fungsi newest diatas.
                //url dengan filter harga
                $url = "https://shopee.co.id/api/v2/search_items/?by=relevancy&keyword=".$search."&limit=50&locations=".$location."&newest=".$newestcount."&order=desc&page_type=search&price_max=0&price_min=0";

                
                //dapat id semua item
                $profile = http_request($url);

                $profile = json_decode($profile, TRUE);

                //semua id di loop pakai link json punya detail
                $x = 0;
                foreach ($profile["items"] as $profil){
                    if($itemshowed>=$limit){
                        break;
                    }

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

                    echo '<img style="width:350px"src='.$img_url.'><br>';
                    echo $profile["items"][$x]["name"].'<br>';
                    echo 'Rp '.$price.'<br>';
                    echo $shop_name.'<br>';
                    echo $data_detail["item"]["shop_location"].'<br>';
                    echo '<a href='.$link_detail.'>Link</a><br>';
                    $itemshowed++;
                    $x = $x + 1;
                }
            //tutup loop newest loop
            //}
        }
    }

?>

