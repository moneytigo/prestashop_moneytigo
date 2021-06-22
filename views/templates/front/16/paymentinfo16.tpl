{*
    * NOTICE OF LICENSE
    *
    * This file is create by Ips
    * For the installation of the software in your application
    * You accept the licence agreement.
    *
    * You must not modify, adapt or create derivative works of this source code
    *
    *  @author    Ips
    *  @copyright 2018-2021 IPS INTERNATIONNAL SAS
    *  @license   moneytigo.com
*}
{if $p3f =='YES'}
<p class="payment_module"> <a id="P3F" class="moneytigo-logo-link bankwire" href="{$linkPayment3f|escape:'htmlall':'UTF-8'}" title="{l s='Pay in three time with moneytigo' mod='moneytigo'}"> <img id="CB" class="moneytigo-logo" src="{$path_img|escape:'htmlall':'UTF-8'}/views/img/carte.png" alt="{l s='Pay in three time with moneytigo' mod='moneytigo'}" width="140px"/> {l s='Pay in three time with moneytigo' mod='moneytigo'} </a> </p>
{/if}
{if $p3f =='INS_CART_AMOUNT'}
<p class="payment_module">
<p id="P3F" class="alert alert-info">{l s='3x payment not available, because the minimum order is :' mod='moneytigo'} {$minp3f|escape:'htmlall':'UTF-8'} Euros.</p>
</p>
{/if}
<p class="payment_module">
<div class="row">
  <div class="col-xs-12">
    <div class="payment_module" style="border: 1px solid #A09F9F;">
      <h3 style="padding: 5px; color: black; margin:0px;"> {l s='Credit card payment' mod='moneytigo'} <img id="CB" class="moneytigo-logo" src="{$path_img|escape:'htmlall':'UTF-8'}/views/img/carte.png" alt="{l s='Pay by credit card with moneytigo' mod='moneytigo'}" width="140px"/> </h3>
      <div class="alert" style="background-color: #9FCDFF; color: black;
							  ">{l s='Enter your card information to complete your purchase' mod='moneytigo'}</div>
      <script type="application/javascript">
var ips_failed = "{$MessageAnswer}";
var ips_message = "{l s='Your bank refused the payment!' mod='moneytigo'}";
if(ips_failed == 1)
	{
		$( ".center_column" ).prepend( "<div class='alert alert-danger'>"+ips_message+"</div>" );
	}
</script>
      <div id="LoadingIPS" style="display: none;">
        <center>
          <img src="{$LoaderPath}" style="width: 90px;"><br>
          {l s='One moment please' mod='moneytigo'}
        </center>
      </div>
      <div id="credit-card-iframe" > </div>
      <div id="ipsBtnSubmit"  >
        <div class="text-right" style="padding: 5px;">
          <button id="submit-credit-card-button" class="btn btn-success center-block">{l s='Pay and validate my order' mod='moneytigo'}</button>
        </div>
        <div class="ps-hidden-by-js" style="display: none;"> </div>
      </div>
      <script type="text/javascript" src="{$Library|escape:'htmlall':'UTF-8'}"></script> 
      <script type="application/javascript">
var iframe_container_id = "credit-card-iframe";
var btn_container_id = "submit-credit-card-button";
var PaymentParameter = {
                "MerchantKey": "{$MerchantKey}",
                "amount": "{$TotalToPaid}",
                "RefOrder": "{$MerchantRef}",
                "Customer_Email": "{$Customer_Email}",
                "Customer_Name" : "{$Customer_Name}", 
				"Customer_FirstName" : "{$Customer_FirstName}",
				"urlIPN" : "{$UriIPN}",
				"urlOK": "{$UriReturn}",
				"urlKO": "{$UriReturn}",
                "HideSubmitBtn": true
};
</script> 
      <script type="application/javascript">
var creditCard = Ips.createCreditCardIframe(btn_container_id,iframe_container_id,PaymentParameter);
document.getElementById(btn_container_id).addEventListener('click', function (event) {

const OldContent = document.getElementById(iframe_container_id).innerHTML; //save payment form
             creditCard.Execute(function (response) { 
                var response = JSON.parse(response);

                if (response.error){
                }else {
                    if(response.Status == "SUCCESS")
                        {
							document.getElementById('LoadingIPS').style.display = 'block';
							var returnurl = "{$UriReturn}";
							var divers = document.createElement('div');
							divers.innerHTML = returnurl
							var decodeduri = divers.firstChild.nodeValue;

                        document.location.href= decodeduri;
						 

                        }
                    else if(response.Status == "FAILED")
                    {
						    document.getElementById('LoadingIPS').style.display = 'block';
						    var returnurl = "{$UriReturn}";
							var divers = document.createElement('div');
							divers.innerHTML = returnurl
							var decodeduri = divers.firstChild.nodeValue;
						 	document.location.href= decodeduri;
                    }
                }
            });

});
</script> 
    </div>
  </div>
</div>
</p>
