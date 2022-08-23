<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;

class PodsFormBase extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'pods_form_base';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
