<?php

namespace Drupal\usda_eauth;

/**
 * ZRoles utilities interface.
 */
interface zRolesUtilitiesInterface {

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
   * @param string $zRole
   *   The zRole to search for.
   *
   * @return mixed
   *   List of employees.
   */
  public static function getListByzRole(string $zRole);

  /**
   * Use strpos and substr to get the value of a token from an XML string.
   *
   * Used to get around problerms with non-breaking spaces.
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
