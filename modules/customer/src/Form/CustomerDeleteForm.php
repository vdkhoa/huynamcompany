<?php

namespace Drupal\customer\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Url;

class CustomerDeleteForm extends FormBase {
 public $id;

 /**
  * @ERROR!!!
  */
 public function getFormId(){
  return 'delete_form';
 }

 /**
  */
 public function buildForm(array $form, FormStateInterface $form_state, $id = NULL){
  $this->id = $id;
  $query = db_select('customer','cus');
  $query->condition('id',$this->id);
  $query->fields('cus');

  $result = $query->execute()->fetchAssoc();
  if ($result) {
   $form['customer_code'] = array(
     '#type' => 'textfield',
     '#default_value' => $result['customer_code'],
     '#disabled' => TRUE
   );

   $form['customer_name'] = array(
     '#type' => 'textfield',
     '#default_value' => $result['customer_name'],
     '#disabled' => TRUE
   );

   $form['customer_address'] = array(
     '#type' => 'textfield',
     '#default_value' => $result['customer_address'],
     '#disabled' => TRUE
   );

   $form['customer_represent'] = array(
     '#type' => 'textfield',
     '#default_value' => $result['customer_represent'],
     '#disabled' => TRUE
   );

   $form['customer_mobiphone'] = array(
     '#type' => 'textfield',
     '#default_value' => $result['customer_mobiphone'],
     '#disabled' => TRUE
   );
  }

  $form['submit'] = [
    '#type' => 'submit',
    '#value' => 'Delete'
  ];

  $form['cancel'] = [
    '#type' => 'link',
    '#title' => $this->t('Cancel'),
    '#attributes' => array(
      'class' => array(
        'btn btn-primary'
      )
    ),
    '#url' => Url::fromRoute('customer.list')
  ];

  $form['#theme'] = 'customer_delete_form';
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
 public function validateForm(array &$form, FormStateInterface $form_state){
  parent::validateForm($form,$form_state);
 }

 /**
  * @ERROR!!!
  */
 public function submitForm(array &$form, FormStateInterface $form_state){
  $query = \Drupal::database();
  $query->delete('customer')->condition('id',$this->id)->execute();
  drupal_set_message("Xóa Thông Tin Khách Hàng Thành Công");
  $form_state->setRedirect('customer.list');
 }
}

