<?php
/**
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
*/

class MoneytigoReturnModuleFrontController extends ModuleFrontController
{
	public function __construct()
  {
    parent::__construct();

  }
	
	
    public function initContent()
    {
        require_once(dirname(__FILE__).'./../../../../config/config.inc.php');
        include_once(dirname(__FILE__).'./../../../../init.php');
        require_once(dirname(__FILE__).'./../../moneytigo.php');
		
		
        // Init
        $moneytigo = new Moneytigo();
 
        if (!$moneytigo->isAvailable()) {
            return;
        }

        $cart = new Cart(Tools::getValue('id_cart'));
        $order = new Order($cart->id);
          
         
        $orderId = $order->getOrderByCartId($cart->id);
        $orderdetails = new Order((int) $orderId);
        
		
        if ($orderId) {
            if ($orderdetails->valid == 1) {
                $customer = new Customer((int)$cart->id_customer);
                $Uri = 'index.php?controller=order-confirmation&id_cart='.$cart->id.'&id_module='.$moneytigo->id.'&id_order='.$orderId.'&key='.$customer->secure_key;
        
                Tools::redirect($Uri); //order paid
            } else {
                Tools::redirect('index.php?controller=order&step=3&ips_failed=1'); //Order not paid
            }
        } else {
			if (_PS_VERSION_ < '1.7') {
            Tools::redirect('index.php?controller=order&step=3&ips_failed=1'); //no order exist
			}
			else
			{
			Tools::redirect('index.php?controller=order&step=1&ips_failed=1'); //no order exist	
			}
        }
    }
}
