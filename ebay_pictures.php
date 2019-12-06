<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script src="jquery-1.11.1.min.js"></script>
</head>
<body>

<div id="item_pictures" style="position: fixed; height: 100px; width: 100%; background: red; border-bottom: 2px solid black"></div>
<div style="height: 120px; width: 100%;"></div>

<?php

//error_reporting(E_ALL);  // Turn on all errors, warnings and notices for easier debugging

$query = $_GET['query'] ? $_GET['query'] : 'ceramics';
$page = $_GET['page'] ? $_GET['page'] : '1';
$sort = $_GET['sort'] ? $_GET['sort'] : 'EndTimeSoonest';

ini_set("memory_limit","512M");

if($sort == 'newest')
{
	$sort = 'StartTimeNewest';
}
if($sort == 'soonest')
{
	$sort = 'EndTimeSoonest';
}

// API request variables
$endpoint = 'http://svcs.ebay.com/services/search/FindingService/v1';  // URL to call
$version = '1.0.0';  // API version supported by your application
$appid = 'NONE';  // Replace with your own AppID
$globalid = 'EBAY-US';  // Global ID of the eBay site you want to search (e.g., EBAY-DE)
$query = $query;  // You may want to supply your own query
$safequery = urlencode($query);  // Make the query URL-friendly

// Construct the findItemsByKeywords HTTP GET call
$apicall = "$endpoint?";
$apicall .= "OPERATION-NAME=findItemsByKeywords";
$apicall .= "&SERVICE-VERSION=$version";
$apicall .= "&SECURITY-APPNAME=$appid";
$apicall .= "&GLOBAL-ID=$globalid";
$apicall .= "&RESPONSE-DATA-FORMAT=XML";
$apicall .= "&SERVICE-NAME=FindingService";
$apicall .= "&REST-PAYLOAD";
$apicall .= "&outputSelector=SellerInfo";
$apicall .= "&keywords=$safequery";
$apicall .= "&paginationInput.entriesPerPage=200";
$apicall .= "&sortOrder=$sort";
$apicall .= "&paginationInput.pageNumber=" . $page;


$next_link = 'http://localhost/ebay_pictures.php?query='.$query.'&page=' . ++$page. '&sort=' . $sort;


// Load the call and capture the document returned by eBay API
// $header = array(
// 'X-EBAY-SOA-SERVICE-NAME: FindingService',
// 'X-EBAY-SOA-OPERATION-NAME: getHistograms',
// 'X-EBAY-SOA-SERVICE-VERSION: 1.0.0',
// 'X-EBAY-SOA-GLOBAL-ID: EBAY-US',
// 'X-EBAY-SOA-SECURITY-APPNAME: ' , $appid,
// 'X-EBAY-SOA-REQUEST-DATA-FORMAT: XML'
// );
// $context = stream_context_create(array('http'=>array('method'=>'POST','header'=>$header,'content-type'=>"application-xml")));
// $xml_contents = file_get_contents($apicall,false,$context);
// echo $apicall;
$xml_contents = file_get_contents($apicall,false,$context);
$resp = simplexml_load_string($xml_contents);


// Check to see if the request was successful, else print an error
if ($resp->ack == "Success") {
  $results = '';
  // If the response was loaded, parse it and build links
	foreach($resp->searchResult->item as $item) {
    
	    $item = json_decode(json_encode($item), true);

	    //Debug
	  	if(0)
	 	{
	 		echo "<pre>";
	 		var_export($item);
	 		echo "<pre>";
	 		exit;	
	 	}
	 	
      $item_id = $item['itemId'];
	    $thumbnail = $item['galleryURL'];
	    $url_to_ebay = $item['viewItemURL'];
	    $seller = $item['sellerInfo']['sellerUserName'];


	   	echo '<div style="float: left">';
	    echo '<a style="" onclick="item_images('.$item_id.')" class="ebay_thumbnail"><img style="height: 80px; width: 80px; padding: 0; margin: 0" src="'.$thumbnail.'"></a>';
	    echo '<div onmousedown="window.open(\'http://localhost/ebay_pictures_user.php?seller='.$seller.'&page=1&sort=newest&query=jeans\',\'_blank\'); window.focus();" style="height: 10px; width: 10px; background-color: white; position: relative; left: 70%; bottom: 20px; z-index: 20">';
	    echo '</div>';
	    echo  '</div>';
	
	}
}
// If the response does not indicate 'Success,' print an error
else {
  $results  = "<h3>Oops! The request was not successful. Make sure you are using a valid ";
  $results .= "AppID for the Production environment.</h3>";
}

echo '<div id="next_link" style="float: left">';
echo '<a style="font-size: 70px; text-decoration: none" href="'.$next_link.'" >NEXT LINK</a>';
echo  '</div>';

?>




<script>


$( '#next_link' ).mouseclick(function(event) {
	//if(event.which == 47)
	//{
		<?php echo 'window.location.href = "'.$next_link.'";'; ?>
	//}
});

function item_images(item_id)
{
  $.ajax({
    url: 'ebay_pictures_item_ajax.php',
    type: 'GET',
    data: {query: item_id},
  })
  .done(function(r) {
    console.log(r);
    $("#item_pictures").html(r);
  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    console.log("complete");
  });
}



$('.ebay_thumbnail').click(function(event){
	$('#unseen').show();
});

</script>


</body>
</html>













<?php

/*
array (
  'itemId' => '331128976689',
  'title' => 'HOT New Harry Potter 14.5" Magical Wand Replica Cosplay In Box',
  'globalId' => 'EBAY-US',
  'primaryCategory' =>
  array (
    'categoryId' => '29798',
    'categoryName' => 'Harry Potter',
  ),
  'galleryURL' => 'http://thumbs2.ebaystatic.com/m/mICawRUsvUULmXv2hqI7Y3A/140.j
pg',
  'viewItemURL' => 'http://www.ebay.com/itm/HOT-New-Harry-Potter-14-5-Magical-Wa
nd-Replica-Cosplay-Box-/331128976689',
  'paymentMethod' => 'PayPal',
  'autoPay' => 'false',
  'location' => 'Hong Kong',
  'country' => 'HK',
  'shippingInfo' =>
  array (
    'shippingServiceCost' => '1.0',
    'shippingType' => 'Flat',
    'shipToLocations' => 'Worldwide',
    'expeditedShipping' => 'false',
    'oneDayShippingAvailable' => 'false',
    'handlingTime' => '1',
  ),
  'sellingStatus' =>
  array (
    'currentPrice' => '11.98',
    'convertedCurrentPrice' => '11.98',
    'sellingState' => 'Active',
    'timeLeft' => 'P9DT22H53M49S',
  ),
  'listingInfo' =>
  array (
    'bestOfferEnabled' => 'false',
    'buyItNowAvailable' => 'false',
    'startTime' => '2014-02-13T18:25:22.000Z',
    'endTime' => '2015-11-05T18:30:22.000Z',
    'listingType' => 'StoreInventory',
    'gift' => 'false',
  ),
  'returnsAccepted' => 'true',
  'galleryPlusPictureURL' => 'http://galleryplus.ebayimg.com/ws/web/331128976689
_1_0_1.jpg',
  'condition' =>
  array (
    'conditionId' => '1000',
    'conditionDisplayName' => 'New',
  ),
  'isMultiVariationListing' => 'false',
  'topRatedListing' => 'true',
)
*/

?>