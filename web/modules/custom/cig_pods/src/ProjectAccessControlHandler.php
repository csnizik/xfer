<?php

namespace Drupal\cig_pods;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\entity\UncacheableEntityAccessControlHandler;

class ProjectAccessControlHandler extends UncacheableEntityAccessControlHandler {

  /**
   * {@inheritdoc}
   *
   * Link the activities to the permissions. checkAccess is called with the
   * $operation as defined in the routing.yml file.
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    // Assume forbidden.
    $result = AccessResult::forbidden();

    // Get the user's eAuthID and zRole.
    $session = \Drupal::request()->getSession();
    $eauth_id = $session->get('eAuthId');
    $zrole = $session->get('ApplicationRoleEnumeration');

    // Admins can create any asset.
    if (in_array($zrole, ['CIG_App_Admin', 'CIG_APA'])) {
      $result = AccessResult::allowed();
    }

    // Awardees only have access to assets in a project that their eAuth ID
    // is associated with.
    elseif (in_array($zrole, ['CIG_NSHDS', 'CIG_APT'])) {
      if (in_array($entity->id(), $this->eAuthIdAssets($eauth_id, $entity->bundle()))) {
        $result = AccessResult::allowed();
      }
    }

    // Ensure that access is evaluated again when the entity changes.
    return $result->addCacheableDependency($entity);
  }

  /**
   * {@inheritdoc}
   *
   * Separate from the checkAccess because the entity does not yet exist, it
   * will be created during the 'add' process.
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {

    // Assume forbidden.
    $result = AccessResult::forbidden();

    // Get the user's zRole.
    $session = \Drupal::request()->getSession();
    $zrole = $session->get('ApplicationRoleEnumeration');

    // Admins can create any asset.
    if (in_array($zrole, ['CIG_App_Admin', 'CIG_APA'])) {
      $result = AccessResult::allowed();
    }

    // Awardees can only create certain asset types.
    elseif (in_array($zrole, ['CIG_NSHDS', 'CIG_APT'])) {
      $allowed_types = [
        'producer',
        'soil_health_management_unit',
        'soil_health_sample',
        'field_assessment',
        'range_assessment',
        'pasture_assessment',
        'pasture_health_assessment',
        'lab_result',
        'lab_testing_method',
        'operation',
        'irrigation',
        'input',
      ];
      if (in_array($entity_bundle, $allowed_types)) {
        $result = AccessResult::allowed();
      }
    }

    // Return the result.
    return $result;
  }

  /**
   * Query to find assets that an eAuth ID has access to.
   *
   * @param $eauth_id
   *   The eAuth ID.
   * @param string|null $asset_type
   *   The asset type to filter by.
   *
   * @return array
   *   Returns an array of asset IDs.
   */
  public static function eAuthIdAssets($eauth_id, string $asset_type = NULL) {

    // Query the asset__project table, where each row is a relationship between
    // an asset and a project.
    $query = \Drupal::database()->select('asset__project', 'ap');

    // Select the asset entity IDs.
    $query->addField('ap', 'entity_id', 'id');

    // Exclude deleted fields.
    $query->condition('ap.deleted', 1, '!=');

    // Optionally filter by asset type.
    if (!empty($asset_type)) {
      $query->condition('ap.bundle', $asset_type);
    }

    // Join the asset__project table again, this time to get contacts that
    // reference the same project.
    $query->join('asset__project', 'apc', "ap.project_target_id = apc.project_target_id AND apc.bundle = 'contact' AND apc.deleted != 1");

    // Join the asset__eauth_id table, which assigns eAuth IDs to contact assets.
    $query->join('asset__eauth_id', 'aei', "apc.entity_id = aei.entity_id AND aei.deleted != 1");

    // Filter by the eAuth ID field on the contact asset.
    $query->condition('aei.eauth_id_value', $eauth_id);

    // Execute the query.
    $result = $query->execute();

    // Return an array of unique asset IDs.
    $asset_ids = [];
    foreach ($result as $row) {
      if (!empty($row->id)) {
        $asset_ids[] = $row->id;
      }
    }
    return $asset_ids;
  }

}
