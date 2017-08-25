<?php

namespace Drupal\customer\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class UpdateCustomerForm extends FormBase {
 public $id;
 /**
  * @ERROR!!!
  */
 public function getFormId() {
  return 'update';
 }
 
 /**
  * @ERROR!!!
  */
 public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
  $this->id = $id;
  $conn = Database::getConnection();
  $record = array();
  if ($this->id) {
   $query = $conn->select('customer','cus')->condition('id',$this->id)->fields('cus');
   $record = $query->execute()->fetchAssoc();
  }
  $form['customer_code'] = array(
    '#type' => 'textfield',
    '#required' => TRUE,
    '#default_value' => $record['customer_code'] 
  );
  
  $form['customer_name'] = array(
    '#type' => 'textfield',
    '#required' => TRUE,
    '#default_value' => $record['customer_name'] 
  );
  
  $form['customer_address'] = array(
    '#type' => 'textfield',
    '#default_value' => $record['customer_address'] 
  );
  
  $form['customer_represent'] = array(
    '#type' => 'textfield',
    '#default_value' => $record['customer_represent'] 
  );
  
  $form['customer_mobiphone'] = array(
    '#type' => 'textfield',
    '#default_value' => $record['customer_mobiphone'] 
  );
  
  $form['submit'] = [
    '#type' => 'submit',
    '#value' => 'save' 
  ];
  
  $form['#theme'] = 'update_customer_form';
  $form['#attached'] = array(
    'library' => array(
      'customer/customer.style' 
    ) 
  );
  
  return $form;
 }
 
 /**
  * @ERROR!!!
  */
 public function validateForm(array &$form, FormStateInterface $form_state) {
 }
 
 /**
  * @ERROR!!!
  */
 public function submitForm(array &$form, FormStateInterface $form_state) {
  $field = $form_state->getValues();
  $customer_code = $field['customer_code'];
  $customer_name = $field['customer_name'];
  $customer_address = $field['customer_address'];
  $customer_represent = $field['customer_represent'];
  $customer_mobiphone = $field['customer_mobiphone'];
  
  if (isset($this->id)) {
   $field = array(
     'customer_code' => $customer_code,
     'customer_name' => $customer_name,
     'customer_address' => $customer_address,
     'customer_represent' => $customer_represent,
     'customer_mobiphone' => $customer_mobiphone 
   );
   
   $query = \Drupal::database();
   $query->update('customer')->fields($field)->condition('id',$this->id)->execute();
   drupal_set_message("Cập Nhật Thành Công");
   $form_state->setRedirect('customer.list-customer');
  }
 }
}

