<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 9/17/2017
 * Time: 10:41
 */

namespace Drupal\huynam\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\huynam\Plugin\Common;
use Drupal\huynam\Plugin\Constant;

class XoaKhachHangForm extends FormBase {
  public $id;

  public function getFormId() {
    return 'frm_xoa_khach_hang';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $this->id = $id;
    $query = \Drupal::database()->select('khach_hang', 'kh');
    $query->condition('id', $this->id);
    $query->fields('kh');

    $result = $query->execute()->fetchAssoc();
    if ($result) {
      $form['ma_khach_hang'] = [
        '#type' => 'textfield',
        '#default_value' => $result['ma_khach_hang'],
        '#disabled' => TRUE,
      ];

      $form['ten_khach_hang'] = [
        '#type' => 'textfield',
        '#default_value' => $result['ten_khach_hang'],
        '#disabled' => TRUE,
      ];

      $form['dia_chi_khach_hang'] = [
        '#type' => 'textfield',
        '#default_value' => $result['dia_chi_khach_hang'],
        '#disabled' => TRUE,
      ];

      $form['nguoi_dai_dien'] = [
        '#type' => 'textfield',
        '#default_value' => $result['nguoi_dai_dien'],
        '#disabled' => TRUE,
      ];

      $form['so_dien_thoai_khach_hang'] = [
        '#type' => 'textfield',
        '#default_value' => $result['so_dien_thoai_khach_hang'],
        '#disabled' => TRUE,
      ];

      $form['loai_cong_no'] = [
        '#type' => 'textfield',
        '#default_value' => Common::getTenCongNo($result['loai_cong_no']),
        '#disabled' => TRUE,
      ];

      $form['loai_khach_hang'] = [
        '#type' => 'textfield',
        '#default_value' => Common::getTenLoaiKhachHang($result['loai_khach_hang']),
        '#disabled' => TRUE,
      ];
    }

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Delete',
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

    $form['#theme'] = 'xoa_khach_hang_form';
    $form['#attached'] = [
      'library' => [
        'huynam/huynam.style',
      ],
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    try {
      $query = \Drupal::database()
        ->delete('khach_hang')
        ->condition('id', $this->id)
        ->execute();
      drupal_set_message(Constant::DELETE_SUCCESS);
      $form_state->setRedirect('khach_hang.danh_sach_khach_hang');
    } catch (\Exception $e) {
      drupal_set_message(Constant::DELETE_FAIL);
    }
  }
}