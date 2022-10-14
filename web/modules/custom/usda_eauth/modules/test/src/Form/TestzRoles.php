<?php

namespace Drupal\usda_eauth_test\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\usda_eauth\ZRolesUtilitiesInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Set session values to mock an awardee user.
 */
class TestzRoles extends FormBase {

  /**
   * The zRoles utilities service.
   *
   * @var \Drupal\usda_eauth\ZRolesUtilitiesInterface
   */
  protected $zRoles;

  /**
   * Constructs a new TestzRoles instance.
   *
   * @param \Drupal\usda_eauth\ZRolesUtilitiesInterface $zRoles
   *   The session.
   */
  public function __construct(ZRolesUtilitiesInterface $zRoles) {
    $this->zRoles = $zRoles;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('usda_eauth.zroles'),
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

    /* Display the users that have Admin zRole */
    $role = 'CIG_App_Admin';

    $userInfo = $this->zRoles->getListByzRole($role);
    print_r('<br>** ' . $role . ' role has ' . count($userInfo) . ' users. ** <br>');
    /* userinfo may be a single object or an array of userinfo objects, so check count */
    if (count($userInfo) == 1) {
      $this->zRoles->printUserInfo($userInfo);
    }
    else {
      foreach ($userInfo as $user) {
        $this->zRoles->printUserInfo($user);
      }
    }

    /* Display the users that have data steward zRole */
    $role = 'CIG_NSHDS';
    $userInfo = $this->zRoles->getListByzRole($role);
    print_r('<br><br>** ' . $role . ' role has ' . count($userInfo) . ' users. ** <br>');
    if (count($userInfo) == 1) {
      $this->zRoles->printUserInfo($userInfo);
    }
    else {
      foreach ($userInfo as $user) {
        $this->zRoles->printUserInfo($user);
      }
    }

    /* Display the users that have CIG_NCDS zRole */
    $role = 'CIG_NCDS';
    $userInfo = $this->zRoles->getListByzRole($role);
    print_r('<br><br>** ' . $role . ' role has ' . count($userInfo) . ' users. ** <br>');
    if (count($userInfo) == 1) {
      $this->zRoles->printUserInfo($userInfo);
    }
    else {
      foreach ($userInfo as $user) {
        $this->zRoles->printUserInfo($user);
      }
    }

    /* Display the users that have CIG_APT zRole */
    $role = 'CIG_APT';
    $userInfo = $this->zRoles->getListByzRole($role);
    print_r('<br><br>** ' . $role . ' role has ' . count($userInfo) . ' users. ** <br>');
    if (count($userInfo) == 1) {
      $this->zRoles->printUserInfo($userInfo);
    }
    else {
      foreach ($userInfo as $user) {
        $this->zRoles->printUserInfo($user);
      }
    }

    /* Display the users that have CIG_NCDS zRole */
    $role = 'CIG_APA';
    $userInfo = $this->zRoles->getListByzRole($role);
    print_r('<br><br>** ' . $role . ' role has ' . count($userInfo) . ' users. ** <br>');
    if (count($userInfo) == 1) {
      $this->zRoles->printUserInfo($userInfo);
    }
    else {
      foreach ($userInfo as $user) {
        $this->zRoles->printUserInfo($user);
      }
    }

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
    return 'test_zroles';
  }

}
