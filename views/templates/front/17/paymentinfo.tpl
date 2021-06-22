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
<script type="application/javascript">
var ips_failed = "{$MessageAnswer}";
var ips_message = "{l s='Your bank refused the payment!' mod='moneytigo'}";
</script>
<div id="LoadingIPS" style="display: none;">
  <center>
    <img src="{$LoaderPath}" style="width: 90px;"><br>
    {l s='One moment please' mod='moneytigo'}
  </center>
</div>
<div id="credit-card-iframe" style="margin-left: -50px;"> </div>
<div id="ipsBtnSubmit" style="margin-left: -50px;  margin-bottom: 20px;">
  <div class="">
    <button id="submit-credit-card-button" class="btn btn-primary center-block">{l s='Pay and validate my order' mod='moneytigo'}</button>
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
	  			  				"urlOK": "{$UriReturn}",
				"urlKO": "{$UriReturn}",
                  "urlIPN" : "{$UriIPN}",
                  "HideSubmitBtn": true
  };
  </script> 
  <script type="application/javascript">
  var creditCard = Ips.createCreditCardIframe(btn_container_id,iframe_container_id,PaymentParameter);
  document.getElementById(btn_container_id).addEventListener('click', function (event) {

  const OldContent = document.getElementById(iframe_container_id).innerHTML;
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