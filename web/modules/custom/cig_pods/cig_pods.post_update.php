<?php

/**
 * @file
 * Post update functions for CIG PODS module.
 */

/**
 * Add new Field in SHMU Experiemental Design Section - Experimental Duration (Months).
 */
function cig_pods_post_update_field_shmu_experimental_duration_month(&$sandbox = NULL) {
  $options = [
    'label' => 'Experimental Duration Month',
    'type' => 'fraction',
    'required' => FALSE,
    'description' => '',
  ];
  $field_definition = \Drupal::service(id: 'farm_field.factory')->bundleFieldDefinition($options);
  \Drupal::entityDefinitionUpdateManager()->installFieldStorageDefinition('field_shmu_experimental_duration_month', 'asset', 'cig_pods', $field_definition);
}

/**
 * Add new Field in SHMU Experiemental Design Section - Experimental Duration (Years).
 */
function cig_pods_post_update_field_shmu_experimental_duration_year(&$sandbox = NULL) {
  $options = [
    'label' => 'Experimental Duration Year',
    'type' => 'fraction',
    'required' => FALSE,
    'description' => '',
  ];
  $field_definition = \Drupal::service(id: 'farm_field.factory')->bundleFieldDefinition($options);
  \Drupal::entityDefinitionUpdateManager()->installFieldStorageDefinition('field_shmu_experimental_duration_year', 'asset', 'cig_pods', $field_definition);
}

/**
 * Add new Field in SHMU Experiemental Design Section - Experimental Frequency (Days).
 */
function cig_pods_post_update_field_shmu_experimental_frequency_day(&$sandbox = NULL) {
  $options = [
    'label' => 'Experimental Frequency Day',
    'type' => 'fraction',
    'required' => FALSE,
    'description' => '',
  ];
  $field_definition = \Drupal::service(id: 'farm_field.factory')->bundleFieldDefinition($options);
  \Drupal::entityDefinitionUpdateManager()->installFieldStorageDefinition('field_shmu_experimental_frequency_day', 'asset', 'cig_pods', $field_definition);
}

/**
 * Add new Field in SHMU Experiemental Design Section - Experimental Frequency (Months).
 */
function cig_pods_post_update_field_shmu_experimental_frequency_month(&$sandbox = NULL) {
  $options = [
    'label' => 'Experimental Frequency Month',
    'type' => 'fraction',
    'required' => FALSE,
    'description' => '',
  ];
  $field_definition = \Drupal::service(id: 'farm_field.factory')->bundleFieldDefinition($options);
  \Drupal::entityDefinitionUpdateManager()->installFieldStorageDefinition('field_shmu_experimental_frequency_month', 'asset', 'cig_pods', $field_definition);
}

/**
 * Add new Field in SHMU Experiemental Design Section - Experimental Frequency (Years).
 */
function cig_pods_post_update_field_shmu_experimental_frequency_year(&$sandbox = NULL) {
  $options = [
    'label' => 'Experimental Frequency Year',
    'type' => 'fraction',
    'required' => FALSE,
    'description' => '',
  ];
  $field_definition = \Drupal::service(id: 'farm_field.factory')->bundleFieldDefinition($options);
  \Drupal::entityDefinitionUpdateManager()->installFieldStorageDefinition('field_shmu_experimental_frequency_year', 'asset', 'cig_pods', $field_definition);
}

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
