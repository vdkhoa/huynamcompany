<?php
namespace Drupal\customer\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class UpdateCustomerForm extends FormBase
{
  public $id;
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'update';
  }
  
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $this->id = $id;
    $conn = Database::getConnection();
    $record = array();
    if ($this->id) {
      $query = $conn->select('customer', 'cus')
      ->condition('id', $this->id)
      ->fields('cus');
      $record = $query->execute()->fetchAssoc();
    }
    $form['customer_code'] = array(
      '#type' => 'textfield',
      '#title' => t('Mã Khách Hàng:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['customer_code']) && $this->id) ? $record['customer_code']:'',
    );
    
    $form['customer_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Họ Tên Khách Hàng:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['customer_name']) && $this->id) ? $record['customer_name']:'',
    );
    
    $form['customer_address'] = array(
      '#type' => 'textfield',
      '#title' => t('Địa Chỉ Khách Hàng:'),
      '#default_value' => (isset($record['customer_address']) && $this->id) ? $record['customer_address']:'',
    );
    
    $form['customer_birthday'] = array(
      '#type' => 'textfield',
      '#title' => t('Năm Sinh:'),
      '#default_value' => (isset($record['customer_birthday']) && $this->id) ? $record['customer_birthday']:'',
    );
    
    $form['customer_gender'] = array(
      '#type' => 'radios',
      '#title' => t('Giới Tính:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['customer_gender']) && $this->id) ? $record['customer_gender'] : 'Nam',
      '#options' => array('Nam' => t('Nam'), 'Nữ' => t('Nữ')),
    );
    
    $form['customer_mobiphone'] = array(
      '#type' => 'textfield',
      '#title' => t('Số Điện Thoại:'),
      '#default_value' => (isset($record['customer_mobiphone']) && $this->id) ? $record['customer_mobiphone']:'',
    );
    
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'save',
    ];
    
    return $form;
  }
  
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) { 
    
  }
  
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $field = $form_state->getValues();
    $customer_code = $field['customer_code'];
    $customer_name = $field['customer_name'];
    $customer_address = $field['customer_address'];
    $customer_birthday = $field['customer_birthday'];
    $customer_gender = $field['customer_gender'];
    $customer_mobiphone = $field['customer_mobiphone'];
    
    if (isset($this->id)) {
      $field  = array(
        'customer_code'   =>  $customer_code,
        'customer_name' =>  $customer_name,
        'customer_address' =>  $customer_address,
        'customer_birthday' => $customer_birthday,
        'customer_gender' => $customer_gender,
        'customer_mobiphone' => $customer_mobiphone,
      );
      
      $query = \Drupal::database();
      $query->update('customer')
      ->fields($field)
      ->condition('id', $this->id)
      ->execute();
      drupal_set_message("Cập Nhật Thành Công");
      $form_state->setRedirect('customer.list-customer');
    }
  }
}

