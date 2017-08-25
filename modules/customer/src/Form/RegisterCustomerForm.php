<?php

namespace Drupal\customer\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RegisterCustomerForm extends FormBase {
 /**
  * @ERROR!!!
  */
 public function getFormId() {
  return 'register';
 }
 
 /**
  * @ERROR!!!
  */
 public function buildForm(array $form, FormStateInterface $form_state) {
  $conn = Database::getConnection();
  $record = array ();
  if (isset($_GET['id'])) {
   $query = $conn->select('customer','cus')->condition('id',$_GET['id'])->fields('cus');
   $record = $query->execute()->fetchAssoc();
  }
  
  $form['customer_code'] = array (
    '#type' => 'textfield',
    '#required' => TRUE 
  );
  
  $form['customer_name'] = array (
    '#type' => 'textfield',
    '#required' => TRUE 
  );
  
  $form['customer_address'] = array (
    '#type' => 'textfield' 
  );
  
  $form['customer_represent'] = array (
    '#type' => 'textfield' 
  );
  
  $form['customer_mobiphone'] = array (
    '#type' => 'textfield' 
  );
  
  $form['submit'] = [ 
    '#type' => 'submit',
    '#value' => 'save' 
  ];
  
  $form['#theme'] = 'register_customer_form';
  $form['#attached'] = array (
    'library' => array (
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
  $customer_birthday = $field['customer_represent'];
  $customer_mobiphone = $field['customer_mobiphone'];
  if (isset($_GET['id'])) {
  } else {
   $field = array (
     'customer_code' => $customer_code,
     'customer_name' => $customer_name,
     'customer_address' => $customer_address,
     'customer_represent' => $customer_birthday,
     'customer_mobiphone' => $customer_mobiphone 
   );
   $query = \Drupal::database();
   $query->insert('customer')->fields($field)->execute();
   drupal_set_message("Đăng Ký Thành Công");
   $response = new RedirectResponse("/customer/list-customer");
   $response->send();
  }
 }
}

