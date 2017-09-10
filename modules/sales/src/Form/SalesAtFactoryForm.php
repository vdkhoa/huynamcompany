<?php

namespace Drupal\sales\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SalesAtFactoryForm extends FormBase {

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
    $date_format = 'd-m-Y';
    // get list commodity
    $lstCommodity = $this->lstCommodity();
    $lstCustomer = $this->lstCustomer();
    $max_sales_id = $this->getMaxSalesID();

    $form['sales_id'] = [
      '#type' => 'hidden',
      '#required' => TRUE,
      '#default_value' => $max_sales_id + 1,
    ];

    $form['sales_date'] = [
      '#type' => 'datetime',
      '#date_date_format' => $date_format,
      '#required' => TRUE,
      '#default_value' => DrupalDateTime::createFromTimestamp(time()),
    ];

    $form['customer_code'] = [
      '#type' => 'select',
      '#required' => TRUE,
      '#options' => $lstCustomer,
    ];

    $form['commodity_code_1'] = [
      '#type' => 'select',
      '#title' => t('Tên Hàng Hóa:'),
      '#required' => TRUE,
      '#options' => $lstCommodity,
    ];

    $form['sales_quantity_1'] = [
      '#type' => 'textfield',
      '#title' => t('Số Lượng:'),
      '#required' => TRUE,
    ];

    $form['sales_price_1'] = [
      '#type' => 'textfield',
      '#title' => t('Đơn Giá:'),
      '#required' => TRUE,
    ];

    $form['commodity_code_2'] = [
      '#type' => 'select',
      '#options' => $lstCommodity,
    ];

    $form['sales_quantity_2'] = [
      '#type' => 'textfield',
    ];

    $form['sales_price_2'] = [
      '#type' => 'textfield',
    ];

    $form['commodity_code_3'] = [
      '#type' => 'select',
      '#options' => $lstCommodity,
    ];

    $form['sales_quantity_3'] = [
      '#type' => 'textfield',
    ];

    $form['sales_price_3'] = [
      '#type' => 'textfield',
    ];

    $form['commodity_code_4'] = [
      '#type' => 'select',
      '#options' => $lstCommodity,
    ];

    $form['sales_quantity_4'] = [
      '#type' => 'textfield',
    ];

    $form['sales_price_4'] = [
      '#type' => 'textfield',
    ];

    $form['commodity_code_5'] = [
      '#type' => 'select',
      '#options' => $lstCommodity,
    ];

    $form['sales_quantity_5'] = [
      '#type' => 'textfield',
    ];

    $form['sales_price_5'] = [
      '#type' => 'textfield',
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'save',
    ];

    $form['#theme'] = 'sales_at_factory_form';
    $form['#attached'] = [
      'library' => [
        'sales/sales.style',
      ],
    ];

    return $form;
  }

  /**
   * @return array
   */
  public function lstCommodity() {
    $query = \Drupal::database()->select('commodity', 'com');
    $query->fields('com', [
      'commodity_code',
      'commodity_name',
    ]);
    $results = $query->execute()->fetchAll();
    $options = [];
    foreach ($results as $item) {
      $options[$item->commodity_code] = t($item->commodity_name);
    }
    return $options;
  }

  /**
   * @return array
   */
  public function lstCustomer() {
    $query = \Drupal::database()->select('customer', 'cus');
    $query->fields('cus', [
      'customer_code',
      'customer_name',
    ]);
    $results = $query->execute()->fetchAll();
    $options = [];
    foreach ($results as $item) {
      $options[$item->customer_code] = t($item->customer_name);
    }
    return $options;
  }

  /**
   * @return int
   */
  public function getMaxSalesID() {
    $query = \Drupal::database()->select('sales');
    $query->addExpression('MAX(sales_id)');
    $max_sales_id = $query->execute()->fetchField();
    return $max_sales_id === NULL ? 1 : intval($max_sales_id);
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
    try {
      $values = [];
      $fields = $form_state->getValues();
      $sales_id = $fields['sales_id'];
      $sales_date = $fields['sales_date']->format('Y-m-d G:i:s');
      $customer_code = $fields['customer_code'];
      $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
      $create_date = date('Y-m-d G:i:s');

      for ($idx = 1; $idx <= 5; $idx++) {
        if ((!empty($fields["sales_quantity_$idx"])) && (!empty($fields["sales_price_$idx"]))) {
          $values[] = [
            'sales_id' => $sales_id,
            'sales_date' => $sales_date,
            'commodity_code' => $fields["commodity_code_$idx"],
            'sales_quantity' => $fields["sales_quantity_$idx"],
            'sales_price' => $fields["sales_price_$idx"],
            'customer_code' => $customer_code,
            'user' => $user->get('name')->value,
            'create_date' => $create_date,
          ];
        }
      }

      $query = \Drupal::database()->insert('sales')->fields([
        'sales_id',
        'sales_date',
        'commodity_code',
        'sales_quantity',
        'sales_price',
        'customer_code',
        'user',
        'create_date',
      ]);
      foreach ($values as $record) {
        $query->values($record);
      }
      $query->execute();
      drupal_set_message("Đã lưu hóa đơn bán hàng.");
    } catch (\Exception $e) {
      drupal_set_message("Lưu hóa đơn bán hàng thất bại.");
    }
  }
}
