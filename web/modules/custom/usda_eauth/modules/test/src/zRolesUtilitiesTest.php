<?php

namespace Drupal\usda_eauth_test;

use Drupal\usda_eauth\zRolesUtilities;
use Drupal\usda_eauth\zRolesUtilitiesInterface;
use \SimpleXMLElement;

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

    $result = $data->{'GetAuthorizedUsersResponse'}->{'GetAuthorizedUsersResult'}->{'UserSummary'};;

    return $result;
  }

}
