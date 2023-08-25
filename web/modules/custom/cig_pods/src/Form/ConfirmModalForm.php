<?php

namespace Drupal\cig_pods\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ConfirmModalForm extends FormBase {

  public function getFormId() {
    return 'confirm_modal_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['markup'] = [
      '#type' => 'markup',
      '#markup' => $this->t('<br><div >Any unsaved changes will be lost.</div>'),
    ];

    
    $form['actions']['no'] = [
      '#type' => 'button',
      '#value' => $this->t('No, go back'),
      '#limit_validation_errors' => [],
      '#attributes' => ['id' => ['edit-cancel'], 'class'=> ['button popup-close-button']],
    ];

    $form['actions']['yes'] = [
      '#type' => 'submit',
      '#value' => $this->t('Yes, leave without saving'),
      '#attributes' => ['id' => ['edit-save'], 'class'=> ['button']],
    ];



    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    
    $form_state->setRedirect('cig_pods.dashboard');
  }

}