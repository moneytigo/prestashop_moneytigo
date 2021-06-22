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


//this not work on ps 1.6 only on 1.7 need create condition for include this class PaymentOption !
//use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Moneytigo extends PaymentModule
{
	protected $_html = '';
    protected $_postErrors = array();

    public $details;
    public $owner;
    public $address;
    public $extra_mail_vars;
	
    public function __construct()
    {
        $this->name = 'moneytigo';
        $this->tab = 'payments_gateways';
        $this->version = '1.0.1';
        $this->author = 'Ips Internationnal SAS';
        $this->controllers = array('validation');
		$this->is_eu_compatible = 1;
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => _PS_VERSION_);
        $this->currencies = true;
        $this->currencies_mode = 'checkbox';
        $this->bootstrap = true;
        $this->module_key = '7daffdb3008980c4393c94e13e02fd98';
        parent::__construct();
		$this->displayName = $this->l('MoneyTigo');
        $this->description = $this->l('Accept credit card payments in minutes');
		
        $this->displayName = 'MoneyTigo';
        $this->description = 'Accept credit card payments in minutes';
        $this->confirmUninstall = 'Are you sure you want to uninstall?';
		        if (!count(Currency::checkPaymentCurrencies($this->id))) {
            $this->warning = $this->l('No currency has been set for this module.');
        }
		
		
    }
	
    /**
    * Module installation
    */    
    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
        include_once(_PS_MODULE_DIR_.'/'.$this->name.'/moneytigo_install.php');
        $moneytigo_install = new MoneytigoInstall();
        $moneytigo_install->updateConfiguration();
        $moneytigo_install->createOrderState();
        $moneytigo_install->createDatabaseTables();
        if (_PS_VERSION_ < '1.7') {
            return parent::install() &&
                            $this->registerHook('payment') &&
                            $this->registerHook('paymentReturn') &&
                            $this->registerHook('displayHeader') &&
                            $this->registerHook('displayFooter') &&
                            $this->registerHook('displayAdminOrder') &&
				$this->registerHook('displayTop') &&
                            $this->registerHook('displayBackOfficeHeader');
        } else {
            return parent::install() &&
                            $this->registerHook('paymentOptions') &&
                            $this->registerHook('paymentReturn') &&
                            $this->registerHook('displayPayment') &&
                            $this->registerHook('displayHeader') &&
                            $this->registerHook('displayFooter') &&
                            $this->registerHook('displayAdminOrder') &&
				$this->registerHook('displayTop') &&
                            $this->registerHook('displayBackOfficeHeader');
        }
    }
	
    /**
    * Uninstalling the module
    */
    public function uninstall()
    {
        include_once(_PS_MODULE_DIR_.'/'.$this->name.'/moneytigo_install.php');
        $moneytigo_install = new MoneytigoInstall();
        $moneytigo_install->deleteConfiguration();
        
        if (_PS_VERSION_ < '1.7') {
            if (!$this->unregisterHook('payment') ||
                !$this->unregisterHook('paymentReturn') ||
                !$this->unregisterHook('displayHeader') ||
				!$this->unregisterHook('displayFooter') ||
                !$this->unregisterHook('displayBackOfficeHeader')) {
                Logger::addLog('Moneytigo module: unregisterHook failed', 4);
                return false;
            }
        } else {
            if (!$this->unregisterHook('paymentOptions') ||
                !$this->unregisterHook('paymentReturn') ||
				!$this->unregisterHook('displayPayment') ||
                !$this->unregisterHook('displayHeader') ||
				!$this->unregisterHook('displayFooter') ||
				!$this->unregisterHook('displayAdminOrder') ||
                !$this->unregisterHook('displayBackOfficeHeader')) {
                Logger::addLog('Moneytigo module: unregisterHook failed', 4);
                return false;
            }
        }
        if (!parent::uninstall()) {
            Logger::addLog('Ipspayment module: uninstall failed', 4);
            return false;
        }
        return true;
    }

    /**
    * Display of credit card logos in the footer
    */
    public function hookDisplayFooter(array $params)
    {
        if (!$this->isAvailable()) {
            return;
        }

        $this->context->smarty->assign(
            array(
                'path_img' => $this->_path
            )
        );
     
 		
        return $this->display(__FILE__, '/views/templates/front/footer.tpl');
    }
    
