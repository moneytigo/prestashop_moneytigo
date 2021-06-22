<?php
header("Cache-Control: no-cache, must-revalidate");
$MerchantInfo = Merchant::Get_Merchant_By_ApiKey();
?>
<!doctype html>
<html <?php if($_SESSION['lang'] == 'he') { ?>dir="rtl"<?php } ?> class="no-js" lang="<?php if($_SESSION['lang']) { echo $_SESSION['lang']; } else { echo 'fr'; } ?>">
<head>
<meta charset="UTF-8">
<title><?=_Title?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="<?php echo UrlAssets; ?>fonts/css/all.min.css">
 <!--
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">-->
<link rel="stylesheet" href="<?php echo UrlAssets; ?>css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo UrlAssets; ?>css/mdb.min.css">
<link rel="stylesheet" href="<?php echo UrlAssets; ?>css/style.css">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js?v=2.2"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js?v=2.2"></script>
<![endif]-->
<style type="text/css">
iframe{height:100%}
.invalid-feedback{
background-color: red;
color: white;
padding-left: 5px;
font-weight: bold;
}
.map-container{
overflow:hidden;
padding-bottom:56.25%;
position:relative;
height:0;
}
.map-container iframe{
left:0;
top:0;
height:100%;
width:100%;
position:absolute;
}
.BackGroundCustom {
background: url(merchantImg.php?BgName=<?php if($MerchantInfo['Custom_CSS_Bg_Img']) { echo $MerchantInfo['Custom_CSS_Bg_Img']; } else { echo "default.jpg";} ?>) no-repeat center fixed;  -webkit-background-size: cover; background-size: cover;	
}
.InputLabel {
color: <?php if($MerchantInfo['Custom_CSS_Input_Title']) { echo $MerchantInfo['Custom_CSS_Input_Title']; } else { echo "black";} ?>;
}
.BorderInput {
border: 1px solid <?php if($MerchantInfo['Custom_CSS_Input_Border']) { echo $MerchantInfo['Custom_CSS_Input_Border']; } else { echo "black";} ?>;
}	
.paymentpanel{
	border: 1px solid <?php if($MerchantInfo['Custom_CSS_Border_Panel']) { echo $MerchantInfo['Custom_CSS_Border_Panel']; } else { echo "white";} ?>;
}
.btnpayment {
		border: 1px solid none;
		background-color: <?php if($MerchantInfo['Custom_CSS_Button_Bg']) { echo $MerchantInfo['Custom_CSS_Button_Bg']; } else { echo "dodgerblue"; } ?>;
		color:<?php if($MerchantInfo['Custom_CSS_Button_Text']) { echo $MerchantInfo['Custom_CSS_Button_Text']; } else { echo "white";} ?>;
}
.btnpayment:hover {
border: 1px solid none;
color:<?php if($MerchantInfo['Custom_CSS_Button_Hover']) { echo $MerchantInfo['Custom_CSS_Button_Hover']; } else { echo "white"; } ?>;
}
 
.caret
{
color:white !important;
}
.select-dropdown
{
color:white !important;
}
	.ErrorMade{
 	max-height: 15px;
	margin-top: 1px;
	font-size: 10px;
    background-color: <?php if($MerchantInfo['Custom_CSS_Err_BG']) { echo $MerchantInfo['Custom_CSS_Err_BG']; } else { echo "red"; } ?>;
    color: <?php if($MerchantInfo['Custom_CSS_Err_TXT']) { echo $MerchantInfo['Custom_CSS_Err_TXT']; } else { echo "white"; } ?>;
    padding-left: 5px;
    font-weight: bold;
	}
.valid {
		border: 1px solid green;
}
</style>
</head>
<body style="margin: 0px 0px 0px 0px;">
<!-- Credit card form content -->
<div class="tab-content" style="margin-top: -25px;">
<!-- credit card info-->
<div id="nav-tab-card" class="tab-pane fade show active">
<div id="IPSStatus" name="IPSStatus" class="toipsstat"></div>
<div id="IPSPaymentDialog" name="IPSPaymentDialog"> 
	<?php if($_GET['isFailed'] == "yes") { ?>
	<div class="col-md-12 ErrorMade"><?php echo _JS_AUTHKO; ?></div>
	<?php } ?>
