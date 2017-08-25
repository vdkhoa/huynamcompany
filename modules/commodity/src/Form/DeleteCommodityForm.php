<?php
namespace Drupal\commodity\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;

class DeleteCommodityForm extends ConfirmFormBase
{
  public $id;
  
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'delete';
  }
  
  public function getQuestion() {
    return t('Bạn có chắc chắn muốn xóa thông tin hàng hóa này không?');
  }
  public function getCancelUrl() {
    return new Url('commodity.list-commodity');
  }
 
  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Yes');
  }
  /**
   * {@inheritdoc}
   */
  public function getCancelText() {
    return t('Cancel');
  }
  
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $this->id = $id;
    return parent::buildForm($form, $form_state);
  }
  
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }
  
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $query = \Drupal::database();
    $query->delete('commodity')
          ->condition('id',$this->id)
          ->execute();
    drupal_set_message("Xóa Thông Tin Hàng Hóa Thành Công");
    $form_state->setRedirect('commodity.list-commodity');
  }
}

