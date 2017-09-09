<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 9/9/2017
 * Time: 23:29
 */

namespace Drupal\customer\Plugin;

class Constant {

  public static $debt = [
    0 => 'Công Nợ Ngày',
    1 => 'Công Nợ Tuần',
    2 => 'Công Nợ Tháng',
    3 => 'Công Nợ Khác',
  ];

  public static function getDeptName($debt) {
    $debt_name = 'Công Nợ Khác';
    switch ($debt) {
      case "0";
        $debt_name = 'Công Nợ Ngày';
        break;
      case "1";
        $debt_name = 'Công Nợ Tuần';
        break;
      case "2";
        $debt_name = 'Công Nợ Tháng';
        break;
      case "3";
        $debt_name = 'Công Nợ Khác';
        break;
      default:
        break;
    }
    return $debt_name;
  }
}