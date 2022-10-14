<?php

namespace Drupal\usda_eauth_test\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 *
 */
class TestzRoles extends FormBase {

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

    // Get the zRoles utility service.
    /** @var \Drupal\usda_eauth\zRolesUtilitiesInterface $zroles_util */
    $zroles_util = \Drupal::service('usda_eauth.zroles');

    /* Display the users that have Admin zRole */
    $role = 'CIG_App_Admin';

    $userInfo = $zroles_util->getListByzRole($role);
    print_r('<br>** ' . $role . ' role has ' . count($userInfo) . ' users. ** <br>');
    /* userinfo may be a single object or an array of userinfo objects, so check count */
    if (count($userInfo) == 1) {
      $zroles_util->printUserInfo($userInfo);
    }
    else {
      foreach ($userInfo as $user) {
        $zroles_util->printUserInfo($user);
      }
    }

    /* Display the users that have data steward zRole */
    $role = 'CIG_NSHDS';
    $userInfo = $zroles_util->getListByzRole($role);
    print_r('<br><br>** ' . $role . ' role has ' . count($userInfo) . ' users. ** <br>');
    if (count($userInfo) == 1) {
      $zroles_util->printUserInfo($userInfo);
    }
    else {
      foreach ($userInfo as $user) {
        $zroles_util->printUserInfo($user);
      }
    }

    /* Display the users that have CIG_NCDS zRole */
    $role = 'CIG_NCDS';
    $userInfo = $zroles_util->getListByzRole($role);
    print_r('<br><br>** ' . $role . ' role has ' . count($userInfo) . ' users. ** <br>');
    if (count($userInfo) == 1) {
      $zroles_util->printUserInfo($userInfo);
    }
    else {
      foreach ($userInfo as $user) {
        $zroles_util->printUserInfo($user);
      }
    }

    /* Display the users that have CIG_APT zRole */
    $role = 'CIG_APT';
    $userInfo = $zroles_util->getListByzRole($role);
    print_r('<br><br>** ' . $role . ' role has ' . count($userInfo) . ' users. ** <br>');
    if (count($userInfo) == 1) {
      $zroles_util->printUserInfo($userInfo);
    }
    else {
      foreach ($userInfo as $user) {
        $zroles_util->printUserInfo($user);
      }
    }

    /* Display the users that have CIG_NCDS zRole */
    $role = 'CIG_APA';
    $userInfo = $zroles_util->getListByzRole($role);
    print_r('<br><br>** ' . $role . ' role has ' . count($userInfo) . ' users. ** <br>');
    if (count($userInfo) == 1) {
      $zroles_util->printUserInfo($userInfo);
    }
    else {
      foreach ($userInfo as $user) {
        $zroles_util->printUserInfo($user);
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
