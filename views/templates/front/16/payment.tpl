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
if(ips_failed == 1)
	{
		$( ".center_column" ).prepend( "<div class='alert alert-danger'>"+ips_message+"</div>" );
	}
</script>

<p class="payment_module">
	<a class="moneytigo-logo-link bankwire" href="{$linkPayment|escape:'htmlall':'UTF-8'}" title="{l s='Pay by credit card with moneytigo' mod='moneytigo'}">
		
		<img id="CB" class="moneytigo-logo" src="{$path_img|escape:'htmlall':'UTF-8'}/views/img/carte.png" alt="{l s='Pay by credit card with moneytigo' mod='moneytigo'}" width="140px"/>
		 
		{l s='Pay with moneytigo' mod='moneytigo'}
	</a>
</p>
{if $p3f =='YES'}


<p class="payment_module">
	<a id="P3F" class="moneytigo-logo-link bankwire" href="{$linkPayment3f|escape:'htmlall':'UTF-8'}" title="{l s='Pay in three time with moneytigo' mod='moneytigo'}">
				<img id="CB" class="moneytigo-logo" src="{$path_img|escape:'htmlall':'UTF-8'}/views/img/carte.png" alt="{l s='Pay in three time with moneytigo' mod='moneytigo'}" width="140px"/>

		{l s='Pay in three time with moneytigo' mod='moneytigo'}
	</a>
</p>
{/if}
{if $p3f =='INS_CART_AMOUNT'}

<p class="payment_module">
	<p id="P3F" class="alert alert-info">{l s='3x payment not available, because the minimum order is :' mod='moneytigo'} {$minp3f|escape:'htmlall':'UTF-8'} Euros.</p>
</p>
{/if}