###########################################################################################
###########################################################################################
##Function for prestashop version 1.6
###########################################################################################
	
    /**
     * Display of the payment method MoneyTigo (Prestashop 1.6)
     */
    public function hookDisplayPayment($params)
    {
        if (!$this->isAvailable()) {
            return;
        }
$cart = $params["cart"];
$p3factivated = Configuration::get('MONEYTIGO_GATEWAY_P3F');
$minimum3f = Configuration::get('MONEYTIGO_TRIGGER_P3F');

if($p3factivated == "on")
{
if($cart->getordertotal(true) >= $minimum3f)
{
	$Canp3f = "YES";
}
else
{
	$Canp3f = "INS_CART_AMOUNT";
}
}
else
{
	$Canp3f = "NO";
}
		
		if(Configuration::get('MONEYTIGO_INTEGRATED') == on)
		{
			
		 //Display integrated mode if activated
	 
		 
 		  $CustomerIs = new Customer($params["cart"]->id_customer);
 		    $urlIPN = (Configuration::get('PS_SSL_ENABLED') ? 'https' : 'http');
        	$urlIPN .= '://'.$_SERVER['HTTP_HOST'].__PS_BASE_URI__.'modules/'.$this->name.'/ipn.php'; 
		 
	
	 
		
 
			
			
			
        $this->context->smarty->assign(
            array(
				'Library' => Configuration::get('MONEYTIGO_URI_LIBRARY'),
				'MerchantRef' => (int)$cart->id.'-D', //D or P3F
				'TotalToPaid'=> $cart->getordertotal(true),
				'Customer_Name' => $CustomerIs->{'lastname'},
				'Customer_FirstName' => $CustomerIs->{'firstname'},
				'Customer_Email' => $CustomerIs->{'email'},
				'UriReturn' => $this->context->link->getModuleLink('moneytigo','return', array('customer'=>$CustomerIs->secure_key, 'id_cart'=>(int)$cart->id)),
				'LoaderPath' => Media::getMediaPath(_PS_MODULE_DIR_.$this->name.'/views/img/loader.gif'),
				'UriIPN' => $urlIPN,
                'MerchantKey' => Configuration::get('MONEYTIGO_GATEWAY_API_KEY'),
				'MessageAnswer' => Tools::getValue('ips_failed'),
				'p3f' => $Canp3f,
				'minp3f' => $minimum3f,
				'path_img' => $this->_path
           )
       );
            return $this->display(__FILE__, '/views/templates/front/16/paymentinfo16.tpl');
			
			}
		else
			
		{
			
			//Display redirect method if integrated mode is not activated
			 $this->context->smarty->assign(
            array(
                'path_img' => $this->_path,
                'p3f' => $Canp3f,
				'MessageAnswer' => Tools::getValue('ips_failed'),
                'linkPayment' => $this->context->link->getModuleLink('moneytigo', 'payment', array('typeTr'=>0)),
                'linkPayment3f' => $this->context->link->getModuleLink('moneytigo', 'payment', array('typeTr'=>1)),
				'minp3f' => $minimum3f
            )
        );
            return $this->display(__FILE__, '/views/templates/front/16/payment.tpl');
		}
		
    }
    
	
	
	
