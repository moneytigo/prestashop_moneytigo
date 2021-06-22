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
{extends "$layout"}
{block name="content"}
<div id="moneytigo_redirect"> {if $isCartEmpty == true}
  <p style="color:red; text-align:center;"> {l s='Forbidden ! You couldn\'t view this page. Please make an order. ' mod='moneytigo'} </p>
  {else}
  <h2 style="text-align:center;">{l s='Redirect to the payment page' mod='moneytigo'}</h2>
  <p style="text-align:center;"> <a href="javascript:$('#moneytigo_form').submit();" id="btnForForm">{l s='You will now be redirected to moneytigo. If this does not happen automatically, please press here.' mod='moneytigo'}</a> </p>
  <form name="moneytigo_form" id="moneytigo_form" method="post" action="{$form_action|escape:'htmlall':'UTF-8'}">
    <input type="hidden" name="amount" value="{$amount|escape:'htmlall':'UTF-8'}"/>
    <input type="hidden" name="MerchantKey" id="MerchantKey" value="{$MerchantKey|escape:'htmlall':'UTF-8'}"/>
    <input type="hidden" name="RefOrder" value="{$RefOrder|escape:'htmlall':'UTF-8'}"/>
    <input type="hidden" name="lang" value="{$lang|escape:'htmlall':'UTF-8'}"/>
    <input type="hidden" name="urlOK" value="{$urlOK|escape:'htmlall':'UTF-8'}"/>
    <input type="hidden" name="urlKO" value="{$urlKO|escape:'htmlall':'UTF-8'}"/>
    <input type="hidden" name="urlIPN" value="{$urlIPN|escape:'htmlall':'UTF-8'}"/>
    <input type="hidden" name="Customer_Email" value="{$email_Customer|escape:'htmlall':'UTF-8'}"/>
    <input type="hidden" name="Customer_Name" value="{$name_Customer|escape:'htmlall':'UTF-8'} {$lastname_Customer|escape:'htmlall':'UTF-8'}"/>
    <input type="hidden" name="Lease" value="{$Lease|escape:'htmlall':'UTF-8'}"/>
    <input type="submit" style="visibility:hidden; display:none"/>
  </form>
  <script type="text/javascript">
		document.getElementById('moneytigo_form').submit();
		var form = document.getElementById("moneytigo_form");
		document.getElementById("btnForForm").addEventListener("click", function () {
			form.submit();
		});
	</script> 
  {/if} </div>
{/block}