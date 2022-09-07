<?php

namespace Drupal\cig_pods\Plugin\views\argument;

use Drupal\cig_pods\ProjectAccessControlHandler;
use Drupal\views\Plugin\views\argument\ArgumentPluginBase;

/**
 * Filter out assets that are not part of a project that the user is assigned to.
 *
 * @ViewsArgument("pods_project_access")
 */
class PodsProjectAccess extends ArgumentPluginBase {

  /**
   * {@inheritdoc}
   */
  public function query($group_by = FALSE) {

    // Get the user's eAuthID and zRole.
    $session = \Drupal::request()->getSession();
    $eauth_id = $session->get('eAuthId');
    $zrole = $session->get('ApplicationRoleEnumeration');

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
      if (!empty($this->view->args[0]) && !is_numeric($this->view->args[0])) {
        $asset_type = $this->view->args[0];
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
      $this->ensureMyTable();
      $this->query->addWhere(0, "$this->tableAlias.id", $asset_ids, 'IN');
    }
  }

}
