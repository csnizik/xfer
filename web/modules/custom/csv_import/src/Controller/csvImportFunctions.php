<?php

use Drupal\asset\Entity\Asset;
use Drupal\log\Entity\Log;

function import_producer($in_data_array, $cur_count){
    $producer_submission = [];
    $producer_submission['type'] = 'producer';
    $producer_submission['project'] = array_pop(\Drupal::entityTypeManager()->getStorage('asset')->loadByProperties(['type' => 'project', 'name' => $in_data_array[0]]));
    $producer_submission['field_producer_first_name'] = $in_data_array[1];
    $producer_submission['field_producer_last_name'] = $in_data_array[2];
    $producer_submission['field_producer_headquarter'] = $in_data_array[3];
    $producer_submission['name'] = $producer_submission['field_producer_first_name'] . " " . $producer_submission['field_producer_last_name'];

    $ps_to_save = Asset::create($producer_submission);
            
    $ps_to_save->save();
}

function import_methods($in_data_array, $cur_count){
    $methods_submission = [];
    $methods_submission['type'] = 'lab_testing_method';
    $methods_submission['field_lab_method_name'] = $in_data_array[0];
    $methods_submission['field_lab_method_project'] = array_pop(\Drupal::entityTypeManager()->getStorage('asset')->loadByProperties(['type' => 'project', 'name' => $in_data_array[1]]));
    $methods_submission['field_lab_soil_test_laboratory'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_laboratory', 'name' => $in_data_array[2]]));
    $methods_submission['field_lab_method_aggregate_stability_method'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_aggregate_stability_me', 'name' => $in_data_array[4]]));
    $methods_submission['field_lab_method_aggregate_stability_unit'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_aggregate_stability_un', 'name' => $in_data_array[5]]));
    $methods_submission['field_lab_method_respiration_incubation_days'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_respiration_incubation', 'name' => $in_data_array[6]]));
    $methods_submission['field_lab_method_respiration_detection_method'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_respiration_detection_', 'name' => $in_data_array[7]]));
    $methods_submission['field_lab_method_bulk_density_core_diameter'] = $in_data_array[8];
    $methods_submission['field_lab_method_bulk_density_volume'] = $in_data_array[9];
    $methods_submission['field_lab_method_infiltration_method'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_infiltration_method', 'name' => $in_data_array[10]]));
    $methods_submission['field_lab_method_electroconductivity_method'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_ec_method', 'name' => $in_data_array[11]]));
    $methods_submission['field_lab_method_nitrate_n_method'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_nitrate_n_method', 'name' => $in_data_array[12]]));
    $methods_submission['field_lab_method_soil_ph_method'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_ph_method', 'name' => $in_data_array[13]]));
    $methods_submission['field_lab_method_phosphorus_method'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_soil_health_extraction', 'name' => $in_data_array[14]]));
    $methods_submission['field_lab_method_potassium_method'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_soil_health_extraction', 'name' => $in_data_array[15]]));
    $methods_submission['field_lab_method_calcium_method'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_soil_health_extraction', 'name' => $in_data_array[16]]));
    $methods_submission['field_lab_method_magnesium_method'] =array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_soil_health_extraction', 'name' => $in_data_array[17]]));
    $methods_submission['field_lab_method_sulfur_method'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_soil_health_extraction', 'name' => $in_data_array[18]]));
    $methods_submission['field_lab_method_iron_method'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_soil_health_extraction', 'name' => $in_data_array[19]]));
    $methods_submission['field_lab_method_manganese_method'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_soil_health_extraction', 'name' => $in_data_array[20]]));
    $methods_submission['field_lab_method_copper_method'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_soil_health_extraction', 'name' => $in_data_array[21]]));
    $methods_submission['field_lab_method_zinc_method'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_soil_health_extraction', 'name' => $in_data_array[22]]));
    $methods_submission['field_lab_method_boron_method'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_soil_health_extraction', 'name' => $in_data_array[23]]));
    $methods_submission['field_lab_method_aluminum_method'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_soil_health_extraction', 'name' => $in_data_array[24]]));
    $methods_submission['field_lab_method_molybdenum_method'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_soil_health_extraction', 'name' => $in_data_array[25]]));

    $methods_submission['name'] = $methods_submission['field_lab_method_name'];
    $methods_submission['project'] = $methods_submission['field_lab_method_project'];

    $ps_to_save = Asset::create($methods_submission);
            
    $ps_to_save->save();
}

function convertExcelDate($inDate){
    $unixTimestamp = ($inDate - 25569) * 86400;
    $date = date(getExcelDateFormat(), $unixTimestamp);

    return $date;
  }

function getExcelDateFormat(){
    return "Y-m-d";
}