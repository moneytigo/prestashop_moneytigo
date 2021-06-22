/**
	 * NOTICE OF LICENSE
	 *
	 * This file is create by Ips
	 * For the installation of the software in your application
	 * You accept the licence agreement.
	 *
	 * You must not modify, adapt or create derivative works of this source code
	 *
	 *  @author    IPS INTERNATIONNAL SAS
	 *  @copyright 2015-2021 Ips
	 *  @license   moneytigo.com
*/

$(function() {
	MoneyTigoFX =	{
		validateFormMoneyTigo: function()	{
			// Values configuration
			
		 
			var seuil_p3f = parseInt($("#seuil_p3f").val());


			if(seuil_p3f >= 100)	{
				$("#formMoneyTigo").submit();
			} else {
				alert("Threshold 3x / fraction payment (Minimum 100 Euros).");
				$("#seuil_p3f").val("100");
				return false;
			}


		
		},
		showCompteIPS: function()	{
		 
		},
		hideCompteIPS: function()	{
			 
		}
	};
});
