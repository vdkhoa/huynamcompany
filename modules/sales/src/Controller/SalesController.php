<?php

namespace Drupal\sales\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

class SalesController extends ControllerBase {
 /**
  *
  * @return string
  */
 public function lstSales() {
  // create table header
  $header_table = array (
    'id' => t('ID'),
    'sales_id' => t('Số Hóa Đơn'),
    'sales_date' => t('Ngày Bán'),
    'commodity_name' => t('Tên Hàng'),
    'sales_quantity' => t('Số Lượng'),
    'sales_price' => t('Đơn Giá'),
    'customer_name' => t('Khách Hàng') 
  );
  
  // select records from table
  $query = \Drupal::database()->select('sales', 'sal');
  $query->join('commodity', 'com', 'sal.commodity_code = com.commodity_code');
  $query->join('customer', 'cus', 'sal.customer_code = cus.customer_code');
  $query->fields('sal', [ 
    'id',
    'sales_id',
    'sales_date',
    'commodity_code',
    'sales_quantity',
    'sales_price',
    'customer_code' 
  ]);
  $query->fields('com', [ 
    'commodity_name' 
  ]);
  $query->fields('cus', [ 
    'customer_name' 
  ]);
  $query->orderBy('sales_id');
  $results = $query->execute()->fetchAll();
  $rows = array ();
  foreach ( $results as $data ) {
   // print the data from table
   $rows[] = array (
     'id' => $data->id,
     'sales_id' => $data->sales_id,
     'sales_date' => $data->sales_date,
     'commodity_name' => $data->commodity_name,
     'sales_quantity' => $data->sales_quantity,
     'sales_price' => $data->sales_price,
     'customer_name' => $data->customer_name 
   );
  }
  
  // display data in site
  $form['table'] = [ 
    '#type' => 'table',
    '#header' => $header_table,
    '#rows' => $rows,
    '#empty' => t('No users found') 
  ];
  
  return $form;
 }
}