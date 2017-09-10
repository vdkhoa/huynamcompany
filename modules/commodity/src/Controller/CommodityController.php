<?php

namespace Drupal\commodity\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\JsonResponse;

class CommodityController extends ControllerBase {

  /**
   *
   * @return string
   */
  public function index() {
    $build['#theme'] = 'commodity_index';
    $build['#attached'] = [
      'library' => [
        'commodity/commodity.style',
      ],
    ];

    return $build;
  }

  public function getListCommodity() {
    //select records from table
    $query = \Drupal::database()->select('commodity', 'com');
    $query->fields('com', [
      'id',
      'commodity_code',
      'commodity_name',
      'commodity_price',
    ]);
    $results = $query->execute()->fetchAll();
    $rows = [];
    foreach ($results as $data) {
      $delete = Url::fromUserInput('/commodity/delete/' . $data->id);
      $edit = Url::fromUserInput('/commodity/update/' . $data->id);
      //print the data from table
      $rows[] = [
        'id' => $data->id,
        'commodity_code' => $data->commodity_code,
        'commodity_name' => $data->commodity_name,
        'commodity_price' => $data->commodity_price,
        'delete' => \Drupal::l('Delete', $delete),
        'edit' => \Drupal::l('Edit', $edit),
      ];
    }

    return new JsonResponse($rows);
  }
}