<form id="cc-form-ips" action="<?php echo UrlPAID; ?>secure-node-ems/validate-purchase" method="POST" novalidate class="ProcessPayment" role="form">
<!-- card number field -->
<div class="form-group">
<div id="creditCardNumberGroup" <?php if($_SESSION['lang'] == "he") { ?> style="text-align: right;"<?php } ?>> 
<label id="creditCardNumberLabel" for="creditCardNumber" class="InputLabel"><?php echo _CardNumber; ?></label> 
 
<input type="tel" class="form-control BorderInput" id="creditCardNumber" <?php if($_SESSION['lang'] == "he") { ?>dir="ltr"<?php } ?> name="ccnum" maxlength="16"  placeholder="0000 1111 2222 3333" aria-describedby="creditCardNumberLabel" required autocomplete="off"> 
 
<div id="creditCardNumberInvalidFeedback" class="col-md-12 ErrorMade"></div> 
 
</div>
</div>
<!-- end card number field -->
<div class="row">
<!-- expiration card field -->
<div class="col-sm-6" <?php if($_SESSION['lang'] == "he") { ?> style="text-align: right;"<?php } ?>> 
<label id="expirationDateLabel" for="expirationDate" class="InputLabel"><span class="hidden-xs"><?php echo _ExpirateCard; ?></span></label> 
 
<input type="tel" class="form-control BorderInput" <?php if($_SESSION['lang'] == "he") { ?>dir="ltr"<?php } ?> id="expirationDate" name="expiry_date" maxlength="5" placeholder="<?php echo _PlaceHolderExpire; ?>" required autocomplete="off"> 
<div  class="col-md-12 ErrorMade" id="expirationDateInvalidFeedback"></div>           
 
</div>

<!-- end expiration card field -->
<!-- cvv card field -->
<div class="col-sm-6" <?php if($_SESSION['lang'] == "he") { ?> style="text-align: right;"<?php } ?>>
<label class="InputLabel"><?php echo _CvvCode; ?></label>	
<input type="tel" class="form-control BorderInput" id="cvx" <?php if($_SESSION['lang'] == "he") { ?>dir="ltr"<?php } ?> name="cvx" placeholder="123" required autocomplete="off">
	<div  class="col-md-12 ErrorMade" id="cvxInvalidFeedback"></div>  
	<span style="font-size: 12px;"><i class="fa fa-question-circle"></i> <?php echo _CvvHelp; ?></span>
          
</div>
<!-- end cvv card field -->
</div>
	
 
	
 
<button id="validationButton" class="subscribe btn btn-block btn-sm rounded-pill shadow-sm btnips btnpayment mt-4 mb-4" onClick="SizingNew()" type="submit" 
		
		<?php if($_GET['HideSubmitBtn'] === "true" || $_POST['HideSubmitBtn'] === "true") { ?> style="display: none" <?php } ?>
		
		><span style="font-size: 18px;"><?php echo _Paid; ?>  <i class="far fa-credit-card"></i></span></button>
 

