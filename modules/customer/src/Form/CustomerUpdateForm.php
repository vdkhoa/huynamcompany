<?php

namespace Drupal\customer\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class CustomerUpdateForm extends FormBase {

  public $id;
  /**
   * (non-PHPdoc)
   * @see \Drupal\Core\Form\FormInterface::getFormId()
   */
  public function getFormId(){
    return 'update_form';
  }

  /**
   * (non-PHPdoc)
   * @see \Drupal\Core\Form\FormInterface::buildForm()
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL){
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
        '#maxlength' => 10,
        '#disabled' => TRUE
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
   * (non-PHPdoc)
   * @see \Drupal\Core\Form\FormBase::validateForm()
   */
  public function validateForm(array &$form, FormStateInterface $form_state){
    $customer = $form_state->getValues();
    if ($customer['customer_name'] === '') {
      $form_state->setErrorByName('customer_name',t('Xin hãy nhập tên khách hàng.'));
    }

    parent::validateForm($form,$form_state);
  }

  /**
   * (non-PHPdoc)
   * @see \Drupal\Core\Form\FormInterface::submitForm()
   */
  public function submitForm(array &$form, FormStateInterface $form_state){
    try {
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
    } catch(Exception $e) {
      drupal_set_message("Cập Nhật Thất Bại.");
    }
  }

  /**
   *
   * @param unknown $customer_code
   * @return \Drupal\Core\Database\A
   */
  public function getCustomerCode($customer_code) {
    try {
      $query = db_select('customer', 'cus');
      $query->condition('customer_code',$customer_code);
      $query->addField('cus', 'customer_code');
      return $query->execute()->fetchField();
    } catch (Exception $e) {
      drupal_set_message(t("Lỗi hệ thống."));
    }
  }
}

