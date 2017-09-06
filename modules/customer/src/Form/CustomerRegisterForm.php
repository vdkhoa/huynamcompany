<?php

namespace Drupal\customer\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Database\IntegrityConstraintViolationException;

class CustomerRegisterForm extends FormBase
{

    /**
     * (non-PHPdoc)
     * @see \Drupal\Core\Form\FormInterface::getFormId()
     */
    public function getFormId()
    {
        return 'register_form';
    }

    /**
     * (non-PHPdoc)
     * @see \Drupal\Core\Form\FormInterface::buildForm()
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['customer_code'] = array(
            '#type' => 'textfield',
            '#maxlength' => 10
        );

        $form['customer_name'] = array(
            '#type' => 'textfield',
            '#maxlength' => 50
        );

        $form['customer_address'] = array(
            '#type' => 'textarea',
            '#maxlength' => 255
        );

        $form['customer_represent'] = array(
            '#type' => 'textfield',
            '#maxlength' => 50
        );

        $form['customer_mobiphone'] = array(
            '#type' => 'textfield',
            '#maxlength' => 11
        );

        $debt = array(
            0 => 'Công Nợ Ngày',
            1 => 'Công Nợ Tuần',
            2 => 'Công Nợ Tháng',
            3 => 'Công Nợ Khác'
        );

        $form['customer_debt'] = array(
            '#type' => 'radios',
            '#options' => $debt,
            '#default_value' => 0
        );

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => 'save'
        ];

        $form['#theme'] = 'customer_register_form';
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
        $customer = $form_state->getValues();
        $customer_code = $customer['customer_code'];
        if ($customer_code === '') {
            $form_state->setErrorByName('customer_code', t('Xin hãy nhập mã khách hàng.'));
        } else if ($customer['customer_name'] === '') {
            $form_state->setErrorByName('customer_name', t('Xin hãy nhập tên khách hàng.'));
        } else if ($this->getCustomerCode($customer_code) !== FALSE) {
            $form_state->setErrorByName('customer_code', t('Mã khách hàng ' . $customer_code . ' đã tồn tại.'));
        }

        parent::validateForm($form, $form_state);
    }

    /**
     * (non-PHPdoc)
     * @see \Drupal\Core\Form\FormInterface::submitForm()
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        try {
            $field = $form_state->getValues();
            $customer_code = $field['customer_code'];
            $customer_name = $field['customer_name'];
            $customer_address = $field['customer_address'];
            $customer_birthday = $field['customer_represent'];
            $customer_mobiphone = $field['customer_mobiphone'];
            $customer_debt = $field['customer_debt'];
            $field = array(
                'customer_code' => $customer_code,
                'customer_name' => $customer_name,
                'customer_address' => $customer_address,
                'customer_represent' => $customer_birthday,
                'customer_mobiphone' => $customer_mobiphone,
                'customer_debt' => $customer_debt
            );
            $query = \Drupal::database();
            $query->insert('customer')->fields($field)->execute();
            drupal_set_message(t("Đăng Ký Thành Công"));
        } catch (IntegrityConstraintViolationException $e) {
            drupal_set_message(t("Đăng Ký Thất Bại"));
        } catch (Exception $e) {
            drupal_set_message(t("Đăng Ký Thất Bại"));
        }
    }

    /**
     *
     * @param unknown $customer_code
     * @return \Drupal\Core\Database\A
     */
    public function getCustomerCode($customer_code)
    {
        try {
            $query = db_select('customer', 'cus');
            $query->condition('customer_code', $customer_code);
            $query->addField('cus', 'customer_code');
            return $query->execute()->fetchField();
        } catch (Exception $e) {
            drupal_set_message(t("Lỗi hệ thống."));
        }
    }
}

