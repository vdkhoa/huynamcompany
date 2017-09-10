<?php

namespace Drupal\employee\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Url;

class EmployeeDeleteForm extends FormBase {

  public $id;

  /**
   * (non-PHPdoc)
   * @see \Drupal\Core\Form\FormInterface::getFormId()
   */
  public function getFormId() {
    return 'delete_form';
  }

  /**
   *
   * (non-PHPdoc)
   * @see \Drupal\Core\Form\FormInterface::buildForm()
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $this->id = $id;
    $query = \Drupal::database()->select('employee', 'emp');
    $query->condition('id', $this->id);
    $query->fields('emp');

    $result = $query->execute()->fetchAssoc();
    if ($result) {
      $form['employee_code'] = [
        '#type' => 'textfield',
        '#default_value' => $result['employee_code'],
        '#disabled' => TRUE,
      ];

      $form['employee_name'] = [
        '#type' => 'textfield',
        '#default_value' => $result['employee_name'],
        '#disabled' => TRUE,
      ];

      $form['employee_address'] = [
        '#type' => 'textfield',
        '#default_value' => $result['employee_address'],
        '#disabled' => TRUE,
      ];

      $form['employee_birthday'] = [
        '#type' => 'textfield',
        '#default_value' => $result['employee_birthday'],
        '#disabled' => TRUE,
      ];

      $form['employee_gender'] = [
        '#type' => 'textfield',
        '#default_value' => $result['employee_gender'],
        '#disabled' => TRUE,
      ];

      $form['employee_mobiphone'] = [
        '#type' => 'textfield',
        '#default_value' => $result['employee_mobiphone'],
        '#disabled' => TRUE,
      ];

      $form['employee_regency'] = [
        '#type' => 'textfield',
        '#default_value' => $result['employee_regency'],
        '#disabled' => TRUE,
      ];
    }

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Delete',
    ];

    $form['cancel'] = [
      '#type' => 'link',
      '#title' => $this->t('Cancel'),
      '#attributes' => [
        'class' => [
          'btn btn-primary',
        ],
      ],
      '#url' => Url::fromRoute('employee.list'),
    ];

    $form['#theme'] = 'employee_delete_form';
    $form['#attached'] = [
      'library' => [
        'employee/employee.style',
      ],
    ];

    return $form;
  }

  /**
   * (non-PHPdoc)
   * @see \Drupal\Core\Form\FormBase::validateForm()
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * (non-PHPdoc)
   * @see \Drupal\Core\Form\FormInterface::submitForm()
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    try {
      $query = \Drupal::database()
        ->delete('employee')
        ->condition('id', $this->id)
        ->execute();
      drupal_set_message("Xóa Thông Tin Nhân Viên Thành Công");
      $form_state->setRedirect('employee.list');
    } catch (\Exception $e) {
      drupal_set_message("Xóa Thông Tin Thất Bại.");
    }
  }
}

