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
<link href="{$LibraryCss|escape:'htmlall':'UTF-8'}" rel="stylesheet">
<script type="application/javascript">
var ips_failed = "{$MessageAnswer}";
var ips_message = "{l s='Your bank refused the payment!' mod='moneytigo'}";
</script>
<moneytigo-app embedded="true" actuatorid="submit-credit-card-button" token="{$Token}"></moneytigo-app>
<div id="ipsBtnSubmit" style="border-radius: 5px; margin-left: 5px; margin-bottom: 10px;" class="ps-shown-by-js">
  <div class="">
    <button id="submit-credit-card-button" class="btn btn-primary center-block">{l s='Pay now' mod='moneytigo'}</button>
  </div>
  <div class="ps-hidden-by-js" style="display: none;"> </div>
</div>
<!-- checking before terms checked and delibere --> 

<script type="text/javascript" src="{$LibraryJs|escape:'htmlall':'UTF-8'}"></script> 