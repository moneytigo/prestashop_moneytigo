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

if (!defined('_PS_VERSION_')) {
    exit;
}

 

class MoneytigoInstall
{

    /**
        * Set configuration table
    */

    public function updateConfiguration()
    {
        Configuration::updateValue('MONEYTIGO_GATEWAY_P3F', 'off');
		Configuration::updateValue('MONEYTIGO_INTEGRATED', 'off');
        Configuration::updateValue('MONEYTIGO_TRIGGER_P3F', 100);
        Configuration::updateValue('MONEYTIGO_CODE_TEMPLATE', '');
        Configuration::updateValue('MONEYTIGO_URI_API_PAYMENT', 'https://payment.moneytigo.com/');
        Configuration::updateValue('MONEYTIGO_URI_API_GET_TRANSACTION', 'https://payment.moneytigo.com/transactions/');
        Configuration::updateValue('MONEYTIGO_URI_API_INIT_TRANSACTION', 'https://payment.moneytigo.com/init_transactions/');
		Configuration::updateValue('MONEYTIGO_URI_LIBRARY', 'https://payment.moneytigo.com/6598874bb8d7bfdb56df4b5d6f4b56d/js/IPSSDK-Cms.js');
    }

    /**
        * Delete MoneyTigo configuration
    */

    public function deleteConfiguration()
    {
        Configuration::deleteByName('MONEYTIGO_GATEWAY_API_KEY');
        Configuration::deleteByName('MONEYTIGO_GATEWAY_CRYPT_KEY');
        Configuration::deleteByName('MONEYTIGO_GATEWAY_P3F');
        Configuration::deleteByName('MONEYTIGO_INTEGRATED');
        Configuration::deleteByName('MONEYTIGO_TRIGGER_P3F');
        Configuration::deleteByName('MONEYTIGO_CODE_TEMPLATE');
        Configuration::deleteByName('MONEYTIGO_URI_API_PAYMENT');
        Configuration::deleteByName('MONEYTIGO_URI_API_GET_TRANSACTION');
        Configuration::deleteByName('MONEYTIGO_URI_API_INIT_TRANSACTION');
		Configuration::deleteByName('MONEYTIGO_URI_LIBRARY');
    }
	
	/**
	* Create data table for store transaction logs
	*/
	    public function createDatabaseTables()
    {
        try {
            $db = Db::getInstance();
            $db->execute(
                'CREATE TABLE IF NOT EXISTS `'
                ._DB_PREFIX_
                .'moneytigo_transactiondata`
                (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `order_id` varchar(255),
                `transaction_id` varchar(255),
                `datetime` datetime,
                `type_tr` varchar(255),
				`IPS_Return_Responses` text DEFAULT NULL,
                PRIMARY KEY (`id`)
                );'
            );
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
        * Create a new order state
    */

    public function createOrderState()
    {
        $this->pendingD();
        $this->acceptedD();
        $this->pendingP3f();
        $this->acceptedP3f();
    }

    public function pendingD()
    {
        //only if not already exist from another extension IPS

        if (!Configuration::get('MONEYTIGO_OS_PENDING')) {
            $orderState = new OrderState();
            $orderState->name = array();
            foreach (Language::getLanguages() as $language) {
                if (Tools::strtolower($language['iso_code']) == 'fr') {
                    $orderState->name[$language['id_lang']] = 'Paiement carte en attente';
                } else {
                    $orderState->name[$language['id_lang']] = 'Credit card payment pending';
                }
            }

            $orderState->send_email = false;
            $orderState->color = '#ffc702';
            $orderState->hidden = false;
            $orderState->delivery = false;
            $orderState->logable = false;
            $orderState->invoice = false;

            if ($orderState->add()) {
                $source = dirname(__FILE__).'/views/img/pendingstate.gif';
                $destination = dirname(__FILE__).'/../../img/os/'.(int)$orderState->id.'.gif';
                copy($source, $destination);
            }
            Configuration::updateValue('MONEYTIGO_OS_PENDING', (int)$orderState->id);
        }
    }


    public function acceptedD()
    {
        //only if not already exist from another extension IPS

        if (!Configuration::get('MONEYTIGO_OS_ACCEPTED')) {
            $orderState = new OrderState();
            $orderState->name = array();
            foreach (Language::getLanguages() as $language) {
                if (Tools::strtolower($language['iso_code']) == 'fr') {
                    $orderState->name[$language['id_lang']] = 'Paiement carte accepté';
                } else {
                    $orderState->name[$language['id_lang']] = 'Credit card payment approved';
                }
            }

            $orderState->send_email = true;
            $orderState->color = '#96CA2D';
            $orderState->hidden = false;
            $orderState->delivery = false;
            $orderState->logable = true;
            $orderState->invoice = true;
            $orderState->paid = true;

            if ($orderState->add()) {
                $source = dirname(__FILE__).'/views/img/stateapproved.gif';
                $destination = dirname(__FILE__).'/../../img/os/'.(int)$orderState->id.'.gif';
                copy($source, $destination);
            }
            Configuration::updateValue('MONEYTIGO_OS_ACCEPTED', (int)$orderState->id);
        }
    }


    public function pendingP3f()
    {
        //only if not already exist from another extension IPS
        if (!Configuration::get('MONEYTIGO_OS_PENDING_P3F')) {
            $orderState = new OrderState();
            $orderState->name = array();
            foreach (Language::getLanguages() as $language) {
                if (Tools::strtolower($language['iso_code']) == 'fr') {
                    $orderState->name[$language['id_lang']] = 'Paiement carte (3X) en attente';
                } else {
                    $orderState->name[$language['id_lang']] = 'Split payment pending (3X) - Card';
                }
            }

            $orderState->send_email = false;
            $orderState->color = '#ffc702';
            $orderState->hidden = false;
            $orderState->delivery = false;
            $orderState->logable = false;
            $orderState->invoice = false;

            if ($orderState->add()) {
                $source = dirname(__FILE__).'/views/img/pendingstate.gif';
                $destination = dirname(__FILE__).'/../../img/os/'.(int)$orderState->id.'.gif';
                copy($source, $destination);
            }
            Configuration::updateValue('MONEYTIGO_OS_PENDING_P3F', (int)$orderState->id);
        }
    }

    public function acceptedP3f()
    {
        //only if not already exist from another extension IPS

        if (!Configuration::get('MONEYTIGO_OS_ACCEPTED_P3F')) {
            $orderState = new OrderState();
            $orderState->name = array();
            foreach (Language::getLanguages() as $language) {
                if (Tools::strtolower($language['iso_code']) == 'fr') {
                    $orderState->name[$language['id_lang']] = 'Paiement carte (3x) accepté';
                } else {
                    $orderState->name[$language['id_lang']] = 'Split payment approved (3X) - Card';
                }
            }
            $orderState->send_email = true;
            $orderState->color = '#96CA2D';
            $orderState->hidden = false;
            $orderState->delivery = false;
            $orderState->logable = true;
            $orderState->invoice = true;
            $orderState->paid = true;

            if ($orderState->add()) {
                $source = dirname(__FILE__).'/views/img/stateapproved.gif';
                $destination = dirname(__FILE__).'/../../img/os/'.(int)$orderState->id.'.gif';
                copy($source, $destination);
            }
            Configuration::updateValue('MONEYTIGO_OS_ACCEPTED_P3F', (int)$orderState->id);
        }
    }
}