###########################################################################################
###########################################################################################
##Function for prestashop version 1.7
###########################################################################################	
	
	
    /**
     * Display of the payment method MoneyTigo (Prestashop 1.7)
     */	

	    public function hookPaymentOptions($params)
    {
			
			 
$order_total = (int) $params["cart"]->getordertotal(true);
$seuil = (int) Configuration::get('MONEYTIGO_TRIGGER_P3F');	
			
        if ($order_total >= $seuil) {
            $active = true;
        } else {
            $active = false;
        }
			
			$this->context->smarty->assign(
            array(
                'imageNameD' => $imageNameD,
                'imageNameP3F' => $imageNameP3F,
                'path_img' => $this->_path,
                'p3f' => Configuration::get('MONEYTIGO_GATEWAY_P3F'),
                'p3f_seuil' => $active,
                'linkPayment' => $this->context->link->getModuleLink('moneytigo', 'payment', array('typeTr'=>0)),
                'linkPayment3f' => $this->context->link->getModuleLink('moneytigo', 'payment', array('typeTr'=>1)),
                'blockgrise' => Tools::getValue(
                    'MONEYTIGO_BLOCK_P3F_CONFIG',
                    Configuration::get('MONEYTIGO_BLOCK_P3F_CONFIG')
                ),
                'seuil' => $seuil,
				'AuthorizedFailed' => Tools::getValue('ips_failed')
            )
        );
			
			
			$optionPaymentP3F = $this->optionPaymentP3F();
			
			
			
			
				if(Configuration::get('MONEYTIGO_INTEGRATED') == on)
		{
					
		$payment_options = [
			$this->getEmbeddedPaymentOption($params)
        ];
				
				
		}
			else
				
			{
				 $optionPaymentD = $this->optionPaymentD();
				 $payment_options = array(
            $optionPaymentD
        );
				
			}
			         
			         
			
		
		 if ((Configuration::get('MONEYTIGO_GATEWAY_P3F') == "on") && ($active == true)) {
            array_push($payment_options, $optionPaymentP3F);
        }
		 
			
		return $payment_options;
    }
	 public function getEmbeddedPaymentOption($params)
    {
		   
		   $cart = $params["cart"];
 		   $CustomerIs = new Customer($params["cart"]->id_customer);
 		    $urlIPN = (Configuration::get('PS_SSL_ENABLED') ? 'https' : 'http');
        	$urlIPN .= '://'.$_SERVER['HTTP_HOST'].__PS_BASE_URI__.'modules/'.$this->name.'/ipn.php';
		   $embeddedOption = new PrestaShop\PrestaShop\Core\Payment\PaymentOption;
 		   //Sent value for generate MONEYTIGO Request SDK
		   $this->context->smarty->assign(
            array(
				'Library' => Configuration::get('MONEYTIGO_URI_LIBRARY'),
				'MerchantRef' => (int)$cart->id.'-D', //D or P3F
				'TotalToPaid'=> $cart->getordertotal(true),
				'Customer_Name' => $CustomerIs->{'lastname'},
				'Customer_FirstName' => $CustomerIs->{'firstname'},
				'Customer_Email' => $CustomerIs->{'email'},
				'UriReturn' => $this->context->link->getModuleLink('moneytigo','return', array('customer'=>$CustomerIs->secure_key, 'id_cart'=>(int)$cart->id)),
				'LoaderPath' => Media::getMediaPath(_PS_MODULE_DIR_.$this->name.'/views/img/loader.gif'),
				'UriIPN' => $urlIPN,
                'MerchantKey' => Configuration::get('MONEYTIGO_GATEWAY_API_KEY'),
				'MessageAnswer' => Tools::getValue('ips_failed')
            )
        );

         $embeddedOption->setModuleName('ipsembedded')
			 		->setBinary(true)
                       ->setAdditionalInformation($this->context->smarty->fetch('module:moneytigo/views/templates/front/17/paymentinfo.tpl'))
                       ->setLogo(Media::getMediaPath(_PS_MODULE_DIR_.$this->name.'/views/img/carte.png'));

        return $embeddedOption;
    }

    public function optionPaymentD()
    {
        $optionD = new PrestaShop\PrestaShop\Core\Payment\PaymentOption;
        $optionD ->setCallToActionText($this->l('Pay by card'))
            ->setAction($this->context->link->getModuleLink($this->name, 'payment', array('typeTr'=>0)))
            ->setAdditionalInformation(
                $this->context->smarty->fetch('module:moneytigo/views/templates/front/17/paymentD.tpl')
            );
        return $optionD;
    }
    public function optionPaymentP3F()
    {
        $optionP3F = new PrestaShop\PrestaShop\Core\Payment\PaymentOption;
        $optionP3F->setCallToActionText($this->l('Pay by card in three times'))
            ->setAction($this->context->link->getModuleLink($this->name, 'payment', array('typeTr'=>1)))
            ->setAdditionalInformation(
                $this->context->smarty->fetch('module:moneytigo/views/templates/front/17/paymentP3F.tpl')
            );
        return $optionP3F;
    }


