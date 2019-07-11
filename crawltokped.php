<form method="POST" action="crawltokped.php">
    <input type="text" name="detail_link">
    <h4>Link harus dari tokopedia dekstop.</h4>
</form>

<?php


    // example of how to use basic selector to retrieve HTML contents
    include('simple_html_dom.php');
    
    // get DOM from URL or file
    $html = file_get_html('https://shopee.co.id/Bandai-HG-1-144-Unicorn-Gundam-Banshee-norn-destroy-mode-i.23261849.1268573108');

    // find item image ads
    foreach($html->find('
            div[id=content-container] 
            div.page-container 
            div.container 
            div.fe-discovery-root 
            div._3c4LNtKh 
            div div._3FbVaYOa 
            div.RGXsSfP_ 
            div._1sn05YIP 
            div._1bgl4SK7 
            div._1AgQNQHv 
            div._3ix9kHTn 
            div 
            div._1A0HOpyr 
            div.clearfix 
            div._1hoMwZCy 
            div._2-lwkV9o 
            div._12z_Env0 
            a 
            div 
            div._1Xxg9m4U') as $e)
    {
        echo $e->outertext . '<br>';
        break;
    }

    // find item image 
    foreach($html->find('div[id=content-container] div.container-product div.clearfix div.rvm-left-column div.rvm-pdp-product div.rvm-left-column--left div.product-detail__img-holder div.content-img ') as $e)
    {
        echo $e->outertext . '<br>';
        break;
    }
        
    // find item title
    foreach($html->find('div.rvm-left-column--right h1.rvm-product-title') as $e)
        echo $e->innertext . '<br>';

     // find item price
    foreach($html->find('div.rvm-price-holder div.rvm-price span') as $e)
        echo $e->innertext;
    echo '<br>';
     
      // find shop name
    foreach($html->find('div.sticky-footer div.container div.pdp-shop div.pdp-shop__info div.pdp-shop__info__name-wrapper span') as $e)
        echo $e->innertext . '<br>';
        echo $html->plaintext;

?>