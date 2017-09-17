<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 9/16/2017
 * Time: 21:33
 */

namespace Drupal\huynam\Plugin;

class Common {

  public static $loai_cong_no = [
    0 => 'Công Nợ Ngày',
    1 => 'Công Nợ Tuần',
    2 => 'Công Nợ Tháng',
    3 => 'Công Nợ Khác',
  ];

  public static $loai_khach_hang = [
    0 => 'Khách Lẻ',
    1 => 'Đại Lý',
    2 => 'Nhà Hàng',
    3 => 'Khách Sạn',
  ];

  public static $chuc_vu = [
    'Văn Phòng' => 'Văn Phòng',
    'Tài Xế' => 'Tài Xế',
    'Giao Hàng' => 'Giao Hàng',
    'Sản Xuất' => 'Sản Xuất',
    'Kỹ Thuật' => 'Kỹ Thuật',
  ];

  public static function getTenCongNo($ma_cong_no) {
    $ten_cong_no = 'Công Nợ Khác';
    switch ($ma_cong_no) {
      case "0";
        $ten_cong_no = 'Công Nợ Ngày';
        break;
      case "1";
        $ten_cong_no = 'Công Nợ Tuần';
        break;
      case "2";
        $ten_cong_no = 'Công Nợ Tháng';
        break;
      case "3";
        $ten_cong_no = 'Công Nợ Khác';
        break;
      default:
        break;
    }

    return $ten_cong_no;
  }

  public static function getTenLoaiKhachHang($ma_loai_khach_hang) {
    $ten_loai_khach_hang = 'Khách Lẻ';
    switch ($ma_loai_khach_hang) {
      case "0";
        $ten_loai_khach_hang = 'Khách Lẻ';
        break;
      case "1";
        $ten_loai_khach_hang = 'Đại Lý';
        break;
      case "2";
        $ten_loai_khach_hang = 'Nhà Hàng';
        break;
      case "3";
        $ten_loai_khach_hang = 'Khách Sạn';
        break;
      default:
        break;
    }

    return $ten_loai_khach_hang;
  }
}