###########################################################################################
###########################################################################################
##Universal function for prestashop 1.6 & 1.7
###########################################################################################	
	
    /**
        * Processing the return page after payment
    */
    public function hookPaymentReturn($params)
    {
        if (!$this->isAvailable()) {
            return;
        }
        // Get informations
        $orderId = Tools::getValue('id_order');
        $order = new Order($orderId);

        if (($order->current_state == Configuration::get('MONEYTIGO_OS_PENDING'))||
            ($order->current_state == Configuration::get('MONEYTIGO_OS_PENDING_P3F'))
        ) {
            $template = 'pending.tpl';
        } elseif (($order->current_state == Configuration::get('MONEYTIGO_OS_ACCEPTED'))||
            ($order->current_state == Configuration::get('MONEYTIGO_OS_ACCEPTED_P3F'))
        ) {
            $template = 'authorised.tpl';
        } else {
            return;
        }
        return $this->display(__FILE__, 'views/templates/front/result/'.$template);
    }

    /**
        * Adding custom CSS
    */
    public function hookDisplayHeader()
    {
		 
		 
         $this->context->controller->addCSS($this->_path.'views/css/moneytigo.css', 'all');
		$this->context->controller->addJS($this->_path.'views/js/moneytigo_embedded.js');
    }
	
    /**
        * Display the methods only if the module is correctly configured!
    */
	    public function isAvailable() //work
    {
        if (!$this->active) {
            return false;
        }
        if ((Configuration::get('MONEYTIGO_GATEWAY_API_KEY') != "") && (Configuration::get('MONEYTIGO_GATEWAY_CRYPT_KEY') != "") && (Configuration::get('MONEYTIGO_GATEWAY_API_KEY') != "PRESTASHOP") && (Configuration::get('MONEYTIGO_GATEWAY_CRYPT_KEY') != "00000000000000000000000000000")
        ) {
            return true;
        }
        Logger::addLog("MoneyTigo : (".date('Y-m-d H:i:s').") Mode not displayed because not active or ApiKey and SecretKey not defined !", 1);

        return false;
    }
	
	
