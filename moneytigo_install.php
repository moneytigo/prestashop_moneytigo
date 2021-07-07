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

if ( !defined( '_PS_VERSION_' ) ) {
  exit;
}

class MoneytigoInstall {

  /**
   * Delete MoneyTigo configuration
   */
  public function deleteConfiguration( $modulename ) {
    $query = new DbQuery();
    $query->select( 'name' );
    $query->from( 'configuration' );
    $query->where( 'name LIKE \'' . pSQL( Tools::strtoupper( $modulename ) ) . '_%\'' );
    $results = Db::getInstance()->executeS( $query );
    if ( empty( $results ) ) {
      return true;
    }
    $configurationKeys = array_column( $results, 'name' );
    $result = true;
    foreach ( $configurationKeys as $configurationKey ) {
      $result &= Configuration::deleteByName( $configurationKey );
    }
    return $result;
  }

  /** delete all order state **/
  public function checkOrderStates( $modulename, $terms = NULL, $iso = NULL ) {
    /* @var $orderState OrderState */
    $collection = new PrestaShopCollection( 'OrderState' );
    $collection->where( 'module_name', '=', $modulename );
    $orderStates = $collection->getResults();
    if ( $orderStates == false ) {
      return false;
    }
    foreach ( $orderStates as $orderState ) {
      if ( $orderState->name[ $iso ] == $terms ) {
        $idStates = $orderState->id;
        return $idStates;
      }
    }
  }

