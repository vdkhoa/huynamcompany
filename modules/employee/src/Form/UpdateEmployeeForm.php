<?php
namespace Drupal\employee\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class UpdateEmployeeForm extends FormBase
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
      $query = $conn->select('employee', 'nv')
      ->condition('id', $this->id)
      ->fields('nv');
      $record = $query->execute()->fetchAssoc();
    }
    $form['employee_code'] = array(
      '#type' => 'textfield',
      '#title' => t('Mã Nhân Viên:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['employee_code']) && $this->id) ? $record['employee_code']:'',
    );
    
    $form['employee_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Họ Tên Nhân Viên:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['employee_name']) && $this->id) ? $record['employee_name']:'',
    );
    
    $form['employee_address'] = array(
      '#type' => 'textfield',
      '#title' => t('Địa Chỉ Nhân Viên:'),
      '#default_value' => (isset($record['employee_address']) && $this->id) ? $record['employee_address']:'',
    );
    
    $form['employee_birthday'] = array(
      '#type' => 'textfield',
      '#title' => t('Năm Sinh:'),
      '#default_value' => (isset($record['employee_birthday']) && $this->id) ? $record['employee_birthday']:'',
    );
    
    $form['employee_gender'] = array(
      '#type' => 'radios',
      '#title' => t('Giới Tính:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['employee_gender']) && $this->id) ? $record['employee_gender'] : 'Nam',
      '#options' => array('Nam' => t('Nam'), 'Nữ' => t('Nữ')),
    );
    
    $form['employee_mobiphone'] = array(
      '#type' => 'textfield',
      '#title' => t('Số Điện Thoại:'),
      '#default_value' => (isset($record['employee_mobiphone']) && $this->id) ? $record['employee_mobiphone']:'',
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
    $employee_code = $field['employee_code'];
    $employee_name = $field['employee_name'];
    $employee_address = $field['employee_address'];
    $employee_birthday = $field['employee_birthday'];
    $employee_gender = $field['employee_gender'];
    $employee_mobiphone = $field['employee_mobiphone'];
    
    if (isset($this->id)) {
      $field  = array(
        'employee_code'   =>  $employee_code,
        'employee_name' =>  $employee_name,
        'employee_address' =>  $employee_address,
        'employee_birthday' => $employee_birthday,
        'employee_gender' => $employee_gender,
        'employee_mobiphone' => $employee_mobiphone,
      );
      
      $query = \Drupal::database();
      $query->update('employee')
      ->fields($field)
      ->condition('id', $this->id)
      ->execute();
      drupal_set_message("Cập Nhật Thành Công");
      $form_state->setRedirect('employee.list-employee');
    }
  }
}

