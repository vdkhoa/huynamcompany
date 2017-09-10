<?php

namespace Drupal\commodity\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RegisterCommodityForm extends FormBase {

  /**
   */
  public function getFormId() {
    return 'register';
  }

  /**
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $conn = Database::getConnection();
    $record = [];
    if (isset($_GET['id'])) {
      $query = $conn->select('commodity', 'com')
        ->condition('id', $_GET['id'])
        ->fields('com');
      $record = $query->execute()->fetchAssoc();
    }

    $form['commodity_code'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#maxlength' => 10,
    ];

    $form['commodity_name'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#maxlength' => 50,
    ];

    $form['commodity_price'] = [
      '#type' => 'textfield',
      '#maxlength' => 6,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'save',
    ];

    $form['#theme'] = 'commodity_register_form';
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

    if (isset($_GET['id'])) {
    }
    else {
      $field = [
        'commodity_code' => $commodity_code,
        'commodity_name' => $commodity_name,
        'commodity_price' => $commodity_price,
      ];

      $query = \Drupal::database();
      $query->insert('commodity')->fields($field)->execute();
      drupal_set_message("Đăng Ký Thành Công");
    }
  }
}

