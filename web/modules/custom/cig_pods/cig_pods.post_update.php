<?php

/**
 * @file
 * Post update functions for CIG PODS module.
 */

/**
 * Install SCSS Compiler module.
 */
function cig_pods_post_update_enable_scss_compiler(&$sandbox = NULL) {
  if (!\Drupal::service('module_handler')->moduleExists('scss_compiler')) {
    \Drupal::service('module_installer')->install(['scss_compiler']);
  }
}

/**
 * Uninstall MVP config.
 */
function cig_pods_post_update_uninstall_mvp_config(&$sandbox = NULL) {
  $delete_config = [
    'asset.type.cost',
    'taxonomy.vocabulary.d_additional_charges',
    'taxonomy.vocabulary.d_animal_type',
    'taxonomy.vocabulary.d_applicant_type',
    'taxonomy.vocabulary.d_assessment_evaluation',
    'taxonomy.vocabulary.d_conservation_practice',
    'taxonomy.vocabulary.d_cover_crop_species',
    'taxonomy.vocabulary.d_cover_crop_type',
    'taxonomy.vocabulary.d_cover_crop_years',
    'taxonomy.vocabulary.d_crop_rotation_years',
    'taxonomy.vocabulary.d_ecoregions',
    'taxonomy.vocabulary.d_funding_pool',
    'taxonomy.vocabulary.d_innovation_results',
    'taxonomy.vocabulary.d_irrigation_type',
    'taxonomy.vocabulary.d_practice_innovation_st',
    'taxonomy.vocabulary.d_production_use',
    'taxonomy.vocabulary.d_project_scale',
    'taxonomy.vocabulary.d_project_type',
    'taxonomy.vocabulary.d_qualitative_impact',
    'taxonomy.vocabulary.d_regional_area_tag',
    'taxonomy.vocabulary.d_sample_status',
    'taxonomy.vocabulary.d_state',
    'taxonomy.vocabulary.d_surface_texture',
  ];
  foreach ($delete_config as $name) {
    $config = \Drupal::configFactory()->getEditable($name);
    if ($config) {
      $config->delete();
    }
  }
}
