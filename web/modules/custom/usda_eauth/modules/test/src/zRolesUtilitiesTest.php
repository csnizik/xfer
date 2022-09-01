<?php

namespace Drupal\usda_eauth_test;

use Drupal\usda_eauth\zRolesUtilities;
use Drupal\usda_eauth\zRolesUtilitiesInterface;

/**
 * zRoles utilities for testing purposes.
 */
class zRolesUtilitiesTest extends zRolesUtilities implements zRolesUtilitiesInterface {

  /**
   * {@inheritdoc}
   */
  public static function getUserAccessRolesAndScopes(string $eAuthId){
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public static function getListByzRole(String $zRole) {
    return [];
  }

}