<?php if($_SESSION['Subscribe'] == "Yes" or $_SESSION['Lease'] == "2" or $_SESSION['Lease'] == "3" or $_SESSION['Lease'] == "4") { ?>
<input type="checkbox" class="form-check-input" name="memorize_credit_card" id="memorizeCreditCard" checked>
<?php } ?>
<!-- if have subscribtion trial -->
	
 
	
 
</form>
</div>
</div>
</div>
<!-- End -->
<script type="text/javascript">
var TxtLoading = "<?php echo _JS_Loading; ?>";
var TxtAuthAttempts = "<?php echo _JS_AskAuth; ?>";
var TxtOneMmPls = "<?php echo _JS_OIP; ?>";
var TxtSacAttemps = "<?php echo _JS_3DSASK; ?>";
var TxtRequired = "<?php echo _JS_REQUIRED; ?>";
var TxtCardInvalid = "<?php echo _JS_CARDNOTVALID; ?>";
var TxtCvxError = "<?php echo _JS_3DIGITSMIN; ?>";
var TxtFailedAuth = "<?php echo _JS_AUTHKO; ?>";
var TxtPaymentSuccess = "<?php echo _JS_AUTHOK; ?>";
var TxtMailSent = "<?php echo _JS_AUTHMAILOK; ?>";
var TxtReturnToMerchant = "<?php echo _JS_RETURN; ?>";
var TxtPaymentFaiOrCan = "<?php echo _JS_AUTHCANC; ?>";
var TxtFinalizePayment = "<?php echo _JS_AUTHFINALIZE; ?>";
var IsIntegrated = "<?php echo $MerchantInfo['integratedmode']; ?>";
var TxtTechnicalError = "<?php echo _JS_TECHNICAL; ?>";
var CardExpireOne = "<?php echo _Card_Month_Help; ?>";
var CardExpireTwo = "<?php echo _Card_Year_Help; ?>";
var CardDateExpired = "<?php echo _Card_Expired_Help; ?>";
</script>
<script type="text/javascript" src="<?php echo UrlAssets; ?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo UrlAssets; ?>js/popper.min.js"></script>
<script type="text/javascript" src="<?php echo UrlAssets; ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo UrlAssets; ?>js/mdb.min.js"></script>
<script type="text/javascript" src="<?php echo UrlAssets; ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo UrlAssets; ?>js/additional-methods.min.js"></script>
<script type="text/javascript" src="<?php echo UrlAssets; ?>js/jquery.mask.min.js"></script>
 
	
	 
	
	
<script type="text/javascript">
var TransactionId = "<?php echo $_SESSION['PspTXID']; ?>";
var OriginalToken = "<?php echo $_SESSION['PspTOKEN']; ?>";
var OriginalUriPaid = "<?php echo UrlPAID."secure-node-ems/purchase-form"; ?>";
$(document).ready(function() {
$('.mdb-select').materialSelect();
});
</script>
<script type="text/javascript" src="<?php echo UrlAssets; ?>js/IpsSecureCbEmbededIntegrated.js?version=<?php echo time(); ?>"></script>
<script type="text/javascript" src="<?php echo UrlAssets; ?>js/iframeResizer.contentWindow.min.js"></script>
<script type="application/javascript">
		
function getDocHeight(doc) {
doc = doc || document;
var body = doc.body, html = doc.documentElement;
var height = Math.max( body.scrollHeight, body.offsetHeight, 
html.clientHeight, html.scrollHeight, html.offsetHeight );
return height;
} 
		
var PostResult = JSON.stringify( {'event_id': 'NewSize', 'Height': getDocHeight(), 'ref_id': "ddddddd"} );
parent.postMessage(PostResult, "*");
//need also sent message if its click on button submit
function SizingNew() {

	var PostResult = JSON.stringify( {'event_id': 'NewSize', 'Height': document.body.scrollHeight, 'ref_id': "ddddddd"} );
	parent.postMessage(PostResult, "*");
}
	
window.addEventListener("message", function (e) {
		if(e.data.event_id === "StartSubmit" && e.data.data === "Lauch")
			{ 
				var form = document.getElementById("validationButton");
  				form.click();
				var PostResult = JSON.stringify( {'event_id': 'NewSize', 'Height': getDocHeight(), 'ref_id': "ddddddd"} );
				parent.postMessage(PostResult, "*");
			}
});

var LastHeightIs = document.body.scrollHeight;
window.onresize = function(event) {

var newHeightIs = document.body.scrollHeight;
var isnewheight = newHeightIs-LastHeightIs;
var PostResult = JSON.stringify( {'event_id': 'NewSize', 'Height': isnewheight, 'ref_id': "ddddddd"} );
parent.postMessage(PostResult, "*");
return;
};
			
	</script>
</body>
</html> 