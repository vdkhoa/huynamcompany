<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 9/17/2017
 * Time: 10:09
 */

namespace Drupal\huynam\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\huynam\Plugin\Common;
use Drupal\huynam\Plugin\Constant;

class CapNhatKhachHangForm extends FormBase {

  public $id;

  public function getFormId() {
    return 'frm_cap_nhat_khach_hang';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $this->id = $id;
    $record = [];
    if ($this->id) {
      $query = \Drupal::database()->select('khach_hang', 'kh')
        ->condition('id', $this->id)
        ->fields('kh');
      $record = $query->execute()->fetchAssoc();
    }
    $form['ma_khach_hang'] = [
      '#type' => 'textfield',
      '#default_value' => $record['ma_khach_hang'],
      '#maxlength' => 10,
      '#disabled' => TRUE,
    ];

    $form['ten_khach_hang'] = [
      '#type' => 'textfield',
      '#default_value' => $record['ten_khach_hang'],
      '#maxlength' => 50,
    ];

    $form['dia_chi_khach_hang'] = [
      '#type' => 'textarea',
      '#default_value' => $record['dia_chi_khach_hang'],
      '#maxlength' => 255,
    ];

    $form['nguoi_dai_dien'] = [
      '#type' => 'textfield',
      '#default_value' => $record['nguoi_dai_dien'],
      '#maxlength' => 50,
    ];

    $form['so_dien_thoai_khach_hang'] = [
      '#type' => 'textfield',
      '#default_value' => $record['so_dien_thoai_khach_hang'],
      '#maxlength' => 11,
    ];

    $form['loai_cong_no'] = [
      '#type' => 'select',
      '#options' => Common::$loai_cong_no,
      '#default_value' => $record['loai_cong_no'],
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

    $form['cancel'] = [
      '#type' => 'link',
      '#title' => $this->t('Cancel'),
      '#attributes' => [
        'class' => [
          'btn btn-primary',
        ],
      ],
      '#url' => Url::fromRoute('khach_hang.danh_sach_khach_hang'),
    ];

    $form['#theme'] = 'cap_nhat_khach_hang_form';
    $form['#attached'] = [
      'library' => [
        'huynam/huynam.style',
      ],
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $khach_hang = $form_state->getValues();
    if ($khach_hang['ten_khach_hang'] === '') {
      $form_state->setErrorByName('ten_khach_hang', t('Xin hãy nhập tên khách hàng.'));
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

      if (isset($this->id)) {
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
        $query->update('khach_hang')
          ->fields($field)
          ->condition('id', $this->id)
          ->execute();
        drupal_set_message(Constant::UPDATE_SUCEESS);
        $form_state->setRedirect('khach_hang.danh_sach_khach_hang');
      }
    } catch (\Exception $e) {
      drupal_set_message(Constant::UPDATE_FAIL);
    }
  }
}