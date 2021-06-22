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

class MoneytigoPaymentModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $this->display_column_left = false;
        parent::initContent();
        $context = $this->context;
        $cart = $context->cart;
        $customer = new Customer((int)$cart->id_customer);
        $extension = new Moneytigo();
        if (!$extension->isAvailable()) {
            Tools::redirect('index.php?controller=order');
        }
        if ((Tools::getvalue('typeTr') == 1) && (Configuration::get('MONEYTIGO_GATEWAY_P3F')=="on")) {
            $P3F= "P3F";
            $context->cookie->__set("typeTr", $P3F);
        } else {
            $P3F = "D";
            $context->cookie->__set("typeTr", $P3F);
        }

        
        //construct payment request
        
        $moneytigo = array();
        $moneytigo['amount'] = number_format($cart->getOrderTotal(), 2, '.', '');

        if ((Tools::getvalue('typeTr') == 1) && (Configuration::get('MONEYTIGO_GATEWAY_P3F')=="on")) {
            $moneytigo['Lease'] = "3"; //Its payment in three time
            $P3F= "P3F";
            $context->cookie->__set("typeTr", $P3F);
        } else {
            $P3F = "D";
            $context->cookie->__set("typeTr", $P3F);
        }
        $moneytigo['RefOrder'] = (int)$cart->id."-".$P3F;
        $moneytigo['lang'] = Language::getIsoById($this->context->language->id);
        $getsUrlRetour = $this->context->link->getModuleLink(
            'moneytigo',
            'return',
            array('customer'=>$customer->secure_key, 'id_cart'=>(int)$cart->id)
        );
        $moneytigo['urlOK'] = $getsUrlRetour;
        $moneytigo['urlKO'] = $getsUrlRetour;
        $urlIPN = (Configuration::get('PS_SSL_ENABLED') ? 'https' : 'http');
        $urlIPN .= '://'.$_SERVER['HTTP_HOST'].__PS_BASE_URI__.'modules/'.$this->module->name.'/ipn.php';
        $moneytigo['urlIPN'] = $urlIPN;
        $moneytigo['extension'] = "prestashop-".$this->module->name."-".$this->module->version;
        $moneytigo['email_Customer'] = $customer->email;
        $moneytigo['name_Customer'] = $customer->lastname;
        $moneytigo['lastname_Customer'] = $customer->firstname;
        $moneytigo['MerchantKey'] = Configuration::get('MONEYTIGO_GATEWAY_API_KEY'); //Api Key identify Merchant !
        $form_action = Configuration::get('MONEYTIGO_URI_API_PAYMENT');
        // Datas Form
        $arraySmarty = array(
            'isCartEmpty' => ((int)$cart->getOrderTotal() == 0) ? true:false,
            'form_action' => $form_action
        );
        foreach ($moneytigo as $key => $value) {
            $arraySmarty[$key] = $value;
        }
        
      
		
        $this->context->smarty->assign($arraySmarty);
        if (_PS_VERSION_ < '1.7') {
            $this->setTemplate('16/moneytigoold.tpl'); //1.6
        } else {
            $this->setTemplate('module:moneytigo/views/templates/front/17/moneytigo.tpl'); //1.7
        }
    }
}
