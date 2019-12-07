<!DOCTYPE html>
<html>
<head>
  <title></title>
  <script src="jquery-1.11.1.min.js"></script>
  <link rel="stylesheet" href="bootstrap.min.css">
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
<div id="item_pictures" style="position: fixed; height: 100px; width: 100%; background: red; border-bottom: 2px solid black"></div>
<div style="height: 120px; width: 100%;"></div>
<div >
<?php
error_reporting(E_ALL);  // Turn on all errors, warnings and notices for easier debugging
$seller = $_GET['seller'] ? $_GET['seller'] : '64harman';
$query = $_GET['query'] ? $_GET['query'] : 'figure';
$page = $_GET['page'] ? $_GET['page'] : '1';
$sort = $_GET['sort'] ? $_GET['sort'] : 'EndTimeSoonest';
if($sort == 'newest')
{
  $sort = 'StartTimeNewest';
}
if($sort == 'soonest')
{
  $sort = 'EndTimeSoonest';
}
$next_link = 'http://localhost/ebay_pictures_user.php?seller='.$seller.'&page=' . ++$page . '&query=' . $query . "&sort=" . $sort;
//Search Jeans By User
$appid = 'NONE';  // Replace with your own AppID
$apicall = 'http://svcs.ebay.com/services/search/FindingService/v1?OPERATION-NAME=findItemsAdvanced&SERVICE-VERSION=1.7.0&SECURITY-APPNAME='.$appid.'&RESPONSE-DATA-FORMAT=XML&REST-PAYLOAD&itemFilter(0).name=Seller&itemFilter(0).value='.$seller.'&paginationInput.entriesPerPage=100&outputSelector=SellerInfo&keywords='.urlencode($query) . "&paginationInput.pageNumber=" . $page . "&sortOrder=" . $sort;
$resp = simplexml_load_file($apicall);
$response_array = json_decode(json_encode($resp), true);
// var_export($response_array);
// http://www.ebay.com/sch/m.html?_odkw=&_ssn=fantasygirl_t1&_nkw=jeans&_sacat=0
// exit;
if(0)
{
  echo "<pre>";
  var_export($response_array);
  echo "<pre>";
  exit; 
}
foreach($response_array['searchResult']['item'] as $item) 
{
    $item = json_decode(json_encode($item), true);
    //Debug
    if(0)
  {
    echo "<pre>";
    var_export($item);
    echo "<pre>";
    exit; 
  }
    $thumbnail = $item['galleryURL'];
    $url_to_ebay = $item['viewItemURL'];
    $title = $item['title'];
    echo '<div style="float: left">';
    echo '<a style="" onclick="item_images('.$item['itemId'].')" class="ebay_thumbnail"><img title="'.$title.'" data-toggle="tooltip" style="height: 90px; width: 90px;" src="'.$thumbnail.'"></a>';
    echo  '</div>';
}
echo '<div id="next_link" style="float: left">';
echo '<a style="font-size: 70px; text-decoration: none" href="'.$next_link.'" >NEXT LINK</a>';
echo  '</div>';
?>
<script>
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
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>
</div>
</body>
</html>