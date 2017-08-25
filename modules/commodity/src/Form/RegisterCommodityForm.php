<?php

namespace Drupal\commodity\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RegisterCommodityForm extends FormBase {
 /**
  * @ERROR!!!
  */
 public function getFormId(){
  return 'register';
 }
 
 /**
  * @ERROR!!!
  */
 public function buildForm(array $form, FormStateInterface $form_state){
  $conn = Database::getConnection();
  $record = array();
  if (isset($_GET['id'])) {
   $query = $conn->select('commodity','com')->condition('id',$_GET['id'])->fields('com');
   $record = $query->execute()->fetchAssoc();
  }
  
  $form['commodity_code'] = array(
    '#type' => 'textfield',
    '#required' => TRUE 
  );
  
  $form['commodity_name'] = array(
    '#type' => 'textfield',
    '#required' => TRUE 
  );
  
  $form['commodity_price'] = array(
    '#type' => 'textfield' 
  );
  
  $form['submit'] = [
    '#type' => 'submit',
    '#value' => 'save' 
  ];
  
  $form['#theme'] = 'register_commodity_form';
  $form['#attached'] = array(
    'library' => array(
      'commodity/commodity.style' 
    ) 
  );
  
  return $form;
 }
 
 /**
  * @ERROR!!!
  */
 public function validateForm(array &$form, FormStateInterface $form_state){
 }
 
 /**
  * @ERROR!!!
  */
 public function submitForm(array &$form, FormStateInterface $form_state){
  $field = $form_state->getValues();
  $commodity_code = $field['commodity_code'];
  $commodity_name = $field['commodity_name'];
  $commodity_price = $field['commodity_price'];
  
  if (isset($_GET['id'])) {
  } else {
   $field = array(
     'commodity_code' => $commodity_code,
     'commodity_name' => $commodity_name,
     'commodity_price' => $commodity_price 
   );
   
   $query = \Drupal::database();
   $query->insert('commodity')->fields($field)->execute();
   drupal_set_message("Đăng Ký Thành Công");
   $response = new RedirectResponse("/commodity/list-commodity");
   $response->send();
  }
 }
}

