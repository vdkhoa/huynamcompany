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
 public function display() {
  $form = \Drupal::formBuilder()->getForm('\Drupal\shipping\Form\ShippingForm');
  $build['form'] = $form;

  return $build;
 }

 public function lstShipping() {
   $query = db_select('shipping', 'ship');
   $query->fields('ship');

   $result = $query->execute()->fetchAll();

   return $result;
 }
}