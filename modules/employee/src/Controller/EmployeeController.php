<?php
namespace Drupal\employee\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

class EmployeeController extends ControllerBase
{
  /**
   * 
   * @return string
   */
	public function lstEmployee() {
    //create table header
    $header_table = array(
      'id'=>    t('ID'),
      'employee_code' => t('Mã Nhân Viên'),
      'employee_name' => t('Họ Tên Nhân Viên'),
      'employee_address'=>t('Địa Chỉ Nhân Viên'),
      'employee_birthday' => t('Năm Sinh'),
      'employee_gender' => t('Giới Tính'),
      'employee_mobiphone' => t('Số Điện Thoại'),
      'opt' => t(''),
      'opt1' => t(''),
    );
    
    //select records from table
    $query = \Drupal::database()->select('employee', 'nv');
    $query->fields('nv', ['id','employee_code','employee_name','employee_address','employee_birthday','employee_gender','employee_mobiphone']);
    $results = $query->execute()->fetchAll();
    $rows=array();
    foreach($results as $data) {
      $delete = Url::fromUserInput('/employee/delete/' . $data->id);
      $edit   = Url::fromUserInput('/employee/update/' . $data->id);
      //print the data from table
      $rows[] = array(
        'id' =>$data->id,
        'employee_code' => $data->employee_code,
        'employee_name' => $data->employee_name,
        'employee_address' => $data->employee_address,
        'employee_birthday' => $data->employee_birthday,
        'employee_gender' => $data->employee_gender,
        'employee_mobiphone' => $data->employee_mobiphone,
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