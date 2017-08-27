<?php

namespace Drupal\customer\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CustomerRegisterForm extends FormBase {
 /**
  * {@inheritdoc}
  */
 public function getFormId() {
  return 'register_form';
 }

 /**
  * {@inheritdoc}
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
    '#maxlength' => 10,
  );

  $form['customer_name'] = array (
    '#type' => 'textfield',
    '#maxlength' => 50
  );

  $form['customer_address'] = array (
    '#type' => 'textarea',
    '#maxlength' => 255
  );

  $form['customer_represent'] = array (
    '#type' => 'textfield',
    '#maxlength' => 50
  );

  $form['customer_mobiphone'] = array (
    '#type' => 'textfield',
    '#maxlength' => 11
  );

  $form['submit'] = [
    '#type' => 'submit',
    '#value' => 'save'
  ];

  $form['#theme'] = 'customer_register_form';
  $form['#attached'] = array (
    'library' => array (
      'customer/customer.style'
    )
  );

  return $form;
 }

 /**
  * {@inheritdoc}
  */
 public function validateForm(array &$form, FormStateInterface $form_state) {
  $customer = $form_state->getValues();
  if ($customer['customer_code'] === '') {
   $form_state->setErrorByName('customer_code', t('Xin hãy nhập mã khách hàng.'));
  } else if ($customer['customer_name'] === '') {
   $form_state->setErrorByName('customer_name', t('Xin hãy nhập tên khách hàng.'));
  }

  parent::validateForm($form, $form_state);
 }

 /**
  * {@inheritdoc}
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
  }
 }
}

