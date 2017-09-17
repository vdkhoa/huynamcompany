<?php
/**
 * Created by PhpStorm.
 * ma_nhan_vien: dell
 * Date: 9/17/2017
 * Time: 23:44
 */

namespace Drupal\huynam\Form;


use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class FormBanLe extends FormBase {
  public function getFormId() {
    return 'frm_ban_le';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $date_format = 'd-m-Y';
    $so_hoa_don = $this->getMaxSoHoaDon();
    $ds_khach_hang = $this->getDanhSachKhachHang();

    $form['so_hoa_don'] = [
      '#type' => 'hidden',
      '#required' => TRUE,
      '#default_value' => $so_hoa_don + 1,
    ];

    $form['ngay_ban'] = [
      '#type' => 'datetime',
      '#date_date_format' => $date_format,
      '#required' => TRUE,
      '#default_value' => DrupalDateTime::createFromTimestamp(time()),
    ];

    $form['ma_khach_hang'] = [
      '#type' => 'select',
      '#required' => TRUE,
      '#options' => $ds_khach_hang,
    ];

    $form['ma_hang_hoa_1'] = [
      '#type' => 'textfield',
      '#title' => t('Loại Đá:'),
      '#disabled' => TRUE,
      '#default_value' => 'MI',
    ];

    $form['so_luong_1'] = [
      '#type' => 'textfield',
      '#title' => t('Số Lượng:'),
    ];

    $form['don_gia_1'] = [
      '#type' => 'textfield',
      '#title' => t('Đơn Giá:'),
    ];

    $form['ma_hang_hoa_2'] = [
      '#type' => 'textfield',
      '#default_value' => 'BIA',
      '#disabled' => TRUE,
    ];

    $form['so_luong_2'] = [
      '#type' => 'textfield',
    ];

    $form['don_gia_2'] = [
      '#type' => 'textfield',
    ];

    $form['ma_hang_hoa_3'] = [
      '#type' => 'textfield',
      '#default_value' => 'XAY',
      '#disabled' => TRUE,
    ];

    $form['so_luong_3'] = [
      '#type' => 'textfield',
    ];

    $form['don_gia_3'] = [
      '#type' => 'textfield',
    ];

    $form['ma_hang_hoa_4'] = [
      '#type' => 'textfield',
      '#default_value' => 'BE CAT',
      '#disabled' => TRUE,
    ];

    $form['so_luong_4'] = [
      '#type' => 'textfield',
    ];

    $form['don_gia_4'] = [
      '#type' => 'textfield',
    ];

    $form['ma_hang_hoa_5'] = [
      '#type' => 'textfield',
      '#default_value' => 'RUOU',
      '#disabled' => TRUE,
    ];

    $form['so_luong_5'] = [
      '#type' => 'textfield',
    ];

    $form['don_gia_5'] = [
      '#type' => 'textfield',
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'save',
    ];

    $form['#theme'] = 'form_ban_le';
    $form['#attached'] = [
      'library' => [
        'huynam/huynam.style',
      ],
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    try {
      $values = [];
      $fields = $form_state->getValues();
      $so_hoa_don = $fields['so_hoa_don'];
      $ngay_ban = $fields['ngay_ban']->format('Y-m-d G:i:s');
      $ma_khach_hang = $fields['ma_khach_hang'];
      $ma_nhan_vien = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
      $create_date = date('Y-m-d G:i:s');

      for ($idx = 1; $idx <= 5; $idx++) {
        if ((!empty($fields["so_luong_$idx"])) && (!empty($fields["don_gia_$idx"]))) {
          $values[] = [
            'so_hoa_don' => $so_hoa_don,
            'ngay_ban' => $ngay_ban,
            'ma_hang_hoa' => $fields["ma_hang_hoa_$idx"],
            'so_luong' => $fields["so_luong_$idx"],
            'don_gia' => $fields["don_gia_$idx"],
            'ma_khach_hang' => $ma_khach_hang,
            'ma_nhan_vien' => $ma_nhan_vien->get('name')->value,
            'create_date' => $create_date,
          ];
        }
      }

      $query = \Drupal::database()->insert('ban_hang')->fields([
        'so_hoa_don',
        'ngay_ban',
        'ma_hang_hoa',
        'so_luong',
        'don_gia',
        'ma_khach_hang',
        'ma_nhan_vien',
        'create_date',
      ]);
      foreach ($values as $record) {
        $query->values($record);
      }
      $query->execute();
      drupal_set_message("Đã lưu hóa đơn bán hàng.");
    } catch (\Exception $e) {
      drupal_set_message("Lưu hóa đơn bán hàng thất bại.");
    }
  }

  public function getMaxSoHoaDon() {
    $query = \Drupal::database()->select('ban_hang');
    $query->addExpression('MAX(so_hoa_don)');
    $max_so_hoa_don = $query->execute()->fetchField();
    return $max_so_hoa_don === NULL ? 0 : intval($max_so_hoa_don);
  }

  public function getDanhSachKhachHang() {
    $query = \Drupal::database()->select('khach_hang', 'kh');
    $query->fields('kh', [
      'ma_khach_hang',
      'ten_khach_hang',
    ]);
    $results = $query->execute()->fetchAll();
    $ds_khach_hang = [];
    foreach ($results as $item) {
      $ds_khach_hang[$item->ma_khach_hang] = t($item->ten_khach_hang);
    }
    return $ds_khach_hang;
  }
}