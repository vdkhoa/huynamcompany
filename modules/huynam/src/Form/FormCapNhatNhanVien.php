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

class FormCapNhatNhanVien extends FormBase {

  public $id;

  public function getFormId() {
    return 'frm_cap_nhat_nhan_vien';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $this->id = $id;
    $record = [];
    if ($this->id) {
      $query = \Drupal::database()->select('nhan_vien', 'nv')
        ->condition('id', $this->id)
        ->fields('nv');
      $record = $query->execute()->fetchAssoc();
    }
    $form['ma_nhan_vien'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#default_value' => $record['ma_nhan_vien'],
    ];

    $form['ho_ten_nhan_vien'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#default_value' => $record['ho_ten_nhan_vien'],
    ];

    $form['dia_chi_nhan_vien'] = [
      '#type' => 'textfield',
      '#default_value' => $record['dia_chi_nhan_vien'],
    ];

    $form['ngay_sinh_nhan_vien'] = [
      '#type' => 'textfield',
      '#default_value' => $record['ngay_sinh_nhan_vien'],
    ];

    $form['gioi_tinh_nhan_vien'] = [
      '#type' => 'radios',
      '#required' => TRUE,
      '#default_value' => $record['gioi_tinh_nhan_vien'],
      '#options' => ['Nam' => t('Nam'), 'Nữ' => t('Nữ')],
    ];

    $form['so_dien_thoai_nhan_vien'] = [
      '#type' => 'textfield',
      '#default_value' => $record['so_dien_thoai_nhan_vien'],
    ];

    $form['chuc_vu_nhan_vien'] = [
      '#type' => 'select',
      '#options' => Common::$chuc_vu,
      '#default_value' => $record['chuc_vu_nhan_vien'],
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'save',
    ];

    $form['#theme'] = 'form_cap_nhat_nhan_vien';
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

    if (isset($this->id)) {
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
      $query->update('nhan_vien')
        ->fields($field)
        ->condition('id', $this->id)
        ->execute();
      drupal_set_message(Constant::UPDATE_SUCEESS);
      $form_state->setRedirect('nhan_vien.danh_sach_nhan_vien');
    }
  }
}