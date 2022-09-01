<?php

namespace Drupal\usda_eauth;

use Drupal\Core\Session\AccountInterface;

/**
 * zRoles utilities interface.
 */
interface zRolesUtilitiesInterface {

  /**
   * Check if a user has admin access.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   User entity.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   Returns an access result.
   */
  public static function accessIfAdmin(AccountInterface $account);

  /**
   * Check if a user has awardee access.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   User entity.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   Returns an access result.
   */
  public static function accessIfAwardee(AccountInterface $account);

  /**
   * Get roles and scopes for an eAuthID.
   *
   * @param string $eAuthId
   *   The eAuth ID to load roles and scopes for.
   *
   * @return mixed
   *   List of roles.
   */
  public static function getUserAccessRolesAndScopes(string $eAuthId);

  /**
   * Get a list of employees with the specified zRole.
   *
   * @param String $zRole
   *   The zRole to search for.
   *
   * @return mixed
   *   List of employees.
   */
  public static function getListByzRole(string $zRole);

  /**
   * Use strpos and substr to get the value of a token from an XML string.
   *
   * Used to get around problerms with non-breaking spaces
   *
   * @param $xmlString
   * @param $token
   *
   * @return string
   */
  public static function getTokenValue($xmlString, $token);

  /**
   * Debug Only - function to print out the user information using print_r.
   *
   * @param $user
   *   User object.
   */
  public static function printUserInfo($user);
}
