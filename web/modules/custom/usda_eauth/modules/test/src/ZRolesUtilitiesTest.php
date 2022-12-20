<?php

namespace Drupal\usda_eauth_test;

use Drupal\Core\Site\Settings;
use Drupal\usda_eauth\ZRolesUtilities;
use Drupal\usda_eauth\ZRolesUtilitiesInterface;

/**
 * ZRoles utilities for testing purposes.
 */
class ZRolesUtilitiesTest extends ZRolesUtilities implements ZRolesUtilitiesInterface {

  /**
   * {@inheritdoc}
   */
  public static function getUserAccessRolesAndScopes(string $eAuthId) {

    // If usda_eauth_test_mock is not set to FALSE in settings.php, then mock
    // the response. Otherwise, delegate to the parent method.
    if (!Settings::get('usda_eauth_test_mock', TRUE)) {
      return parent::getUserAccessRolesAndScopes($eAuthId);
    }

    $test_roles['28200310160021007137'] = '<ApplicationRoleEnumeration>CIG_App_Admin</ApplicationRoleEnumeration>';
    $test_roles['28200711150011206144332'] = '<ApplicationRoleEnumeration>CIG_NSHDS</ApplicationRoleEnumeration>';
    $test_roles['2'] = '<ApplicationRoleEnumeration>CIG_APT</ApplicationRoleEnumeration>';
    $test_roles['8'] = '<ApplicationRoleEnumeration>CIG_NSHDS</ApplicationRoleEnumeration>';
    $test_roles['28'] = '<ApplicationRoleEnumeration>CIG_NSHDSA</ApplicationRoleEnumeration>';

    return $test_roles[$eAuthId];
  }

  /**
   * {@inheritdoc}
   */
  public static function getListByzRole(String $zRole) {

    // If usda_eauth_test_mock is not set to FALSE in settings.php, then mock
    // the response. Otherwise, delegate to the parent method.
    if (!Settings::get('usda_eauth_test_mock', TRUE)) {
      return parent::getListByzRole($zRole);
    }

    $response = '<soap:Body>
      <GetAuthorizedUsersResponse xmlns="http://zRoles.sc.egov.usda.gov">
         <GetAuthorizedUsersResult>
            <UserSummary>
               <UsdaeAuthenticationId>28200711150011206144332</UsdaeAuthenticationId>
               <FirstName>WILLIAM</FirstName>
               <LastName>MAY</LastName>
               <AuthoritativeId>5202</AuthoritativeId>
               <TypeCode>E</TypeCode>
               <SubtypeCode/>
               <EmailAddress>William.May.usda.gov</EmailAddress>
               <PhoneNUmber/>
               <OfficeID/>
               <TypeDisplay>Employee</TypeDisplay>
               <HomeAdminStateId>16</HomeAdminStateId>
               <HomeAdminStateDisplay>Iowa</HomeAdminStateDisplay>
            </UserSummary>
            <UserSummary>
               <UsdaeAuthenticationId>2</UsdaeAuthenticationId>
               <FirstName>EVAN</FirstName>
               <LastName>KELLEY</LastName>
               <AuthoritativeId>3</AuthoritativeId>
               <TypeCode>E</TypeCode>
               <SubtypeCode/>
               <EmailAddress>Evan.Kelley.usda.gov</EmailAddress>
               <PhoneNUmber/>
               <OfficeID/>
               <TypeDisplay>Employee</TypeDisplay>
               <HomeAdminStateId>16</HomeAdminStateId>
               <HomeAdminStateDisplay>Iowa</HomeAdminStateDisplay>
            </UserSummary>
            <UserSummary>
               <UsdaeAuthenticationId>8</UsdaeAuthenticationId>
               <FirstName>ZANE</FirstName>
               <LastName>BELKHAYAT</LastName>
               <AuthoritativeId>37011</AuthoritativeId>
               <TypeCode>E</TypeCode>
               <SubtypeCode/>
               <EmailAddress>Zane.Belkhayat.usda.gov</EmailAddress>
               <PhoneNUmber/>
               <OfficeID/>
               <TypeDisplay>Employee</TypeDisplay>
               <HomeAdminStateId>16</HomeAdminStateId>
               <HomeAdminStateDisplay>Iowa</HomeAdminStateDisplay>
            </UserSummary>
            <UserSummary>
               <UsdaeAuthenticationId>28</UsdaeAuthenticationId>
               <FirstName>JOHN</FirstName>
               <LastName>HAINES</LastName>
               <AuthoritativeId>37011</AuthoritativeId>
               <TypeCode>E</TypeCode>
               <SubtypeCode/>
               <EmailAddress>John.Haines.usda.gov</EmailAddress>
               <PhoneNUmber/>
               <OfficeID/>
               <TypeDisplay>Employee</TypeDisplay>
               <HomeAdminStateId>16</HomeAdminStateId>
               <HomeAdminStateDisplay>Iowa</HomeAdminStateDisplay>
            </UserSummary>
         </GetAuthorizedUsersResult>
      </GetAuthorizedUsersResponse>
   </soap:Body>';

    $data = simplexml_load_string($response);

    $data = json_decode(json_encode($data));

    $result = $data->{'GetAuthorizedUsersResponse'}->{'GetAuthorizedUsersResult'}->{'UserSummary'};
    ;

    return $result;
  }

}
