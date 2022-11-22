<?php

namespace Drupal\usda_eauth_test\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
// Used to access Settings.
use Drupal\Core\Site\Settings;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Shows eAuth/zRole session values for debugging.
 */
class TestSessionValues extends FormBase {

  /**
   * The session.
   *
   * @var \Symfony\Component\HttpFoundation\Session\Session
   */
  protected $session;

  /**
   * Constructs a new TestSessionValues instance.
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

    /* Display the the current users zrole info from the session variables */
    $sessionEA = $this->session->get('eAuthId');
    $sessionEM = $this->session->get('EmailAddress');
    $sessionFN = $this->session->get('FirstName');
    $sessionLN = $this->session->get('LastName');
    $sessionID = $this->session->get('ApplicationRoleId');
    $sessionRN = $this->session->get('ApplicationRoleName');
    $sessionRE = $this->session->get('ApplicationRoleEnumeration');
    $sessionRD = $this->session->get('ApplicationRoleDisplay');

    print_r("Current user info:<br>
      eAuthId = $sessionEA<br>
      Email = $sessionEM<br>
      First Name = $sessionFN<br>
      Last Name = $sessionLN<br>
      Role ID  = $sessionID<br>
      Role Name  = $sessionRN<br>
      Role Enum = $sessionRE<br>
      Role Display Name = $sessionRD<br>");

    $endpoint = Settings::get('zroles_url', 'd1');
    $nrtApplicationId = Settings::get('nrtApplicationId', 'd2');
    $wsSecuredToken = Settings::get('wsSecuredToken', 'd3');

    $eAuthTokenUrl = Settings::get('eAuthBaseUrl', 'd5') . '/token';
    $client_id = Settings::get('client_id', 'd6');
    $client_secret = Settings::get('client_secret', 'd7');

    print_r("<br> Current settings info: <br>
      endpoint = $endpoint<br>
      nrtApplicationId = $nrtApplicationId<br>
      wsSecuredToken = $wsSecuredToken<br>
      eAuthTokenUrl = $eAuthTokenUrl<br>
      client_id = $client_id<br>
      client_secret = $client_secret<br>");

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
    return 'test_session_values';
  }

}