  /**
   * Create data table for store transaction logs
   */
  public function createDatabaseTables() {
    try {
      $db = Db::getInstance();
      $db->execute(
        'CREATE TABLE IF NOT EXISTS `'
        . _DB_PREFIX_
        . 'moneytigo_transactiondata`
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
    } catch ( Exception $exception ) {
      return false;
    }
  }

  /**
   * Create a new order state
   */

  public function createOrderState( $modulename ) {
    $this->acceptedD( $modulename );
    $this->acceptedP2f( $modulename );
    $this->acceptedP3f( $modulename );
    $this->acceptedP4f( $modulename );
  }

  public function acceptedD( $modulename ) {
    // Create status only if it does not exist
    // If it already exists then it is assigned to the MoneyTigo configuration
    foreach ( Language::getLanguages() as $language ) {
      if ( Tools::strtolower( $language[ 'iso_code' ] ) == 'fr' ) {
        $nameToCheck = 'Paiement carte accepté';
        $iso = $language[ 'id_lang' ];
      } else {
        $nameToCheck = 'Credit card payment approved';
        $iso = $language[ 'id_lang' ];
      }
    }
    $checkingStatus = $this->checkOrderStates( $modulename, $nameToCheck, $iso );

    if ( !Configuration::get( 'MONEYTIGO_OS_ACCEPTED' ) ) {
      if ( $checkingStatus == false ) {
        $orderState = new OrderState();
        $orderState->name = array();
        foreach ( Language::getLanguages() as $language ) {
          if ( Tools::strtolower( $language[ 'iso_code' ] ) == 'fr' ) {
            $orderState->name[ $language[ 'id_lang' ] ] = 'Paiement carte accepté';
          } else {
            $orderState->name[ $language[ 'id_lang' ] ] = 'Credit card payment approved';
          }
        }
        $orderState->send_email = true;
        $orderState->color = '#96CA2D';
        $orderState->unremovable = true;
        $orderState->hidden = false;
        $orderState->delivery = false;
        $orderState->logable = true;
        $orderState->invoice = true;
        $orderState->paid = true;
        $orderState->module_name = $modulename;
        $orderState->template[ $language[ 'id_lang' ] ] = 'payment';
        if ( $orderState->add() ) {
          $source = dirname( __FILE__ ) . '/views/img/stateapproved.gif';
          $destination = dirname( __FILE__ ) . '/../../img/os/' . ( int )$orderState->id . '.gif';
          copy( $source, $destination );
        }
        Configuration::updateValue( 'MONEYTIGO_OS_ACCEPTED', ( int )$orderState->id );
      } else {
        Configuration::updateValue( 'MONEYTIGO_OS_ACCEPTED', ( int )$checkingStatus );
      }
    }
  }
  public function acceptedP2f( $modulename ) {
    // Create status only if it does not exist
    // If it already exists then it is assigned to the MoneyTigo configuration
    foreach ( Language::getLanguages() as $language ) {
      if ( Tools::strtolower( $language[ 'iso_code' ] ) == 'fr' ) {
        $nameToCheck = 'Paiement carte 2x accepté';
        $iso = $language[ 'id_lang' ];
      } else {
        $nameToCheck = 'Card payment 2x approved';
        $iso = $language[ 'id_lang' ];
      }
    }
    $checkingStatus = $this->checkOrderStates( $modulename, $nameToCheck, $iso );

    if ( !Configuration::get( 'MONEYTIGO_OS_ACCEPTED_P2F' ) ) {
      if ( $checkingStatus == false ) {
        $orderState = new OrderState();
        $orderState->name = array();
        foreach ( Language::getLanguages() as $language ) {
          if ( Tools::strtolower( $language[ 'iso_code' ] ) == 'fr' ) {
            $orderState->name[ $language[ 'id_lang' ] ] = 'Paiement carte 2x accepté';
          } else {
            $orderState->name[ $language[ 'id_lang' ] ] = 'Card payment 2x approved';
          }
        }
        $orderState->send_email = true;
        $orderState->color = '#96CA2D';
        $orderState->unremovable = true;
        $orderState->hidden = false;
        $orderState->delivery = false;
        $orderState->logable = true;
        $orderState->invoice = true;
        $orderState->paid = true;
        $orderState->module_name = $modulename;
        $orderState->template[ $language[ 'id_lang' ] ] = 'payment';
        if ( $orderState->add() ) {
          $source = dirname( __FILE__ ) . '/views/img/stateapproved.gif';
          $destination = dirname( __FILE__ ) . '/../../img/os/' . ( int )$orderState->id . '.gif';
          copy( $source, $destination );
        }
        Configuration::updateValue( 'MONEYTIGO_OS_ACCEPTED_P2F', ( int )$orderState->id );
      } else {
        Configuration::updateValue( 'MONEYTIGO_OS_ACCEPTED_P2F', ( int )$checkingStatus );
      }
    }
  }
  public function acceptedP3f( $modulename ) {
    // Create status only if it does not exist
    // If it already exists then it is assigned to the MoneyTigo configuration
    foreach ( Language::getLanguages() as $language ) {
      if ( Tools::strtolower( $language[ 'iso_code' ] ) == 'fr' ) {
        $nameToCheck = 'Paiement carte 3x accepté';
        $iso = $language[ 'id_lang' ];
      } else {
        $nameToCheck = 'Card payment 3x approved';
        $iso = $language[ 'id_lang' ];
      }
    }
    $checkingStatus = $this->checkOrderStates( $modulename, $nameToCheck, $iso );

    if ( !Configuration::get( 'MONEYTIGO_OS_ACCEPTED_P3F' ) ) {
      if ( $checkingStatus == false ) {
        $orderState = new OrderState();
        $orderState->name = array();
        foreach ( Language::getLanguages() as $language ) {
          if ( Tools::strtolower( $language[ 'iso_code' ] ) == 'fr' ) {
            $orderState->name[ $language[ 'id_lang' ] ] = 'Paiement carte 3x accepté';
          } else {
            $orderState->name[ $language[ 'id_lang' ] ] = 'Card payment 3x approved';
          }
        }
        $orderState->send_email = true;
        $orderState->color = '#96CA2D';
        $orderState->unremovable = true;
        $orderState->hidden = false;
        $orderState->delivery = false;
        $orderState->logable = true;
        $orderState->invoice = true;
        $orderState->paid = true;
        $orderState->module_name = $modulename;
        $orderState->template[ $language[ 'id_lang' ] ] = 'payment';
        if ( $orderState->add() ) {
          $source = dirname( __FILE__ ) . '/views/img/stateapproved.gif';
          $destination = dirname( __FILE__ ) . '/../../img/os/' . ( int )$orderState->id . '.gif';
          copy( $source, $destination );
        }
        Configuration::updateValue( 'MONEYTIGO_OS_ACCEPTED_P3F', ( int )$orderState->id );
      } else {
        Configuration::updateValue( 'MONEYTIGO_OS_ACCEPTED_P3F', ( int )$checkingStatus );
      }
    }
  }
  public function acceptedP4f( $modulename ) {
    // Create status only if it does not exist
    // If it already exists then it is assigned to the MoneyTigo configuration
    foreach ( Language::getLanguages() as $language ) {
      if ( Tools::strtolower( $language[ 'iso_code' ] ) == 'fr' ) {
        $nameToCheck = 'Paiement carte 4x accepté';
        $iso = $language[ 'id_lang' ];
      } else {
        $nameToCheck = 'Card payment 4x approved';
        $iso = $language[ 'id_lang' ];
      }
    }
    $checkingStatus = $this->checkOrderStates( $modulename, $nameToCheck, $iso );

    if ( !Configuration::get( 'MONEYTIGO_OS_ACCEPTED_P4F' ) ) {
      if ( $checkingStatus == false ) {
        $orderState = new OrderState();
        $orderState->name = array();
        foreach ( Language::getLanguages() as $language ) {
          if ( Tools::strtolower( $language[ 'iso_code' ] ) == 'fr' ) {
            $orderState->name[ $language[ 'id_lang' ] ] = 'Paiement carte 4x accepté';
          } else {
            $orderState->name[ $language[ 'id_lang' ] ] = 'Card payment 4x approved';
          }
        }
        $orderState->send_email = true;
        $orderState->color = '#96CA2D';
        $orderState->unremovable = true;
        $orderState->hidden = false;
        $orderState->delivery = false;
        $orderState->logable = true;
        $orderState->invoice = true;
        $orderState->paid = true;
        $orderState->module_name = $modulename;
        $orderState->template[ $language[ 'id_lang' ] ] = 'payment';
        if ( $orderState->add() ) {
          $source = dirname( __FILE__ ) . '/views/img/stateapproved.gif';
          $destination = dirname( __FILE__ ) . '/../../img/os/' . ( int )$orderState->id . '.gif';
          copy( $source, $destination );
        }
        Configuration::updateValue( 'MONEYTIGO_OS_ACCEPTED_P4F', ( int )$orderState->id );
      } else {
        Configuration::updateValue( 'MONEYTIGO_OS_ACCEPTED_P4F', ( int )$checkingStatus );
      }
    }
  }

}