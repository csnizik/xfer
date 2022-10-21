<?php

namespace Drupal\usda_eauth_test\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Set session values to mock an awardee user.
 */
class TestSetAwardee2 extends FormBase {

  /**
   * The session.
   *
   * @var \Symfony\Component\HttpFoundation\Session\Session
   */
  protected $session;

  /**
   * Constructs a new TestSetAwardee2 instance.
   *
   * @param \Symfony\Component\HttpFoundation\Session\Session $session
   *   The session.
   */
  public function __construct(Session $session) {
    $this->session = $session;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('session'),
    );
  }

  /**
   * {@inheritdoc}
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

    $eAuthId = '8';
    $email = 'Zane.Belkhayat.usda.gov';
    $firstName = 'ZANE';
    $lastName = 'BELKHAYAT';
    $roleId = '5202';
    $roleName = 'NRCS Soil Health Data Steward';
    $roleEnum = 'CIG_NSHDS';
    $roleDisplay = 'NRCS Soil Health Data Steward';

    /*Store the user info in the session */
    $this->session->set('eAuthId', $eAuthId);
    $this->session->set('EmailAddress', $email);
    $this->session->set('FirstName', $firstName);
    $this->session->set('LastName', $lastName);
    $this->session->set('ApplicationRoleId', $roleId);
    $this->session->set('ApplicationRoleName', $roleName);
    $this->session->set('ApplicationRoleEnumeration', $roleEnum);
    $this->session->set('ApplicationRoleDisplay', $roleDisplay);

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
