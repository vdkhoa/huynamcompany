<?php

namespace Drupal\customer\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Url;

class CustomerDeleteForm extends FormBase
{
    public $id;

    /**
     * (non-PHPdoc)
     * @see \Drupal\Core\Form\FormInterface::getFormId()
     */
    public function getFormId()
    {
        return 'delete_form';
    }

    /**
     *
     * (non-PHPdoc)
     * @see \Drupal\Core\Form\FormInterface::buildForm()
     */
    public function buildForm(array $form, FormStateInterface $form_state, $id = NULL)
    {
        $this->id = $id;
        $query = \Drupal::database();
        $query->select('customer', 'cus');
        $query->condition('id', $this->id);
        $query->fields('cus');

        $result = $query->execute()->fetchAssoc();
        if ($result) {
            $form['customer_code'] = array(
                '#type' => 'textfield',
                '#default_value' => $result['customer_code'],
                '#disabled' => TRUE
            );

            $form['customer_name'] = array(
                '#type' => 'textfield',
                '#default_value' => $result['customer_name'],
                '#disabled' => TRUE
            );

            $form['customer_address'] = array(
                '#type' => 'textfield',
                '#default_value' => $result['customer_address'],
                '#disabled' => TRUE
            );

            $form['customer_represent'] = array(
                '#type' => 'textfield',
                '#default_value' => $result['customer_represent'],
                '#disabled' => TRUE
            );

            $form['customer_mobiphone'] = array(
                '#type' => 'textfield',
                '#default_value' => $result['customer_mobiphone'],
                '#disabled' => TRUE
            );

            switch ($result['customer_debt']) {
                case 0:
                    $debt = 'Công Nợ Ngày';
                    break;
                case 1:
                    $debt = 'Công Nợ Tuần';
                    break;
                case 2:
                    $debt = 'Công Nợ Tháng';
                    break;
                case 3:
                    $debt = 'Công Nợ Khác';
                    break;
                default:
                    $debt = 'Công Nợ Khác';
                    break;
            }

            $form['customer_debt'] = array(
                '#type' => 'textfield',
                '#default_value' => $debt,
                '#disabled' => TRUE
            );
        }

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => 'Delete'
        ];

        $form['cancel'] = [
            '#type' => 'link',
            '#title' => $this->t('Cancel'),
            '#attributes' => array(
                'class' => array(
                    'btn btn-primary'
                )
            ),
            '#url' => Url::fromRoute('customer.list')
        ];

        $form['#theme'] = 'customer_delete_form';
        $form['#attached'] = array(
            'library' => array(
                'customer/customer.style'
            )
        );

        return $form;
    }

    /**
     * (non-PHPdoc)
     * @see \Drupal\Core\Form\FormBase::validateForm()
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        parent::validateForm($form, $form_state);
    }

    /**
     * (non-PHPdoc)
     * @see \Drupal\Core\Form\FormInterface::submitForm()
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        try {
            $query = \Drupal::database();
            $query->delete('customer')->condition('id', $this->id)->execute();
            drupal_set_message("Xóa Thông Tin Khách Hàng Thành Công");
            $form_state->setRedirect('customer.list');
        } catch (\Exception $e) {
            drupal_set_message("Xóa Thông Tin Thất Bại.");
        }
    }
}

