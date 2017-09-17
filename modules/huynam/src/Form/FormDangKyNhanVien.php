<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 9/17/2017
 * Time: 11:48
 */

namespace Drupal\huynam\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\huynam\Plugin\Common;
use Drupal\huynam\Plugin\Constant;

class FormDangKyNhanVien extends FormBase {

  public function getFormId() {
    return 'frm_dang_ky_nhan_vien';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['ma_nhan_vien'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
    ];

    $form['ho_ten_nhan_vien'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
    ];

    $form['dia_chi_nhan_vien'] = [
      '#type' => 'textfield',
    ];

    $form['ngay_sinh_nhan_vien'] = [
      '#type' => 'textfield',
    ];

    $form['gioi_tinh_nhan_vien'] = [
      '#type' => 'radios',
      '#required' => TRUE,
      '#default_value' => 'Nam',
      '#options' => [
        'Nam' => t('Nam'),
        'Nữ' => t('Nữ'),
      ],
    ];

    $form['so_dien_thoai_nhan_vien'] = [
      '#type' => 'textfield',
    ];

    $form['chuc_vu_nhan_vien'] = [
      '#type' => 'select',
      '#options' => Common::$chuc_vu,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'save',
    ];

    $form['#theme'] = 'form_dang_ky_nhan_vien';
    $form['#attached'] = [
      'library' => [
        'huynam/huynam.style',
      ],
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $field = $form_state->getValues();
    $ma_nhan_vien = $field['ma_nhan_vien'];
    $ho_ten_nhan_vien = $field['ho_ten_nhan_vien'];
    $dia_chi_nhan_vien = $field['dia_chi_nhan_vien'];
    $ngay_sinh_nhan_vien = $field['ngay_sinh_nhan_vien'];
    $gioi_tinh_nhan_vien = $field['gioi_tinh_nhan_vien'];
    $so_dien_thoai_nhan_vien = $field['so_dien_thoai_nhan_vien'];
    $chuc_vu_nhan_vien = $field['chuc_vu_nhan_vien'];

    $field = [
      'ma_nhan_vien' => $ma_nhan_vien,
      'ho_ten_nhan_vien' => $ho_ten_nhan_vien,
      'dia_chi_nhan_vien' => $dia_chi_nhan_vien,
      'ngay_sinh_nhan_vien' => $ngay_sinh_nhan_vien,
      'gioi_tinh_nhan_vien' => $gioi_tinh_nhan_vien,
      'so_dien_thoai_nhan_vien' => $so_dien_thoai_nhan_vien,
      'chuc_vu_nhan_vien' => $chuc_vu_nhan_vien,
    ];

    $query = \Drupal::database();
    $query->insert('nhan_vien')->fields($field)->execute();
    drupal_set_message(Constant::REGISTER_SUCCESS);
  }
}