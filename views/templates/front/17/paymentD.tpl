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
{if $AuthorizedFailed =='1'} 
<script type="application/javascript">
var ips_failed = '{$AuthorizedFailed}';
var ips_message = "{l s='Your bank refused the payment!' mod='moneytigo'}";
</script> 
{/if}
<p class="payment_module"><img id="CB" class="moneytigo-logo" src="{$path_img|escape:'htmlall':'UTF-8'}/views/img/carte.png" alt="carte" width="140px"/><br>
  {l s='You will be redirected to our secure payment server' mod='moneytigo'}</p>
