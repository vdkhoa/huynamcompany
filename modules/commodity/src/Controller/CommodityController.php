<?php
namespace Drupal\commodity\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

class CommodityController extends ControllerBase
{
  /**
   * 
   * @return string
   */
	public function lstCommodity() {
    //create table header
    $header_table = array(
      'id'=>    t('ID'),
      'commodity_code' => t('Mã Hàng Hóa'),
      'commodity_name' => t('Tên Hàng Hóa'),
      'commodity_price'=>t('Đơn Giá'),
      'opt' => t(''),
      'opt1' => t(''),
    );
    
    //select records from table
    $query = \Drupal::database()->select('commodity', 'com');
    $query->fields('com', ['id','commodity_code','commodity_name','commodity_price']);
    $results = $query->execute()->fetchAll();
    $rows=array();
    foreach($results as $data) {
      $delete = Url::fromUserInput('/commodity/delete/' . $data->id);
      $edit   = Url::fromUserInput('/commodity/update/' . $data->id);
      //print the data from table
      $rows[] = array(
        'id' =>$data->id,
        'commodity_code' => $data->commodity_code,
        'commodity_name' => $data->commodity_name,
        'commodity_price' => $data->commodity_price,
        \Drupal::l('Delete', $delete),
        \Drupal::l('Edit', $edit),
      );
    }
    
    //display data in site
    $form['table'] = [
      '#type' => 'table',
      '#header' => $header_table,
      '#rows' => $rows,
      '#empty' => t('No users found'),
    ];
    
    return $form;
  }
}