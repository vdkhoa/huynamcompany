<?php

namespace Drupal\customer\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\customer\Plugin\Constant;


class CustomerController extends ControllerBase {

  /**
   *
   * @return string
   */
  public function lstCustomer() {
    $build['#theme'] = 'customer_list';
    $build['#attached'] = [
      'library' => [
        'customer/customer.style',
      ],
    ];

    return $build;
  }

  public function getListCustomer() {
    // select records from table
    $query = \Drupal::database()->select('customer', 'cus');
    $query->fields('cus', [
      'id',
      'customer_code',
      'customer_name',
      'customer_address',
      'customer_represent',
      'customer_mobiphone',
      'customer_debt',
    ]);
    $query->orderBy('customer_name');
    $results = $query->execute()->fetchAll();
    $rows = [];
    foreach ($results as $data) {
      $delete = Url::fromUserInput('/customer/delete/' . $data->id);
      $edit = Url::fromUserInput('/customer/update/' . $data->id);
      // print the data from table
      $rows[] = [
        'id' => $data->id,
        'customer_code' => $data->customer_code,
        'customer_name' => $data->customer_name,
        'customer_address' => $data->customer_address,
        'customer_represent' => $data->customer_represent,
        'customer_mobiphone' => $data->customer_mobiphone,
        'customer_debt' => Constant::getDeptName($data->customer_debt),
        'delete' => \Drupal::l('Delete', $delete),
        'edit' => \Drupal::l('Edit', $edit),
      ];
    }

    return new JsonResponse($rows);
  }
}