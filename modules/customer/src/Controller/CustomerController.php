<?php

namespace Drupal\customer\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

class CustomerController extends ControllerBase {
 /**
  *
  * @return string
  */
 public function lstCustomer() {
  // create table header
  $header_table = array (
    'id' => t('ID'),
    'customer_code' => t('Mã Khách Hàng'),
    'customer_name' => t('Họ Tên Khách Hàng'),
    'customer_address' => t('Địa Chỉ Khách Hàng'),
    'customer_represent' => t('Người Đại Diện'),
    'customer_mobiphone' => t('Số Điện Thoại'),
    'opt' => t(''),
    'opt1' => t('')
  );

  // select records from table
  $query = \Drupal::database()->select('customer','cus');
  $query->fields('cus',[
    'id',
    'customer_code',
    'customer_name',
    'customer_address',
    'customer_represent',
    'customer_mobiphone'
  ]);
  $query->orderBy('customer_name');
  $results = $query->execute()->fetchAll();
  $rows = array ();
  foreach ( $results as $data ) {
   $delete = Url::fromUserInput('/customer/delete/' . $data->id);
   $edit = Url::fromUserInput('/customer/update/' . $data->id);
   // print the data from table
   $rows[] = array (
     'id' => $data->id,
     'customer_code' => $data->customer_code,
     'customer_name' => $data->customer_name,
     'customer_address' => $data->customer_address,
     'customer_represent' => $data->customer_represent,
     'customer_mobiphone' => $data->customer_mobiphone,
     'delete' => \Drupal::l('Delete',$delete),
     'edit' => \Drupal::l('Edit',$edit)
   );
  }

//   // display data in site
//   $form['table'] = [
//     '#type' => 'table',
//     '#header' => $header_table,
//     '#rows' => $rows,
//     '#empty' => t('No users found')
//   ];
  $build['#theme'] = 'customer_list';
  $build['#list_customer'] = $rows;
  $build['#attached'] = array(
    'library' => array(
      'customer/customer.style'
    )
  );

  return $build;
 }
}