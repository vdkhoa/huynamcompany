<?php

namespace Drupal\customer\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class CustomerUpdateForm extends FormBase {
 public $id;
 /**
  * @ERROR!!!
  */
 public function getFormId() {
  return 'update_form';
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
    '#default_value' => $record['customer_code'],
    '#maxlength' => 10
  );

  $form['customer_name'] = array(
    '#type' => 'textfield',
    '#default_value' => $record['customer_name'],
    '#maxlength' => 50
  );

  $form['customer_address'] = array(
    '#type' => 'textarea',
    '#default_value' => $record['customer_address'],
    '#maxlength' => 255
  );

  $form['customer_represent'] = array(
    '#type' => 'textfield',
    '#default_value' => $record['customer_represent'],
    '#maxlength' => 50
  );

  $form['customer_mobiphone'] = array(
    '#type' => 'textfield',
    '#default_value' => $record['customer_mobiphone'],
    '#maxlength' => 11
  );

  $form['submit'] = [
    '#type' => 'submit',
    '#value' => 'save'
  ];

  $form['#theme'] = 'customer_update_form';
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
  $customer = $form_state->getValues();
  if ($customer['customer_code'] === '') {
   $form_state->setErrorByName('customer_code', t('Xin hãy nhập mã khách hàng.'));
  } else if ($customer['customer_name'] === '') {
   $form_state->setErrorByName('customer_name', t('Xin hãy nhập tên khách hàng.'));
  }
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
   $form_state->setRedirect('customer.list');
  }
 }
}

