<?php
 
require_once __DIR__ . '/vendor/autoload.php';

if(isset($_POST['submit'])){
  $searchValue = $_POST['search-ticket'];
  $companyName = $_POST['company-name'];
}

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://demo.fareharbor.com/api/external/v1/companies/'. $companyName . '/bookings/' . $searchValue . '/?api-app=f93f34aa-dcd9-452d-b23e-f5a556d3de58&api-user=1faf9476-099a-48d5-bc86-f5012dfbd372',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Cookie: fh-content-language=en-us; fh-email=""; fh-name="Global Amenities API"; fh-shortname=globalamenities-api-testing; fh-target-language=en-us; fh-token=437gi247ytrwhfi8wjfhi5776w7d4; fh-units-language=en-us; fh-username=globalamenitiesapi'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$result = json_decode($response, true);

$pkNum = $result['booking']['pk'];
$orderId = $result['booking']['uuid'];
$customerName = $result['booking']['contact']['name'];
$orderHash = $result['booking']['display_id'];
$arrivalTime = $result['booking']['arrival']['time'];
$affiliateCompany = $result['booking']['affiliate_company']['name']; 
$itemName = $result['booking']['availability']['item']['name'];
$confirmationUrl = $result['booking']['confirmation_url'];

use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QRFpdf;

$data = $orderId;

// quick and simple:
$qrCode = '<img class="qr-image" src="'.(new QRCode)->render($data).'" alt="QR Code" />';
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Fareharbour-Ticket</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
  
  <div class="searchfield">
    <form action="" method="post">
    <select name="company-name" id="comp-name">
      <option value="bodyglove">Body Glove</option>
      <option value="mauioceancenter">Maui Ocean Center</option>
    </select>
    <input type="text" name="search-ticket" class="search" placeholder="Enter your Ticket Number">
    <input type="submit" class="search-btn" name="submit" value="search">
    </form>
  </div>
    <div class="order_upper" id="content">
    	 <div class="order-inner-content">
    	 	 <div class="order-inner-content_left">
    	 	 	 <div class="order_num">
    	 	 	   <p class="or_hast">Order <?php echo $orderHash; ?></p> 
    	 	 	 </div>
                <div class="order_content_punama">
                	<p class="ticket_sky"><?php echo "Ticket to ". $itemName ." Purchased by ". $customerName; ?></p>
                	<p class="ticket_sky_ptt"><?php echo $affiliateCompany; ?></p>
                </div>

    	 	 </div>
         <div class="ticket-qrcode"><?php echo $qrCode; ?></div>
    	 	 <div class="order_content_rigt">
    	 	 	 <div class="Purchased_div">
					<span>Arrival Time:</span><br>
					<b><?php echo $arrivalTime; ?></b>
				</div>
    	 	 </div>
    	 </div>
    </div>
    <div class="dwnld-btn">
      <button id="download-ticket" class="btn">Download Ticket</button>
    </div>
    <div class="confirmation">
      <button class="btn"><a target="_blank" href="<?php echo $confirmationUrl; ?>">Confirm Booking</a></button>
    </div>

    <script src="script.js"></script>
</body>
</html>