###########################################################################################
###########################################################################################
##Universal function for prestashop 1.6 & 1.7 (ADMIN PRESTASHOP)
###########################################################################################	
	
    /**
        * Adding a custom CSS in the admin for the Moneytigo configuration page
    */
    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('controller') == 'AdminModules') {
            $this->context->controller->addJquery();
            $this->context->controller->addCSS($this->_path.'views/css/moneytigo_back.css', 'all');
            $this->context->controller->addJS($this->_path.'views/js/validateConfiguration.js');
        }
    }
    /**
        * Display of the payment schedule on the order admin panel
    */
    public function hookdisplayAdminOrder($hook)
    {
        if (array_key_exists('id_order', $hook)) {
            $order_id = $hook['id_order'];
        }
        $db = Db::getInstance();
        try {
            $requestSqlTypeTr = 'SELECT `type_tr` FROM `'
                ._DB_PREFIX_
                .'moneytigo_transactiondata` WHERE `order_id`='
                .(int)$order_id;
            $resultTypeTr = $db->getRow($requestSqlTypeTr);
        } catch (Exception $exception) {
            Logger::addLog("MoneyTigo : Error while retrieving the transaction from the database ! For order ID : ".(int)$order_id.$exception, 3);
        }
 
        if ($resultTypeTr['type_tr'] == "P3F") {
            $this->hookdisplayAdminOrderP3f($db, $order_id);
            $file = "orderBlocP3f.tpl";
        } elseif ($resultTypeTr['type_tr'] == "D") {
            $this->hookdisplayAdminOrderD($db, $order_id);
            $file = "orderBlocD.tpl";
        } else {
            return;
        }
        return $this->display(__FILE__, 'views/templates/front/'.$file);
    }
    /**
        * Table generator for installment payment
    */
    public function hookdisplayAdminOrderP3f($db, $order_id)
    {
        try {
            $requestSql = 'SELECT `transaction_id`,`IPS_Return_Responses` FROM `'
                ._DB_PREFIX_
                .'moneytigo_transactiondata` WHERE `order_id`='
                .(int)$order_id;
            $resultIdTransac = $db->executeS($requestSql);
        } catch (Exception $exception) {
            Logger::addLog("MoneyTigo : Error while retrieving the transaction from the database ! For order ID : ".(int)$order_id, 3);
        }

      
        $result = $resultIdTransac;
        if ($result) {
            $transac = array();
            foreach ($result as $key => $value) {
                if ($key == 0) {
                    $transactiondetails = json_decode($value['IPS_Return_Responses']);
                    $transac[$key] = array(
                        "transacId" => $transactiondetails->{'Bank'}->{'Internal_IPS_Id'},
                        "transacDate" => $transactiondetails->{'Created'},
                        "transacMontant" => $transactiondetails->{'Financial'}->{'Total_Paid'}." EUR (€)",
                        "transacOK" => $transactiondetails->{'Transaction_Status'}->{'Description'}
                    );
                }
            }
            $datea = new DateTime($transactiondetails->{'Created'});
            $intervalera = new DateInterval("P1M");
            $datea->add($intervalera);
            $datea = $datea->format('Y-m-d H:i:s');
            $dateb = new DateTime($transactiondetails->{'Created'});
            $intervalerb = new DateInterval("P2M");
            $dateb->add($intervalerb);
            $dateb = $dateb->format('Y-m-d H:i:s');
            $transac["1"] = array(
                "transacId" => $transactiondetails->{'Bank'}->{'Internal_IPS_Id'},
                "transacDate" => $datea,
                "transacMontant" => $transactiondetails->{'Financial'}->{'Total_Paid'}." EUR (€)",
                "transacOK" => "Upcoming transaction"
            );
            $transac["2"] = array(
                "transacId" => $transactiondetails->{'Bank'}->{'Internal_IPS_Id'},
                "transacDate" => $dateb,
                "transacMontant" => $transactiondetails->{'Financial'}->{'Total_Paid'}." EUR (€)",
                "transacOK" => "Upcoming transaction"
            );
            $this->context->smarty->assign('transac', $transac);
        }
    }
    /**
        * Table generator for simple payment
    */
    public function hookdisplayAdminOrderD($db, $order_id)
    {
        try {
            $requestSql = 'SELECT `transaction_id`,`IPS_Return_Responses` FROM `'
                ._DB_PREFIX_
                .'moneytigo_transactiondata` WHERE `order_id`='
                .(int)$order_id;
            $resultIdTransac = $db->getRow($requestSql);
        } catch (Exception $exception) {
            Logger::addLog("MoneyTigo : Error while retrieving the transaction from the database ! For order ID : ".(int)$order_id, 3);
        }
        $result = $resultIdTransac;
        if ($result) {
            $transactiondetails = json_decode($result['IPS_Return_Responses']);
            $transac = array(
            "transacID" => $transactiondetails->{'Bank'}->{'Internal_IPS_Id'},
            "transacDate" => $transactiondetails->{'Created'},
            "transacMontant" => $transactiondetails->{'Financial'}->{'Total_Paid'}." EUR (€)",
            "transacOK" => $transactiondetails->{'Transaction_Status'}->{'Description'},
            );
            $this->context->smarty->assign('transac', $transac);
        }
    }

	    /**
        * Configuration admin page save update or change
    */
    public function getContent()
    {
        if (!isset($this->_html) || empty($this->_html)) {
            $this->_html = '';
        }
        $msg_confirmation = '';
        $msg_confirmation_class = '';
        $display_msg_confirmation = '0';
        $msg_information = '';
        $msg_information_class = '';
        $display_msg_information = '0';
       

        

        if (!empty(Tools::getValue('MONEYTIGO_ADMIN_ACTION'))) {
            Configuration::updateValue('MONEYTIGO_GATEWAY_API_KEY', Tools::getValue('MONEYTIGO_GATEWAY_API_KEY')); //Update ApiKey
            Configuration::updateValue('MONEYTIGO_GATEWAY_CRYPT_KEY', Tools::getValue('MONEYTIGO_GATEWAY_CRYPT_KEY')); //Update CrypKey or Secret Key
            Configuration::updateValue('MONEYTIGO_GATEWAY_P3F', Tools::getValue('MONEYTIGO_GATEWAY_P3F')); //Enable or not split payment 3steps
            Configuration::updateValue('MONEYTIGO_INTEGRATED', Tools::getValue('MONEYTIGO_INTEGRATED')); //Enable or not split payment 3steps

            /* Check Minimal amount for split payment */
            if ((int)Tools::getValue('MONEYTIGO_TRIGGER_P3F') >= 100 && Tools::getValue('MONEYTIGO_GATEWAY_P3F') == "on") {
                Configuration::updateValue('MONEYTIGO_TRIGGER_P3F', Tools::getValue('MONEYTIGO_TRIGGER_P3F'));
                $msg_confirmation_class = ' alert-success';
                $msg_confirmation = "Changing saved (with activation of payment in installments)."; //Update is saved and split payment activated
                $display_msg_confirmation = '1';
            } else {
                if (Tools::getValue('MONEYTIGO_GATEWAY_P3F', Configuration::get('MONEYTIGO_GATEWAY_P3F')) == "on") { //Error cause, split payment asked but bad minimum amount
                    $msg_confirmation = "Failed to save your changes<br>Please note, the minimum amount for activating the payment function in several installments is 100 Euros.";
                    $msg_confirmation_class = ' alert-error';
                    $display_msg_confirmation = '1';
                } else {
                    $msg_confirmation_class = ' alert-success';
                    $msg_confirmation = "Saving your changes successful";
                    $display_msg_confirmation = '1';
                }
            }
        }

        if (!empty(Tools::getValue('MONEYTIGO_ADMIN_ACTION'))) {
            $activeTab_1 = ' active';
            $activeTab_2 = '';
            $activeTabList_1 = 'active';
            $activeTabList_2 = '';
            $apiKeyNumber = Tools::safeOutput(
                Tools::getValue('MONEYTIGO_GATEWAY_API_KEY', Configuration::get('MONEYTIGO_GATEWAY_API_KEY'))
            );
            $cryptKeyNumber = Tools::safeOutput(
                Tools::getValue('MONEYTIGO_GATEWAY_CRYPT_KEY', Configuration::get('MONEYTIGO_GATEWAY_CRYPT_KEY'))
            );
        } else {
            $apiKeyNumber = Tools::getValue(
                'MONEYTIGO_GATEWAY_API_KEY',
                Configuration::get('MONEYTIGO_GATEWAY_API_KEY')
            );
            $cryptKeyNumber = Tools::getValue(
                'MONEYTIGO_GATEWAY_CRYPT_KEY',
                Configuration::get('MONEYTIGO_GATEWAY_CRYPT_KEY')
            );
        }

        $seuil_p3f = Tools::safeOutput(
            Tools::getValue('MONEYTIGO_TRIGGER_P3F', Configuration::get('MONEYTIGO_TRIGGER_P3F'))
        );

 

        if (($apiKeyNumber == false) || ($apiKeyNumber == "")) {
            $apiKeyNumber = 'PRESTASHOP';
        }
        if (($cryptKeyNumber == false) || ($cryptKeyNumber == "")) {
            $cryptKeyNumber = '00000000000000000000000000000';
        }
 
		
		        if (Tools::getValue('MONEYTIGO_INTEGRATED', Configuration::get('MONEYTIGO_INTEGRATED')) == "on") {
            $integrated_on = " checked=\"checked\"";
            $integrated_off = "";
        } else {
            $integrated_on = "";
            $integrated_off = " checked=\"checked\"";
        }
		
		
        if (Tools::getValue('MONEYTIGO_GATEWAY_P3F', Configuration::get('MONEYTIGO_GATEWAY_P3F')) == "on") {
            $p3f_on = " checked=\"checked\"";
            $p3f_off = "";
        } else {
            $p3f_on = "";
            $p3f_off = " checked=\"checked\"";
        }

        if (!empty(Tools::getValue('MONEYTIGO_ADMIN_ACTION'))) {
            $activeTab_1 = 'active';
            $activeTab_2 = '';
            $activeTabList_1 = 'active';
            $activeTabList_2 = '';
        } else {
            $activeTab_1 = 'active';
            $activeTab_2 = '';
            $activeTabList_1 = 'active';
            $activeTabList_2 = '';
        }

        if ($this->context->language->iso_code == 'fr') {
            $imageName = "moneytigo_header_admin.jpg";
            $imageNameBottom = "moneytigo_header_admin_bottom.jpg";
        } else {
            $imageName = "moneytigo_header_admin_en.jpg";
            $imageNameBottom = "moneytigo_header_admin_bottom_en.jpg";
        }

        $this->context->smarty->assign(
            array(
                'image_header' => "../modules/".$this->name."/views/img/" . $imageName,
                'image_header_bottom' => "../modules/".$this->name."/views/img/" . $imageNameBottom,
                'activeTabList_1' => $activeTabList_1,
                'activeTabList_2' => $activeTabList_2,
                'activeTab_1' => $activeTab_1,
                'actionForm' => '',
                'label_api_key' => $this->l('Your API Key (MerchantKey)'),
                'value_api_key' => $apiKeyNumber,
                'label_crypt_key' => $this->l('Your encryption key (SecretKey)'),
                'value_crypt_key' => $cryptKeyNumber,
                'p3f_on' => $p3f_on,
                'p3f_off' => $p3f_off,
				'integrated_on' => $integrated_on,
                'integrated_off' => $integrated_off,
                'seuil_p3f' => $seuil_p3f,
                'activeTab_2' => $activeTab_2,
                'msg_information' => $msg_information,
                'msg_information_class' => $msg_information_class,
                'display_msg_information' => $display_msg_information,
                'msg_confirmation' => $msg_confirmation,
                'msg_confirmation_class' => $msg_confirmation_class,
                'display_msg_confirmation' => $display_msg_confirmation
            )
        );
        return $this->display(__FILE__, '/views/templates/front/admin.tpl');
    } 

 
	
