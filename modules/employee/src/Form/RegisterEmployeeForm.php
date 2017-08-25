<?php

namespace Drupal\employee\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RegisterEmployeeForm extends FormBase {
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
   $query = $conn->select('employee', 'nv')->condition('id', $_GET['id'])->fields('nv');
   $record = $query->execute()->fetchAssoc();
  }
  
  $form['employee_code'] = array (
    '#type' => 'textfield',
    '#required' => TRUE,
    '#default_value' => (isset($record['employee_code']) && $_GET['id']) ? $record['employee_code'] : '' 
  );
  
  $form['employee_name'] = array (
    '#type' => 'textfield',
    '#required' => TRUE,
    '#default_value' => (isset($record['employee_name']) && $_GET['id']) ? $record['employee_name'] : '' 
  );
  
  $form['employee_address'] = array (
    '#type' => 'textfield',
    '#default_value' => (isset($record['employee_address']) && $_GET['id']) ? $record['employee_address'] : '' 
  );
  
  $form['employee_birthday'] = array (
    '#type' => 'textfield',
    '#default_value' => (isset($record['employee_birthday']) && $_GET['id']) ? $record['employee_birthday'] : '' 
  );
  
  $form['employee_gender'] = array (
    '#type' => 'radios',
    '#required' => TRUE,
    '#default_value' => 'Nam',
    '#options' => array (
      'Nam' => t('Nam'),
      'Nữ' => t('Nữ') 
    ) 
  );
  
  $form['employee_mobiphone'] = array (
    '#type' => 'textfield',
    '#default_value' => (isset($record['employee_mobiphone']) && $_GET['id']) ? $record['employee_mobiphone'] : '' 
  );
  
  $form['submit'] = [ 
    '#type' => 'submit',
    '#value' => 'save' 
  ];
  
  $form['#theme'] = 'register_employee_form';
  $form['#attached'] = array (
    'library' => array (
      'employee/employee.style' 
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
  $employee_code = $field['employee_code'];
  $employee_name = $field['employee_name'];
  $employee_address = $field['employee_address'];
  $employee_birthday = $field['employee_birthday'];
  $employee_gender = $field['employee_gender'];
  $employee_mobiphone = $field['employee_mobiphone'];
  
  if (isset($_GET['id'])) {
  } else {
   $field = array (
     'employee_code' => $employee_code,
     'employee_name' => $employee_name,
     'employee_address' => $employee_address,
     'employee_birthday' => $employee_birthday,
     'employee_gender' => $employee_gender,
     'employee_mobiphone' => $employee_mobiphone 
   );
   
   $query = \Drupal::database();
   $query->insert('employee')->fields($field)->execute();
   drupal_set_message("Đăng Ký Thành Công");
   $response = new RedirectResponse("/employee/list-employee");
   $response->send();
  }
 }
}

