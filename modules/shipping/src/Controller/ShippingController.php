<?php

namespace Drupal\shipping\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

class ShippingController extends ControllerBase {
  /**
   *
   * @return string
   */
  public function display(){
    $lstShip = $this->lstShipping();

    $build['#theme'] = 'shipping_form';
    $form = \Drupal::formBuilder()->getForm('\Drupal\shipping\Form\ShippingForm');
    $build['#ship_form'] = $form;
    $build['#list_ship'] = $lstShip;
    $build['#attached'] = array(
        'library' => array(
            'shipping/shipping.style'
        )
    );

    return $build;
  }
  public function lstShipping(){
    $query = db_select('shipping','ship');
    $query->join('customer', 'cus', 'cus.customer_code = ship.customer_code');
    $query->fields('ship');
    $query->fields('cus', ['customer_name']);

    $result = $query->execute()->fetchAll();

   return $result;
 }
}