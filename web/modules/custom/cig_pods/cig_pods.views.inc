<?php

/**
 * @file
 * Provides Views data for cig_pods module.
 */

/**
 * Implements hook_views_data().
 */
function cig_pods_views_data() {
  $data = [];

  // Pods project access argument.
  $data['asset']['pods_project_access'] = [
    'title' => t('PODS Project Access'),
    'argument' => [
      'id' => 'pods_project_access',
    ],
  ];

  return $data;
}
