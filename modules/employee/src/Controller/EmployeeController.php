<?php

namespace Drupal\employee\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\JsonResponse;

class EmployeeController extends ControllerBase {

  /**
   *
   * @return string
   */
  public function lstEmployee() {
    $build['#theme'] = 'employee_list';
    $build['#attached'] = [
      'library' => [
        'employee/employee.style',
      ],
    ];

    return $build;
  }

  /**
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function getListEmployee() {
    // select records from table
    $query = \Drupal::database()->select('employee', 'nv');
    $query->fields('nv', [
      'id',
      'employee_code',
      'employee_name',
      'employee_address',
      'employee_birthday',
      'employee_gender',
      'employee_mobiphone',
      'employee_regency',
    ]);
    $results = $query->execute()->fetchAll();
    $rows = [];
    foreach ($results as $data) {
      $delete = Url::fromUserInput('/employee/delete/' . $data->id);
      $edit = Url::fromUserInput('/employee/update/' . $data->id);
      // print the data from table
      $rows[] = [
        'id' => $data->id,
        'employee_code' => $data->employee_code,
        'employee_name' => $data->employee_name,
        'employee_address' => $data->employee_address,
        'employee_birthday' => $data->employee_birthday,
        'employee_gender' => $data->employee_gender,
        'employee_mobiphone' => $data->employee_mobiphone,
        'employee_regency' => $data->employee_regency,
        'delete' => \Drupal::l('Delete', $delete),
        'edit' => \Drupal::l('Edit', $edit),
      ];
    }

    return new JsonResponse($rows);
  }
}