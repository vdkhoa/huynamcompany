<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 9/16/2017
 * Time: 18:00
 */

namespace Drupal\huynam\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\huynam\Plugin\Common;
use Symfony\Component\HttpFoundation\JsonResponse;

class KhachHangController extends ControllerBase {

  /**
   * Hiển thị thông tin khách hàng
   * @return string
   */
  public function danhSachKhachHang() {
    $build['#theme'] = 'danh_sach_khach_hang';
    $build['#attached'] = [
      'library' => [
        'huynam/huynam.style',
        'huynam/bootstrap-table.style'
      ],
    ];

    return $build;
  }

  /**
   * Get thông tin khách hàng
   * @return JsonResponse
   */
  public function getDanhSachKhachHang() {
    // select records from table
    $query = \Drupal::database()->select('khach_hang', 'kh');
    $query->fields('kh', [
      'id',
      'ma_khach_hang',
      'ten_khach_hang',
      'dia_chi_khach_hang',
      'nguoi_dai_dien',
      'so_dien_thoai_khach_hang',
      'loai_cong_no',
      'loai_khach_hang',
    ]);
    $query->orderBy('ten_khach_hang');
    $results = $query->execute()->fetchAll();
    $rows = [];
    foreach ($results as $data) {
      $edit = Url::fromRoute('khach_hang.cap_nhat_khach_hang', ['id' => $data->id]);
      $delete = Url::fromRoute('khach_hang.xoa_khach_hang', ['id' => $data->id]);
      $rows[] = [
        'id' => $data->id,
        'ma_khach_hang' => $data->ma_khach_hang,
        'ten_khach_hang' => $data->ten_khach_hang,
        'dia_chi_khach_hang' => $data->dia_chi_khach_hang,
        'nguoi_dai_dien' => $data->nguoi_dai_dien,
        'so_dien_thoai_khach_hang' => $data->so_dien_thoai_khach_hang,
        'loai_cong_no' => Common::getTenCongNo($data->loai_cong_no),
        'loai_khach_hang' => Common::getTenLoaiKhachHang($data->loai_khach_hang),
        'edit' => \Drupal::l('edit', $edit),
        'delete' => \Drupal::l('Delete', $delete),
      ];
    }
    return new JsonResponse($rows);
  }
}