###########################################################################################
###########################################################################################
##Universal function for prestashop 1.6 & 1.7
##Crutial update and query function of the MoneyTigo API
###########################################################################################	
	
	

    /**
        * Changer Id Order State
    */
    public function changeIdOrderState($transactionId, $stateId)
    {
        if ($transactionId == "") {
            return false;
        }
        $orderHistory = new OrderHistory();
        $orderHistory->id_order = $transactionId;
        $orderHistory->changeIdOrderState($stateId, $transactionId);
        $orderHistory->addWithemail();
        $orderHistory->save();
        return true;
    }

    /**
        * Insert transaction in database
    */



    public function insertDataBase($orderId, $transactionId, $type_tr, $ipsanswer = null)
    {
        //Adding transaction information to a separate table
        if (!empty($orderId) && !empty($transactionId) && ($type_tr == "D" || $type_tr == "P3F")) {
            $now = date("Y-m-d H:i:s");
            $db = Db::getInstance();
            $requestSql = 'INSERT INTO `'
                ._DB_PREFIX_
                .'moneytigo_transactiondata`
                (`order_id`, `transaction_id`, `datetime`, `type_tr`, `IPS_Return_Responses`) VALUES("'.
                            (int)$orderId.'", "'.
                            pSQL($transactionId).'", "'.
                            $now.'", "'.
                            pSQL($type_tr).'", "'.
                            pSQL(json_encode($ipsanswer)).'")';
            try {
                $db->execute($requestSql);
            } catch (Exception $exception) {
                Logger::addLog("IPN - MoneyTigo : Failure while adding the transaction to the database !  OrderID : ".$orderId, 3);
            }
        } else {
            return false;
        }
    }
    
    /**
    Requesting to IPS API Server for Checking Transaction
    */
    public function getTransactionInfo($request)
    {
        $UriRequest = "".Configuration::get('MONEYTIGO_URI_API_GET_TRANSACTION')."?";
        foreach ($request as $key => $value) {
            $UriRequest .= $key."=".$value."&";
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $UriRequest);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
		 
        if (curl_errno($ch)) {
            Logger::addLog("IPN - MoneyTigo : ".curl_error($ch)." for", 4);
            $answerIs = json_encode(array(
                "success"=>"false",
                "error"=> "internal",
                "error_description"=> curl_error($ch)));
            header("Status: 500 Internal fatal error with curl request GetTransactionInfo", false, 500);
            exit($answerIs);
        } else {
            return json_decode($result);
        }
        curl_close($ch);
    }
    
    /**
    Signature request encryption
    */
    public function signRequest($key, $secret, $txid)
    {
        $BeforeSign = $key."!".$txid."!".$secret;
        return hash("sha512", base64_encode($BeforeSign."|".$secret));
    }
    /**
    Process IPN Order
    */
    public function confirmOrder($transactiondetails)
    {
        $PsTransactionIdentifier = explode("-", $transactiondetails->{'Merchant_Order_Id'});
         
        $CartingID = $PsTransactionIdentifier[0];
        $TransactionType = $PsTransactionIdentifier[1];
        if ($TransactionType == 'P3F') {
            $ApprovedState = Configuration::get('MONEYTIGO_OS_ACCEPTED_P3F');
        } elseif ($TransactionType == 'D') {
            $ApprovedState = Configuration::get('MONEYTIGO_OS_ACCEPTED');
        }
        $cart = new Cart($CartingID);
        $order = new Order();
        $orderId = $order->getOrderByCartId($cart->id);
        if ($orderId) {
            //This cart has already been transformed into an order, we check if the payment is processed, if necessary we record it.
            if ($transactiondetails->{'Transaction_Status'}->{'State'} == 2) { //Beginning of the procedure only if the transaction is accepted.
                $order = new Order($orderId);
                if ($order->getCurrentState() == $ApprovedState) {
                    //Order already processed and confirmed
                    Logger::addLog("IPN - MoneyTigo : Order ID #".$orderId." - is already processed and confirmed in your prestashop", 1);
                    $answerIs = json_encode(array("success"=>"true","OrderID"=>$orderId)); //success cause already processed and confirmed
                    exit($answerIs); //Immediate stop of the process, action completed and response received.
                } else {
                    //Order confirmation not processed, we start processing.
                    $orderHistory = new OrderHistory();
                    $orderHistory ->id_order = $orderId;
                    $orderHistory ->changeIdOrderState((int)$ApprovedState, $orderId);
                    $orderHistory ->addWithemail();
                    $orderHistory ->save();
                    $this->insertDataBase($orderId, $transactiondetails->{'Merchant_Order_Id'}, $TransactionType, $transactiondetails);
                    if (_PS_VERSION_ > '1.5' && _PS_VERSION_ < '1.5.2') {
                        $order ->current_state = $orderHistory->id_order_state;
                        $order ->update();
                    }
                    Logger::addLog("IPN - MoneyTigo : Order # ".$orderId." to was processed successfully!", 1);
                    $answerIs = json_encode(array("success"=>"true","OrderID"=>$orderId)); //success cause already processed and confirmed
                    exit($answerIs);
                }
            }
        } else {
            if ($cart->id) {
                if ($transactiondetails->{'Transaction_Status'}->{'State'} == 2) {
                    $customer = new Customer((int)$cart->id_customer);
                    $message = "Process processing for the transaction ".$transactiondetails->{'Merchant_Order_Id'};
                    $this->validateOrder(
                        $cart->id,
                        $ApprovedState,
                        (float)$cart->getOrderTotal(true, Cart::BOTH),
                        $this->displayName,
                        $message,
                        array(),
                        (int)$cart->id_currency,
                        false,
                        $customer->secure_key
                    );
                    $order = new Order($this->currentOrder);
                    $this->insertDataBase($order->id, $transactiondetails->{'Merchant_Order_Id'}, $TransactionType, $transactiondetails);
                    $answerIs = json_encode(array("success"=>"true","OrderID"=>$order->id));
                    Logger::addLog("IPN - MoneyTigo : Order # ".$order->id." to was processed successfully!", 1);
                    exit($answerIs);
                }
            } else {
                Logger::addLog("IPN - MoneyTigo : Payment validation error , CART $CartingID not exist!", 4);
                header('HTTP/1.0 403 Forbidden');
                exit();
            }
        }
    }
    /**
        Server/Server MoneyTigo transaction checking
    */
    public function checkingTransaction($transactionBankID)
    {
        $RequestContruct = array(
            "TransID" => $transactionBankID,
            "ApiKey" => Configuration::get('MONEYTIGO_GATEWAY_API_KEY'),
            "SHA" => $this->signRequest(Configuration::get('MONEYTIGO_GATEWAY_API_KEY'), Configuration::get('MONEYTIGO_GATEWAY_CRYPT_KEY'), $transactionBankID)
        );
        $TransactionSinfo = $this->getTransactionInfo($RequestContruct);
		
	
        if ($TransactionSinfo->{'ErrorCode'}) {
            Logger::addLog("IPN - MoneyTigo : ".$TransactionSinfo->{'ErrorCode'}." - ".$TransactionSinfo->{'ErrorDescription'}." for", 4);
            $answerIs = json_encode(
                array(
                "success"=>"false",
                "error"=> $TransactionSinfo->{'ErrorCode'},
                "error_description"=> $TransactionSinfo->{'ErrorDescription'})
            );
            header("Status: 401 Authorization failed or transaction not found", false, 401);
            exit($answerIs);
        } else {
            $this->confirmOrder($TransactionSinfo);
        }
    }
}
