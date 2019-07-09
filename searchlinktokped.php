<form method="POST" action="searchlinktokped.php">
    <input type="text" name="detail_link">
    <h4>Link harus dari tokopedia dekstop.</h4>
</form>

<?php
    $search = $_POST['detail_link'];
    echo $search;

    // example of how to use basic selector to retrieve HTML contents
    include('simple_html_dom.php');
    
    // get DOM from URL or file
    $html = file_get_html($search);

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
?>