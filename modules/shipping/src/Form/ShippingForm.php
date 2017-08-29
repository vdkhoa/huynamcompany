<?php

namespace Drupal\shipping\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ShippingForm extends FormBase {
  /**
   */
  public function getFormId(){
    return 'shipping_register';
  }

  /**
   */
  public function buildForm(array $form, FormStateInterface $form_state){
    // get list employee
    $employees = $this->lstEmployee();
    // get list commodity
    $commodities = $this->lstCommodity();
    // get list customer
    $customers = $this->lstCustomer();

    $type = array(
        '3h00' => 'Chuyến 3h00',
        '4h00' => 'Chuyến 4h00',
        '6h30' => 'Chuyến 6h30',
        '8h00' => 'Chuyến 8h00',
        '13h00' => 'Chuyến 13h00',
        '14h00' => 'Chuyến 14h00',
        '18h00' => 'Chuyến 18h00'
    );

    $form['ship'] = array(
        '#type' => 'select',
        '#required' => TRUE,
        '#options' => $type
    );

    $form['driver'] = array(
        '#type' => 'select',
        '#title' => t('Tài Xế'),
        '#required' => TRUE,
        '#options' => $employees
    );

    $form['sub_driver1'] = array(
        '#type' => 'select',
        '#title' => t('Giao Hàng 1'),
        '#options' => $employees
    );

    $form['sub_driver2'] = array(
        '#type' => 'select',
        '#title' => t('Giao Hàng 2'),
        '#options' => $employees
    );

    $form['add_customer'] = [
        '#type' => 'button',
        '#value' => t('Thêm Khách Hàng'),
        '#attributes' => array(
            'class' => array(
                'btn-info'
            )
        )
    ];

    $form['customer_code'] = array(
        '#type' => 'select',
        '#options' => $customers
    );

    $form['mi_quantity'] = array(
        '#type' => 'textfield',
        '#default_value' => 0
    );

    $form['bia_quantity'] = array(
        '#type' => 'textfield',
        '#default_value' => 0
    );

    $form['xay_quantity'] = array(
        '#type' => 'textfield',
        '#default_value' => 0
    );

    $form['cat_quantity'] = array(
        '#type' => 'textfield',
        '#default_value' => 0
    );

    $form['ruou_quantity'] = array(
        '#type' => 'textfield',
        '#default_value' => 0
    );

    $form['submit'] = [
        '#type' => 'submit',
        '#value' => t('save')
    ];

    $form['#theme'] = 'shipping_form';
    $form['#attached'] = array(
        'library' => array(
            'shipping/shipping.style'
        )
    );

    return $form;
  }

  /**
   * Danh Sach Hang Hoa
   *
   * @return multitype:\Drupal\Core\StringTranslation\TranslatableMarkup
   */
  public function lstCommodity(){
    $query = \Drupal::database()->select('commodity','com');
    $query->fields('com',[
        'commodity_code',
        'commodity_name'
    ]);
    $results = $query->execute()->fetchAll();
    $options = array();
    foreach ( $results as $item ) {
      $options[$item->commodity_code] = t($item->commodity_name);
    }
    return $options;
  }

  /**
   * Danh Sach Khach Hang
   *
   * @return multitype:\Drupal\Core\StringTranslation\TranslatableMarkup
   */
  public function lstCustomer(){
    $query = \Drupal::database()->select('customer','cus');
    $query->fields('cus',[
        'customer_code',
        'customer_name'
    ]);
    $results = $query->execute()->fetchAll();
    $options = array();
    foreach ( $results as $item ) {
      $options[$item->customer_code] = t($item->customer_name);
    }
    return $options;
  }

  /**
   *
   * @return multitype:\Drupal\Core\StringTranslation\TranslatableMarkup
   */
  public function lstEmployee(){
    $query = \Drupal::database()->select('employee','emp');
    $query->fields('emp',[
        'employee_code',
        'employee_name'
    ]);
    $results = $query->execute()->fetchAll();
    $options = array();
    foreach ( $results as $item ) {
      $options[$item->employee_code] = t($item->employee_name);
    }
    return $options;
  }

  /**
   *
   * @return number
   */
  public function getMaxSalesID(){
    $query = db_select('sales');
    $query->addExpression('MAX(sales_id)');
    $max_sales_id = $query->execute()->fetchField();
    return $max_sales_id === NULL ? 1 : intval($max_sales_id);
  }

  /**
   */
  public function validateForm(array &$form, FormStateInterface $form_state){
  }

  /**
   */
  public function submitForm(array &$form, FormStateInterface $form_state){
    $fields = $form_state->getValues();
    $create_date = date('Y-m-d G:i:s');
    $shipping = array(
        'ship' => $fields['ship'],
        'customer_code' => $fields['customer_code'],
        'order' => 1,
        'mi_quantity' => $fields['mi_quantity'],
        'bia_quantity' => $fields['bia_quantity'],
        'xay_quantity' => $fields['xay_quantity'],
        'cat_quantity' => $fields['cat_quantity'],
        'ruou_quantity' => $fields['ruou_quantity'],
        'driver' => $fields['driver'],
        'sub_driver1' => $fields['sub_driver1'],
        'sub_driver2' => $fields['sub_driver2'],
        'create_date' => $create_date
    );

    $query = db_insert('shipping');
    $query->fields($shipping);
    $query->execute();
    drupal_set_message("Đăng Ký Thành Công");
  }
}
