<?php

namespace Drupal\custom\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class RegisterForm extends FormBase{
  public function BuildForm(array $form, FormStateInterface $form_state){

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Họ và tên'),
      '#required' => true,
      '#max'=>10,
    ];

    $form['phone_number'] = [
      '#type' => 'tel',
      '#title' => $this->t('SĐT'),
      '#required' => true,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email (EX : example@kyanon.digital)')
    ];

    $option = [
      ''=> $this->t('Chọn tuổi')
//      '10'=>$this->t('10'),
    ];
    for ($i=10;$i<=50;$i++){
      $option[$i]= $i;
    }
    $form['old'] = [
      '#type' => 'select',
      '#title' => $this->t('Độ tuổi'),
      '#options' => $option,
    ];

    $form['descreption'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Mô tả bản thân')
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Gửi'),
    ];

    return $form;
  }

  public function getFormId(){
    return 'register_form';
  }

  public function ValidateForm(array &$form, FormStateInterface $form_state){

    $name = $form_state->getValue('name');
    $phone = $form_state->getValue('phone_number');
    $old = $form_state->getValue('old');

    if($name == ''){
      $form_state->setErrorByName('name','Bạn chưa nhập tên');
    }

    if($phone == ''){
      $form_state->setErrorByName('phone','Bạn chưa nhập số điện thoại');
    }

    if($form_state->getValue('email') != null){
      $email = strstr($form_state->getValue('email'),'@');
      if($email != '@kyanon.digital'){
        $form_state->setErrorByName('email','Email không đúng định dạng @kyanon.digital');
      }
    }

    if($old != ''){
      if($old < 18){
        $form_state->setErrorByName('old','Bạn chưa đủ tuổi');
      }
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state){

    $old = $form_state->getValue('old')==''?null:$form_state->getValue('old');
    $email = $form_state->getValue('email')==''?null:$form_state->getValue('email');
    $descreption = $form_state->getValue('descreption')==''?null:$form_state->getValue('descreption');

    db_insert('register')
      ->fields(array(
        'name' => $form_state->getValue('name'),
        'phone' => $form_state->getValue('phone_number'),
        'email' => $email,
        'old' => $old,
        'descreption' => $descreption
      ))
      ->execute();

    drupal_set_message(t('Bạn đã đăng kí thành công'));

    $form_state->setRedirect('<front>');
  }
}
