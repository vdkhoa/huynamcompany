<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 9/17/2017
 * Time: 11:49
 */

namespace Drupal\huynam\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\huynam\Plugin\Constant;

class FormXoaNhanVien extends FormBase {

  public $id;

  public function getFormId() {
    return 'frm_xoa_nhan_vien';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $this->id = $id;
    $query = \Drupal::database()->select('nhan_vien', 'nv');
    $query->condition('id', $this->id);
    $query->fields('nv');

    $result = $query->execute()->fetchAssoc();
    if ($result) {
      $form['ma_nhan_vien'] = [
        '#type' => 'textfield',
        '#default_value' => $result['ma_nhan_vien'],
        '#disabled' => TRUE,
      ];

      $form['ho_ten_nhan_vien'] = [
        '#type' => 'textfield',
        '#default_value' => $result['ho_ten_nhan_vien'],
        '#disabled' => TRUE,
      ];

      $form['dia_chi_nhan_vien'] = [
        '#type' => 'textfield',
        '#default_value' => $result['dia_chi_nhan_vien'],
        '#disabled' => TRUE,
      ];

      $form['ngay_sinh_nhan_vien'] = [
        '#type' => 'textfield',
        '#default_value' => $result['ngay_sinh_nhan_vien'],
        '#disabled' => TRUE,
      ];

      $form['gioi_tinh_nhan_vien'] = [
        '#type' => 'textfield',
        '#default_value' => $result['gioi_tinh_nhan_vien'],
        '#disabled' => TRUE,
      ];

      $form['so_dien_thoai_nhan_vien'] = [
        '#type' => 'textfield',
        '#default_value' => $result['so_dien_thoai_nhan_vien'],
        '#disabled' => TRUE,
      ];

      $form['chuc_vu_nhan_vien'] = [
        '#type' => 'textfield',
        '#default_value' => $result['chuc_vu_nhan_vien'],
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
      '#url' => Url::fromRoute('nhan_vien.danh_sach_nhan_vien'),
    ];

    $form['#theme'] = 'form_xoa_nhan_vien';
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
        ->delete('nhan_vien')
        ->condition('id', $this->id)
        ->execute();
      drupal_set_message(Constant::DELETE_SUCCESS);
      $form_state->setRedirect('nhan_vien.danh_sach_nhan_vien');
    } catch (\Exception $e) {
      drupal_set_message(Constant::DELETE_FAIL);
    }
  }
}