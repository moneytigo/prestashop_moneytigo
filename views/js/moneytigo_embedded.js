 
 

$('.ps-shown-by-js').click(function(){

var ClickedModuleName = $(this).attr('data-module-name');
if(ClickedModuleName == "ipsembedded") {
$("#conditions-to-approve").hide();
$("#IPSERROR").hide();
}
else
{
$("#conditions-to-approve").show();
}

 
});

 
if(ips_failed == 1) {
$('#checkout-payment-step').prepend('<div id="IPSERROR" class="alert alert-danger">'+ips_message+'</div>');
 }

 

 
 