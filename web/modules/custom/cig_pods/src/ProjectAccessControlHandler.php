<?php

namespace Drupal\cig_pods;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\entity\UncacheableEntityAccessControlHandler;
use Drupal\views\Plugin\views\ViewsHandlerInterface;

class ProjectAccessControlHandler extends UncacheableEntityAccessControlHandler {

  /**
   * Helper method for getting the current session zRole.
   *
   * @return string
   *   The zRole.
   */
  protected static function getZRole() {
    $session = \Drupal::request()->getSession();
    return $session->get('ApplicationRoleEnumeration');
  }

  /**
   * Helper method for getting the current session eAuth ID.
   *
   * @return string
   *   The zRole.
   */
  protected static function getEAuthId() {
    $session = \Drupal::request()->getSession();
    return $session->get('eAuthId');
  }

  /**
   * {@inheritdoc}
   *
   * Link the activities to the permissions. checkAccess is called with the
   * $operation as defined in the routing.yml file.
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    // First delegate to the parent method.
    $result = parent::checkAccess($entity, $operation, $account);

    // Only proceed if access was not determined. We will use eAuth ID + zRole
    // to check if the user should have access.
    if (!$result->isNeutral()) {
      return $result;
    }

    // Get the user's eAuthID and zRole.
    $eauth_id = $this->getEAuthId();
    $zrole = $this->getZRole();

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

    // First delegate to the parent method.
    $result = parent::checkCreateAccess($account, $context, $entity_bundle);

    // Only proceed if access was not determined. We will use zRole to check if
    // the user should have access.
    if (!$result->isNeutral()) {
      return $result;
    }

    // Get the user's zRole.
    $zrole = $this->getZRole();

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
   * Query to find projects that an eAuth ID has access to.
   *
   * @param $eauth_id
   *   The eAuth ID.
   *
   * @return array
   *   Returns an array of projectasset IDs.
   */
  public static function eAuthIdProjects($eauth_id) {

    // Query the asset table
    $query = \Drupal::database()->select('asset', 'a');

    // Select the asset entity IDs.
    $query->addField('a', 'id');

    // Filter to project assets.
    $query->condition('a.type', 'project');

    // Join the asset__project table to find contacts that reference the project.
    $query->join('asset__project', 'ap', "a.id = ap.project_target_id AND ap.bundle = 'contact' AND ap.deleted != 1");

    // Join the asset__eauth_id table, which assigns eAuth IDs to contact assets.
    $query->join('asset__eauth_id', 'aei', "ap.entity_id = aei.entity_id AND aei.deleted != 1");

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

    // If the asset type is "project", then we need a slightly different query.
    // Delegate to the eAuthIdProjects() method instead.
    if ($asset_type == 'project') {
      return ProjectAccessControlHandler::eAuthIdProjects($eauth_id);
    }

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

  /**
   * Helper method for Views argument query modification.
   *
   * This is used by the pods_project_access view argument plugin.
   *
   * @param \Drupal\views\Plugin\views\ViewsHandlerInterface $handler
   *   The Views handler.
   */
  public static function viewsArgumentQueryAlter(ViewsHandlerInterface $handler) {

    // Get the user's eAuthID and zRole.
    $eauth_id = ProjectAccessControlHandler::getEAuthId();
    $zrole = ProjectAccessControlHandler::getZRole();

    // If this is an admin, don't apply any additional filters.
    if (in_array($zrole, ['CIG_App_Admin', 'CIG_APA'])) {
      return;
    }

    // If this is an awardee, filter out assets that they do not have access to.
    elseif (in_array($zrole, ['CIG_NSHDS', 'CIG_APT'])) {

      // Try to determine the asset type from arguments.
      // The pods_asset_er View uses asset type as the first argument, so we
      // look for a non-numeric argument there. If one is not found, we default
      // to NULL so that no additional asset type filtering is performed. This
      // is fine in most cases because downstream code will filter it (eg: the
      // pods_asset_lists View).
      // The only time this could cause brittleness is when you are trying to
      // get project assets, which require a slightly different query JOIN.
      // @see ProjectAccessControlHandler::eAuthIdAssets()
      $asset_type = NULL;
      if (!empty($handler->view->args[0]) && !is_numeric($handler->view->args[0])) {
        $asset_type = $handler->view->args[0];
      }

      // First query for a list of asset IDs that the awardee has access to
      // (based on their eAuth ID), then use this list to filter the current
      // View.
      // We do this in two separate queries to keep this argument handler's
      // query modifications very simple. It only needs the condition:
      // "WHERE asset.id IN (:asset_ids)", rather than add a bunch of extra
      // JOINs (and duplicate the logic in the helper method).
      $asset_ids = ProjectAccessControlHandler::eAuthIdAssets($eauth_id, $asset_type);

      // If there are no asset IDs, add 0 to ensure the array is not empty.
      if (empty($asset_ids)) {
        $asset_ids[] = 0;
      }

      // Filter to only include assets with those IDs.
      $handler->ensureMyTable();
      $handler->query->addWhere(0, "$handler->tableAlias.id", $asset_ids, 'IN');
    }
  }

}
