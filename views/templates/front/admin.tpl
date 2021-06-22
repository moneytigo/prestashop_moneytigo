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
<div class="alert alert-info" style="margin-top: 10px; height: 60px;"> <img src="../modules/moneytigo/logo.png" style="float:left; margin-right:15px;">
  <p><strong>{l s='This module allows you to accept credit card payments with MoneyTigo' mod='moneytigo'}</strong></p>
</div>
<div id="config_moneytigodirect" style="max-width:900px; margin-left:auto; margin-right:auto;"> {if $display_msg_information == '1'}
  <div class="panel">
    <div class="alert_moneytigo_admin{$msg_information_class|escape:'htmlall':'UTF-8'}"> {$msg_information|escape:'htmlall':'UTF-8'} </div>
  </div>
  {/if}
  {if $display_msg_confirmation == '1'}
  <div class="panel">
    <div class="alert_moneytigo_admin{$msg_confirmation_class|escape:'htmlall':'UTF-8'}"> {$msg_confirmation|escape:'htmlall':'UTF-8'} </div>
  </div>
  {/if}
  <div role="tabpanel" style="margin-top: 20px;">
    <ul role="tablist" class="nav nav-tabs">
      <li class="{$activeTabList_1|escape:'htmlall':'UTF-8'}" role="presentation"> <a data-toggle="tab" role="tab" id="D" aria-controls="config_moneytigo_div" href="#config_moneytigo_div" style="color: darkblue; font-weight:bold;"> {l s='Settings' mod='moneytigo'} </a> </li>
      <li class="{$activeTabList_2|escape:'htmlall':'UTF-8'}" role="presentation"> <a data-toggle="tab" role="tab" id="faq_moneytigo" aria-controls="faq_moneytigo_div" href="#faq_moneytigo_div" style="color: darkblue; font-weight:bold;"> {l s='Help (Faq)' mod='moneytigo'} </a> </li>
    </ul>
    <div class="tab-content">
      <div id="config_moneytigo_div" class="tab-pane {$activeTab_1|escape:'htmlall':'UTF-8'}" role="tabpanel">
        <div class="panel" style="min-height:470px;">
          <form method="post" action="{$actionForm|escape:'htmlall':'UTF-8'}" class="account-creation" id="formMoneyTigo">
            <input type="hidden" id="ips_action" name="MONEYTIGO_ADMIN_ACTION" value="UPDATE">
            <label for="api_key"> {$label_api_key|escape:'htmlall':'UTF-8'} </label>
            <input type="text" id="api_key" name="MONEYTIGO_GATEWAY_API_KEY" value="{$value_api_key|escape:'htmlall':'UTF-8'}">
            <label for="crypt_key"> {$label_crypt_key|escape:'htmlall':'UTF-8'} </label>
            <input type="text" id="crypt_key" name="MONEYTIGO_GATEWAY_CRYPT_KEY" value="{$value_crypt_key|escape:'htmlall':'UTF-8'}">
            <hr>
            <p> <a href="https://app.moneytigo.com/user/register" title="{l s='Open MoneyTigo Account for free' mod='moneytigo'}" target="_blank">{l s='Open an account immediately' mod='moneytigo'}</a></p>
            <hr>
			<label for="integrated">{l s='Activation of IFRAME mode (InSite)' mod='moneytigo'}</label>
            <span class="switch prestashop-switch">
            <input type="radio" name="MONEYTIGO_INTEGRATED" id="activer_integrated" value="on"{$integrated_on|escape:'htmlall':'UTF-8'}/>
            <label for="activer_integrated">{l s='Enable' mod='moneytigo'}</label>
            <input type="radio" name="MONEYTIGO_INTEGRATED" id="desactiver_integrated" value="off"{$integrated_off|escape:'htmlall':'UTF-8'}/>
            <label for="desactiver_integrated">{l s='Disable' mod='moneytigo'}</label>
            <a class="slide-button btn"></a> 
			<p class="help-block text-left">{l s='If you activate this mode, your customer will not be redirected to MoneyTigo at the time of payment, he will remain on your order page, if so, he will be redirected to MoneyTigo.' mod='moneytigo'} 
			</p>
			</span><br>
			<hr>  
			  
            <label for="p3f">{l s='Payment split in 3x' mod='moneytigo'}</label>
            <span class="switch prestashop-switch">
            <input type="radio" name="MONEYTIGO_GATEWAY_P3F" id="activer_p3f" value="on"{$p3f_on|escape:'htmlall':'UTF-8'}/>
            <label for="activer_p3f">{l s='Enable' mod='moneytigo'}</label>
            <input type="radio" name="MONEYTIGO_GATEWAY_P3F" id="desactiver_p3f" value="off"{$p3f_off|escape:'htmlall':'UTF-8'}/>
            <label for="desactiver_p3f">{l s='Disable' mod='moneytigo'}</label>
            <a class="slide-button btn"></a> </span>
            <label for="seuil_p3f">{l s='Split payment trigger threshold' mod='moneytigo'} </label>
            <input type="text" id="seuil_p3f" name="MONEYTIGO_TRIGGER_P3F" value="{$seuil_p3f|escape:'htmlall':'UTF-8'}" placeholder="100" ; >
            <span class="badge badge-danger"> {l s='(Minimum 100 Euros)' mod='moneytigo'}</span>
            <hr>
            <input type="button" name="submitMoneytigo" class="btn btn-default btn-primary" value="{l s='Update configuration' mod='moneytigo'}" id="submitMoneytigo" onclick="MoneyTigoFX.validateFormMoneyTigo();" style="margin-top:-5px;">
          </form>
        </div>
      </div>
      <div id="faq_moneytigo_div" class="tab-pane {$activeTab_2|escape:'htmlall':'UTF-8'}" role="tabpanel">
        <div class="panel" style="min-height:650px;">
          <h2 class="colorBlueMoneyTigo">{l s='I Need one Merchant Account for use MoneyTigo ?' mod='moneytigo'}</h2>
          <p>{l s='No,  the Merchant account is included at the time of subscription. You have no further steps to take with your bank or another organization.' mod='moneytigo'}</p>
          <h2 class="colorBlueMoneyTigo">{l s='How much does it cost ?' mod='moneytigo'}</h2>
          <p>{l s='You will not have any monthly subscriptions or other fees until you use the solution and have a successful transaction. Yes, you only pay when you use the solution and also only when payments are accepted' mod='moneytigo'}</p>
          <p>{l s='Pay-as-you-go billing' mod='moneytigo'}</p>
          <ul>
            <li>{l s='A single rate regardless of the country of the card from 0.9% to 2.3%' mod='moneytigo'}</li>
            <li>{l s='Only on accepted transactions' mod='moneytigo'}</li>
            <li>{l s='No fees on declined or refunded transactions' mod='moneytigo'}</li>
          </ul>
          <p>{l s='If you need a custom offer this is possible just contact us by email at hello@moneytigo.com depending on your profile an offer will be sent to you.' mod='moneytigo'}</p>
          <h2 class="colorBlueMoneyTigo">{l s='How the money is transferred to my bank account ?' mod='moneytigo'}</h2>
          <ul>
            <li>{l s='You can make manual withdrawal requests in addition' mod='moneytigo'}</li>
            <li>{l s='We transfer your sales according to your preferences :' mod='moneytigo'}
              <ul>
                <li>{l s='Everyday' mod='moneytigo'}</li>
                <li>{l s='Every 2 days' mod='moneytigo'}</li>
                <li>{l s='Or more' mod='moneytigo'}</li>
                <li>{l s='You decide that !' mod='moneytigo'}</li>
              </ul>
            </li>
          </ul>
          <h2 class="colorBlueMoneyTigo">{l s='How long does it take to open and verify an account ?' mod='moneytigo'}</h2>
          <p>{l s='The opening is fast in less than 5 minutes, For the verification you must send us your documents, we validate the accounts in the morning and in the afternoon generally the verification is carried out in 1 working day!' mod='moneytigo'}.</p>
          <p>{l s='But don\'t forget you will be able to collect your customers from the opening!' mod='moneytigo'}</p>
          <h2 class="colorBlueMoneyTigo">{l s='How to configure MoneyTigo?' mod='moneytigo'}</h2>
          <p>{l s='Add your website, in your MoneyTigo dashboard, retrieve the API key as well as your secret key and indicate it in the  settings section of this module. Then you need to add to your dashboard in the contract section the IP address of the server that hosts your website.' mod='moneytigo'}</p>
          <h2 class="colorBlueMoneyTigo">{l s='What are the payment methods accepted by MoneyTigo?' mod='moneytigo'}</h2>
          <p>{l s='Visa, Carte Bleue, Mastercard, Amex, Electron, Maestro ... We are increasing day by day the available modes' mod='moneytigo'}</p>
          <h2 class="colorBlueMoneyTigo">{l s='Who are we ?' mod='moneytigo'}</h2>
          <p>{l s='MoneyTigo, is a payment solution published by the IPS INTERNATIONNAL SAS, based in France in PARIS and BORDEAUX. We are a financial institution' mod='moneytigo'} </p>
          <p>&nbsp;</p>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
    $("#csrf").val(makeid(20));
    function makeid(number) {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for (var i = 0; i < number; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }
</script>