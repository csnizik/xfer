<?php
use Drupal\asset\Entity\Asset;
use Drupal\taxonomy\Entity\Term;
/**
 * @file
 * Post update functions for CIG PODS module.
 */

/**
 * Install the select2 module if needed
 */
 function cig_pods_post_update_enable_select2(&$sandbox = NULL) {
  if (!\Drupal::service('module_handler')->moduleExists('select2')) {
  \Drupal::service('module_installer')->install(['select2']);
  }
}

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
 * Adjust the name of Short Term Storage of Animal Waste and By-Products (318)
 * taxonomy for correctness.
 */
function cig_pods_post_update_taxonomy_by_products($sandbox = NULL) {
  $term_arr = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->
  loadByProperties(['name' => 'Short Term Storage of Animal Waste and By- Products  (318)']);
  $term = reset($term_arr);
  $term->setName('Short Term Storage of Animal Waste and By-Products (318)');
  $term->save();
}

/**
 * Add Cover Crop Terms.
 */
function cig_pods_post_update_cover_crop_terms(&$sandbox = NULL) {
  $terms = cig_pods_cover_crop_terms();
  foreach($terms as $name){
    $term = Term::create([
      'name' => $name,
      'vid' => "d_cover_crop",
    ]);
    $term->save();
  }
}

/**
 * Add Soil Carbon Amendment Taxonomy.
 */
function cig_pods_post_update_soil_carbon_amendment(&$sandbox = NULL) {
    $term = Term::create([
      'name' => 'Soil Carbon Amendment (336)',
      'vid' => "d_practice",
    ]);
    $term->save();
}

/**
 * Add Soil PH Method Taxonomies.
 */
function cig_pods_post_update_soil_ph_terms(&$sandbox = NULL) {
  $terms = [
    'H20, 1:1, v:v',
    'H20, 1:1, w:v',
    'CaCl2, 1:1, v:v',
    'CaCl2, 1:1, w:v',
    'Other'
  ];
  foreach($terms as $name){
    $term = Term::create([
      'name' => $name,
      'vid' => "d_ph_method",
    ]);
    $term->save();
  }
}

/**
 * Add new Field in Lab Methods Soil PH Methods.
 */
function cig_pods_post_update_field_lab_method_soil_ph(&$sandbox = NULL) {
  $options = [
    'label' => 'Lab Method Soil PH Method',
    'type' => 'entity_reference',
    'target_type' => 'taxonomy_term',
    'target_bundle' => 'd_ph_method',
    'required' => TRUE,
    'description' => '',
  ];
  $field_definition = \Drupal::service(id: 'farm_field.factory')->bundleFieldDefinition($options);
  \Drupal::entityDefinitionUpdateManager()->installFieldStorageDefinition('field_lab_method_soil_ph_method', 'asset', 'cig_pods', $field_definition);
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

/**
 * Correct the typing of the fields in the Field Assessment Entity
 */
function cig_pods_post_update_field_assessment_fields(&$sandbox = NULL) {
  $field_assessments = \Drupal::entityTypeManager()->getStorage('asset')->loadByProperties(
    ['type' => 'field_assessment']);
    
    if(count($field_assessments) > 0){
      foreach($field_assessments as $assessment){
        try{ 
          $assessment->delete();
        }catch (\Exception $e) {
        }
      }
    }
    
  $update_manager = \Drupal::entityDefinitionUpdateManager();

  $fields_to_change = [
    'Soil Cover' => 'field_assessment_soil_cover',
    'Residue Breakdown' => 'field_assessment_residue_breakdown',
    'Surface Crusts' => 'field_assessment_surface_crusts',
    'Ponding' => 'field_assessment_ponding',
    'Penetration Resistance' => 'field_assessment_penetration_resistance',
    'Water Stable Aggregates' => 'field_assessment_water_stable_aggregates',
    'Soil Structure' => 'field_assessment_soil_structure',
    'Soil Color' => 'field_assessment_soil_color',
    'Plant Roots' => 'field_assessment_plant_roots',
    'Biological Diversity' => 'field_assessment_biological_diversity',
    'Biopores' => 'field_assessment_biopores',
  ];

  foreach($fields_to_change as $key => $field){
    $storage_definition = $update_manager->getFieldStorageDefinition($field, 'asset');
    $update_manager->uninstallFieldStorageDefinition($storage_definition);
  
    $options = [
      'label' => $key,
      'type' => 'string',
      'required' => FALSE,
      'description' => 'Field Assessment',
    ];
  
    $field_definition = \Drupal::service(id: 'farm_field.factory')->bundleFieldDefinition($options);
    $update_manager->installFieldStorageDefinition($field, 'asset', 'cig_pods', $field_definition);
  }

}

/**
 * Remove Lab Testing Profile entries from the database
 */
function cig_pods_post_update_delete_lab_profile_entries(&$sandbox = NULL) {
  //delete any existing lab test proifle entiries
  $storage = \Drupal::entityTypeManager()->getStorage('asset');
  $assets = $storage->loadByProperties(['type' => 'lab_testing_profile']);
  $storage->delete($assets);

  //delete the lab test profile entity type
  $asset_type = \Drupal::entityTypeManager()->getStorage('asset_type')->load('lab_testing_profile');
  if (!empty($asset_type)) {
    $asset_type->delete();
  }

}