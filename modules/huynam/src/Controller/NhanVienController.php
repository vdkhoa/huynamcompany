<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 9/17/2017
 * Time: 11:40
 */

namespace Drupal\huynam\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\JsonResponse;

class NhanVienController extends ControllerBase {

  public function danhSachNhanVien() {
    $build['#theme'] = 'danh_sach_nhan_vien';
    $build['#attached'] = [
      'library' => [
        'huynam/huynam.style',
        'huynam/bootstrap-table.style'
      ],
    ];

    return $build;
  }

  public function getDanhSachNhanVien() {
    // select records from table
    $query = \Drupal::database()->select('nhan_vien', 'nv');
    $query->fields('nv', [
      'id',
      'ma_nhan_vien',
      'ho_ten_nhan_vien',
      'dia_chi_nhan_vien',
      'ngay_sinh_nhan_vien',
      'gioi_tinh_nhan_vien',
      'so_dien_thoai_nhan_vien',
      'chuc_vu_nhan_vien',
    ]);
    $results = $query->execute()->fetchAll();
    $rows = [];
    foreach ($results as $data) {
      $delete = Url::fromRoute('nhan_vien.xoa_nhan_vien', ['id' => $data->id]);
      $edit = Url::fromRoute('nhan_vien.cap_nhat_nhan_vien', ['id' => $data->id]);
      // print the data from table
      $rows[] = [
        'id' => $data->id,
        'ma_nhan_vien' => $data->ma_nhan_vien,
        'ho_ten_nhan_vien' => $data->ho_ten_nhan_vien,
        'dia_chi_nhan_vien' => $data->dia_chi_nhan_vien,
        'ngay_sinh_nhan_vien' => $data->ngay_sinh_nhan_vien,
        'gioi_tinh_nhan_vien' => $data->gioi_tinh_nhan_vien,
        'so_dien_thoai_nhan_vien' => $data->so_dien_thoai_nhan_vien,
        'chuc_vu_nhan_vien' => $data->chuc_vu_nhan_vien,
        'delete' => \Drupal::l('Delete', $delete),
        'edit' => \Drupal::l('Edit', $edit),
      ];
    }

    return new JsonResponse($rows);
  }
}

