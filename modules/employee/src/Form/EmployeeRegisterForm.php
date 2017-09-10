<?php

namespace Drupal\employee\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\employee\Plugin\Constant;

class EmployeeRegisterForm extends FormBase {

  /**
   *
   */
  public function getFormId() {
    return 'register';
  }

  /**
   *
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['employee_code'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
    ];

    $form['employee_name'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
    ];

    $form['employee_address'] = [
      '#type' => 'textfield',
    ];

    $form['employee_birthday'] = [
      '#type' => 'textfield',
    ];

    $form['employee_gender'] = [
      '#type' => 'radios',
      '#required' => TRUE,
      '#default_value' => 'Nam',
      '#options' => [
        'Nam' => t('Nam'),
        'Nữ' => t('Nữ'),
      ],
    ];

    $form['employee_mobiphone'] = [
      '#type' => 'textfield',
    ];

    $form['employee_regency'] = [
      '#type' => 'select',
      '#options' => Constant::$regency,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'save',
    ];

    $form['#theme'] = 'employee_register_form';
    $form['#attached'] = [
      'library' => [
        'employee/employee.style',
      ],
    ];

    return $form;
  }

  /**
   *
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   *
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $field = $form_state->getValues();
    $employee_code = $field['employee_code'];
    $employee_name = $field['employee_name'];
    $employee_address = $field['employee_address'];
    $employee_birthday = $field['employee_birthday'];
    $employee_gender = $field['employee_gender'];
    $employee_mobiphone = $field['employee_mobiphone'];
    $employee_regency = $field['employee_regency'];

    $field = [
      'employee_code' => $employee_code,
      'employee_name' => $employee_name,
      'employee_address' => $employee_address,
      'employee_birthday' => $employee_birthday,
      'employee_gender' => $employee_gender,
      'employee_mobiphone' => $employee_mobiphone,
      'employee_regency' => $employee_regency,
    ];

    $query = \Drupal::database();
    $query->insert('employee')->fields($field)->execute();
    drupal_set_message("Đăng Ký Thành Công");
  }
}