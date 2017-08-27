<?php

namespace Drupal\sales\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RegisterSalesForm extends FormBase {
 /**
  * @ERROR!!!
  */
 public function getFormId() {
  return 'register';
 }

 /**
  * @ERROR!!!
  */
 public function buildForm(array $form, FormStateInterface $form_state) {
  $date_format = 'd-m-Y';
  // get list commodity
  $lstCommodity = $this->lstCommodity();
  $lstCustomer = $this->lstCustomer();
  $max_sales_id = $this->getMaxSalesID();

  $form['sales_id'] = array (
    '#type' => 'textfield',
    '#required' => TRUE,
    '#default_value' => $max_sales_id + 1
  );

  $form['sales_date'] = array (
    '#type' => 'datetime',
    '#date_date_format' => $date_format,
    '#required' => TRUE,
    '#default_value' => DrupalDateTime::createFromTimestamp(time())
  );

  $form['customer_code'] = array (
    '#type' => 'select',
    '#required' => TRUE,
    '#options' => $lstCustomer,
  );

  $form['commodity_code_1'] = array (
    '#type' => 'select',
    '#title' => t('Tên Hàng Hóa:'),
    '#required' => TRUE,
    '#options' => $lstCommodity,
  );

  $form['sales_quantity_1'] = array (
    '#type' => 'textfield',
    '#title' => t('Số Lượng:'),
    '#required' => TRUE,
  );

  $form['sales_price_1'] = array (
    '#type' => 'textfield',
    '#title' => t('Đơn Giá:'),
    '#required' => TRUE,
  );

  $form['commodity_code_2'] = array (
    '#type' => 'select',
    '#options' => $lstCommodity,
  );

  $form['sales_quantity_2'] = array (
    '#type' => 'textfield'
  );

  $form['sales_price_2'] = array (
    '#type' => 'textfield'
  );

  $form['commodity_code_3'] = array (
    '#type' => 'select',
    '#options' => $lstCommodity,
  );

  $form['sales_quantity_3'] = array (
    '#type' => 'textfield'
  );

  $form['sales_price_3'] = array (
    '#type' => 'textfield'
  );

  $form['commodity_code_4'] = array (
    '#type' => 'select',
    '#options' => $lstCommodity,
  );

  $form['sales_quantity_4'] = array (
    '#type' => 'textfield'
  );

  $form['sales_price_4'] = array (
    '#type' => 'textfield'
  );

  $form['commodity_code_5'] = array (
    '#type' => 'select',
    '#options' => $lstCommodity,
  );

  $form['sales_quantity_5'] = array (
    '#type' => 'textfield'
  );

  $form['sales_price_5'] = array (
    '#type' => 'textfield'
  );

  $form['submit'] = [
    '#type' => 'submit',
    '#value' => 'save'
  ];

  $form['#theme'] = 'register_sales_form';
  $form['#attached'] = array (
    'library' => array (
      'sales/sales.style'
    )
  );

  return $form;
 }
 public function lstCommodity() {
  $query = \Drupal::database()->select('commodity', 'com');
  $query->fields('com', [
    'commodity_code',
    'commodity_name'
  ]);
  $results = $query->execute()->fetchAll();
  $options = array ();
  foreach ( $results as $item ) {
   $options[$item->commodity_code] = t($item->commodity_name);
  }
  return $options;
 }
 public function lstCustomer() {
  $query = \Drupal::database()->select('customer', 'cus');
  $query->fields('cus', [
    'customer_code',
    'customer_name'
  ]);
  $results = $query->execute()->fetchAll();
  $options = array ();
  foreach ( $results as $item ) {
   $options[$item->customer_code] = t($item->customer_name);
  }
  return $options;
 }
 public function getMaxSalesID() {
  $query = db_select('sales');
  $query->addExpression('MAX(sales_id)');
  $max_sales_id = $query->execute()->fetchField();
  return $max_sales_id === NULL ? 1 : intval($max_sales_id);
 }

 /**
  * @ERROR!!!
  */
 public function validateForm(array &$form, FormStateInterface $form_state) {
 }

 /**
  * @ERROR!!!
  */
 public function submitForm(array &$form, FormStateInterface $form_state) {
  $values = array ();
  $fields = $form_state->getValues();
  $sales_id = $fields['sales_id'];
  $sales_date = $fields['sales_date']->format('Y-m-d G:i:s');
  $customer_code = $fields['customer_code'];
  $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
  $create_date = date('Y-m-d G:i:s');

  for($idx = 1; $idx <= 5; $idx ++) {
   if ((! empty($fields["sales_quantity_$idx"])) && (! empty($fields["sales_price_$idx"]))) {
    $values[] = array (
      'sales_id' => $sales_id,
      'sales_date' => $sales_date,
      'commodity_code' => $fields["commodity_code_$idx"],
      'sales_quantity' => $fields["sales_quantity_$idx"],
      'sales_price' => $fields["sales_price_$idx"],
      'customer_code' => $customer_code,
      'user' => $user->get('name')->value,
      'create_date' => $create_date
    );
   }
  }

  $query = db_insert('sales')->fields(array (
    'sales_id',
    'sales_date',
    'commodity_code',
    'sales_quantity',
    'sales_price',
    'customer_code',
    'user',
    'create_date'
  ));
  foreach ( $values as $record ) {
   $query->values($record);
  }
  $query->execute();
  drupal_set_message("Đăng Ký Thành Công");
  $response = new RedirectResponse("/sales/register");
  $response->send();
 }
}
