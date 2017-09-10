<?php

namespace Drupal\commodity\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Url;

class DeleteCommodityForm extends FormBase {

  public $id;

  /**
   */
  public function getFormId() {
    return 'delete';
  }

  /**
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $this->id = $id;
    if ($this->id) {
      $query = \Drupal::database()->select('commodity', 'com');
      $query->condition('id', $this->id);
      $query->fields('com');

      $result = $query->execute()->fetchAssoc();
    }
    $form['commodity_code'] = [
      '#type' => 'textfield',
      '#default_value' => $result['commodity_code'],
      '#disabled' => TRUE,
    ];

    $form['commodity_name'] = [
      '#type' => 'textfield',
      '#default_value' => $result['commodity_name'],
      '#disabled' => TRUE,
    ];

    $form['commodity_price'] = [
      '#type' => 'textfield',
      '#default_value' => $result['commodity_price'],
      '#disabled' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Delete',
    ];

    $form['cancel'] = [
      '#title' => $this->t('Cancel'),
      '#type' => 'link',
      '#attributes' => [
        'class' => [
          'btn btn-primary',
        ],
      ],
      '#url' => Url::fromRoute('commodity.index'),
    ];

    $form['#theme'] = 'commodity_delete_form';
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
    parent::validateForm($form, $form_state);
  }

  /**
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $query = \Drupal::database();
    $query->delete('commodity')->condition('id', $this->id)->execute();
    drupal_set_message("Xóa Thông Tin Hàng Hóa Thành Công");
    $form_state->setRedirect('commodity.index');
  }
}

