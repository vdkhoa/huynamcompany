<?php

namespace Drupal\commodity\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Url;

class DeleteCommodityForm extends FormBase {
 public $id;
 
 /**
  * @ERROR!!!
  */
 public function getFormId(){
  return 'delete';
 }
 
 /**
  * @ERROR!!!
  */
 public function buildForm(array $form, FormStateInterface $form_state, $id = NULL){
  $this->id = $id;
  $form['submit'] = [
    '#type' => 'submit',
    '#value' => 'Delete' 
  ];
  
  $form['cancel'] = [
    '#title' => $this->t('Cancel'),
    '#type' => 'link',
    '#attributes' => array(
      'class' => array(
        'btn btn-primary' 
      ) 
    ),
    '#url' => Url::fromRoute('commodity.list-commodity') 
  ];
  
  $form['#theme'] = 'delete_commodity_form';
  $form['#attached'] = array(
    'library' => array(
      'commodity/commodity.style' 
    ) 
  );
  
  return $form;
 }
 
 /**
  * @ERROR!!!
  */
 public function validateForm(array &$form, FormStateInterface $form_state){
  parent::validateForm($form,$form_state);
 }
 
 /**
  * @ERROR!!!
  */
 public function submitForm(array &$form, FormStateInterface $form_state){
  $query = \Drupal::database();
  $query->delete('commodity')->condition('id',$this->id)->execute();
  drupal_set_message("Xóa Thông Tin Hàng Hóa Thành Công");
  $form_state->setRedirect('commodity.list-commodity');
 }
}

