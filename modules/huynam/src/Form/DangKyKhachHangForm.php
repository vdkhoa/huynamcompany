<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 9/16/2017
 * Time: 21:47
 */

namespace Drupal\huynam\Form;

use Drupal\Core\Database\IntegrityConstraintViolationException;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\huynam\Plugin\Common;
use Drupal\huynam\Plugin\Constant;

class DangKyKhachHangForm extends FormBase {

  public function getFormId() {
    return 'frm_dang_ky_khach_hang';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['ma_khach_hang'] = [
      '#type' => 'textfield',
      '#maxlength' => 10,
    ];

    $form['ten_khach_hang'] = [
      '#type' => 'textfield',
      '#maxlength' => 50,
    ];

    $form['dia_chi_khach_hang'] = [
      '#type' => 'textarea',
      '#maxlength' => 255,
    ];

    $form['nguoi_dai_dien'] = [
      '#type' => 'textfield',
      '#maxlength' => 50,
    ];

    $form['so_dien_thoai_khach_hang'] = [
      '#type' => 'textfield',
      '#maxlength' => 11,
    ];

    $form['loai_cong_no'] = [
      '#type' => 'select',
      '#options' => Common::$loai_cong_no,
      '#default_value' => 0,
    ];

    $form['loai_khach_hang'] = [
      '#type' => 'select',
      '#options' => Common::$loai_khach_hang,
      '#default_value' => 0,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'save',
    ];

    $form['#theme'] = 'dang_ky_khach_hang_form';
    $form['#attached'] = [
      'library' => [
        'huynam/huynam.style',
      ],
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $khach_hang = $form_state->getValues();
    $ma_khach_hang = $khach_hang['ma_khach_hang'];
    if ($ma_khach_hang === '') {
      $form_state->setErrorByName('ma_khach_hang', t('Xin hãy nhập mã khách hàng.'));
    }
    else {
      if ($khach_hang['ten_khach_hang'] === '') {
        $form_state->setErrorByName('ten_khach_hang', t('Xin hãy nhập tên khách hàng.'));
      }
      else {
        if ($this->getMaKhachHang($ma_khach_hang) !== FALSE) {
          $form_state->setErrorByName('ma_khach_hang', t('Mã khách hàng ' . $ma_khach_hang . ' đã tồn tại.'));
        }
      }
    }

    parent::validateForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    try {
      $field = $form_state->getValues();
      $ma_khach_hang = $field['ma_khach_hang'];
      $ten_khach_hang = $field['ten_khach_hang'];
      $dia_chi_khach_hang = $field['dia_chi_khach_hang'];
      $nguoi_dai_dien = $field['nguoi_dai_dien'];
      $so_dien_thoai_khach_hang = $field['so_dien_thoai_khach_hang'];
      $loai_cong_no = $field['loai_cong_no'];
      $loai_khach_hang = $field['loai_khach_hang'];
      $field = [
        'ma_khach_hang' => $ma_khach_hang,
        'ten_khach_hang' => $ten_khach_hang,
        'dia_chi_khach_hang' => $dia_chi_khach_hang,
        'nguoi_dai_dien' => $nguoi_dai_dien,
        'so_dien_thoai_khach_hang' => $so_dien_thoai_khach_hang,
        'loai_cong_no' => $loai_cong_no,
        'loai_khach_hang' => $loai_khach_hang,
      ];
      $query = \Drupal::database();
      $query->insert('khach_hang')->fields($field)->execute();
      drupal_set_message(t(Constant::REGISTER_SUCCESS));
    } catch (IntegrityConstraintViolationException $e) {
      drupal_set_message(t(Constant::REGISTER_FAIL));
    } catch (\Exception $e) {
      drupal_set_message(t(Constant::REGISTER_FAIL));
    }
  }

  public function getMaKhachHang($ma_khach_hang) {
    try {
      $query = \Drupal::database()->select('khach_hang', 'kh');
      $query->condition('ma_khach_hang', $ma_khach_hang);
      $query->addField('kh', 'ma_khach_hang');
      return $query->execute()->fetchField();
    } catch (\Exception $e) {
      drupal_set_message(t(Constant::SYSTEM_ERROR));
    }
  }
}