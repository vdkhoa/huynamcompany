<?php

namespace Drupal\commodity\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class UpdateCommodityForm extends FormBase {

  public $id;

  /**
   */
  public function getFormId() {
    return 'update';
  }

  /**
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $this->id = $id;
    $conn = Database::getConnection();
    $record = [];
    if ($this->id) {
      $query = $conn->select('commodity', 'com')
        ->condition('id', $this->id)
        ->fields('com');
      $record = $query->execute()->fetchAssoc();
    }

    $form['commodity_code'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#maxlength' => 10,
      '#default_value' => $record['commodity_code'],
    ];

    $form['commodity_name'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#maxlength' => 50,
      '#default_value' => $record['commodity_name'],
    ];

    $form['commodity_price'] = [
      '#type' => 'textfield',
      '#maxlength' => 6,
      '#default_value' => $record['commodity_price'],
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'save',
    ];

    $form['#theme'] = 'commodity_update_form';
    $form['#attached'] = [
      'library' => [
        'commodity/commodity.style',
      ],
    ];

    return $form;
  }

  /**
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $field = $form_state->getValues();
    $commodity_code = $field['commodity_code'];
    $commodity_name = $field['commodity_name'];
    $commodity_price = $field['commodity_price'];

    if (isset($this->id)) {
      $field = [
        'commodity_code' => $commodity_code,
        'commodity_name' => $commodity_name,
        'commodity_price' => $commodity_price,
      ];

      $query = \Drupal::database();
      $query->update('commodity')
        ->fields($field)
        ->condition('id', $this->id)
        ->execute();
      drupal_set_message("Cập Nhật Thành Công");
      $form_state->setRedirect('commodity.index');
    }
  }
}

