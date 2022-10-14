<?php

namespace Drupal\usda_eauth_test\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 *
 */
class TestSetAwardee3 extends FormBase {

  /**
   *
   */
  public function buildForm(array $form, FormStateInterface $form_state, $options = NULL) {

    $form['actions'] = [
      '#type' =>
      'actions',
    ];

    $form['actions']['cancel'] = [
      '#type' =>
      'submit',
      '#value' =>
      $this->t('Cancel'),
    ];

    $eAuthId = '28';
    $email = 'John.Haines.usda.gov';
    $firstName = 'JOHN';
    $lastName = 'HAINES';
    $roleId = '5202';
    $roleName = 'NRCS Soil Health Data Steward';
    $roleEnum = 'CIG_NSHDS';
    $roleDisplay = 'NRCS Soil Health Data Steward';

    /*Store the user info in the session */
    $session = \Drupal::request()->getSession();
    $session->set('eAuthId', $eAuthId);
    $session->set('EmailAddress', $email);
    $session->set('FirstName', $firstName);
    $session->set('LastName', $lastName);
    $session->set('ApplicationRoleId', $roleId);
    $session->set('ApplicationRoleName', $roleName);
    $session->set('ApplicationRoleEnumeration', $roleEnum);
    $session->set('ApplicationRoleDisplay', $roleDisplay);

    // Redirect to the PODS dashboard.
    (new RedirectResponse('/pods'))->send();

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    (new RedirectResponse('/user/login'))->send();
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'test_set_awardee';
  }

}
