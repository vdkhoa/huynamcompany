<?php
namespace Drupal\commodity\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RegisterCommodityForm extends FormBase
{
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'register';
  }
  
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $conn = Database::getConnection();
    $record = array();
    if (isset($_GET['id'])) {
      $query = $conn->select('commodity', 'com')
      ->condition('id', $_GET['id'])
      ->fields('com');
      $record = $query->execute()->fetchAssoc();
    }
    
    $form['commodity_code'] = array(
      '#type' => 'textfield',
      '#title' => t('Mã Hàng Hóa:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['commodity_code']) && $_GET['id']) ? $record['commodity_code']:'',
    );
    
    $form['commodity_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Tên Hàng Hóa:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['commodity_name']) && $_GET['id']) ? $record['commodity_name']:'',
    );
    
    $form['commodity_price'] = array(
      '#type' => 'textfield',
      '#title' => t('Đơn Giá:'),
      '#default_value' => (isset($record['commodity_price']) && $_GET['id']) ? $record['commodity_price']:'',
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
    $commodity_code = $field['commodity_code'];
    $commodity_name = $field['commodity_name'];
    $commodity_price = $field['commodity_price'];
    
    if (isset($_GET['id'])) {
      
    } else {
      $field  = array(
        'commodity_code'   =>  $commodity_code,
        'commodity_name' =>  $commodity_name,
        'commodity_price' =>  $commodity_price,
      );
      
      $query = \Drupal::database();
      $query ->insert('commodity')
      ->fields($field)
      ->execute();
      drupal_set_message("Đăng Ký Thành Công");
      $response = new RedirectResponse("/commodity/list-commodity");
      $response->send();
    }
  }
}

