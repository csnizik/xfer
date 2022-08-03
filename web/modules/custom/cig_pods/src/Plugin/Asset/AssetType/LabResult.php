<?php

namespace Drupal\cig_pods\Plugin\Asset\AssetType;
use Drupal\farm_field\FarmFieldFactory;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;
/**
   * Provides the CIG Project asset type.
   *
   * @AssetType(
   * id = "lab_result",
   * label = @Translation("Lab Result"),
   * )
   */
class LabResult extends FarmAssetType {

   public function buildFieldDefinitions(){
      $fields = parent::buildFieldDefinitions();
      $field_info = [
         'field_lab_result_raw_soil_organic_carbon' => [
            'label' => 'Raw Soil Organic Carbon',
            'type' => 'fraction',
            'required' => FALSE,
         ],
         'field_lab_result_raw_aggregate_stability' => [
             'label'=> 'Aggregate Stability',
             'type'=> 'fraction',
             'required' => FALSE,
         ],
         'field_lab_result_raw_respiration' => [
             'label'=> 'Respiration',
             'type'=> 'fraction',
             'required' => FALSE,
         ],
         'field_lab_result_active_carbon' => [
             'label'=> 'Active Carbon',
             'type'=> 'fraction',
             'required' => FALSE,
             'description' => '(Unit ppm)',
         ],
         'field_lab_result_available_organic_nitrogen' => [
             'label'=> 'Available Organic Nitrogen (ACE Protein)',
             'type'=> 'fraction',
             'required' => FALSE,
             'description' => '(Unit ppm)',
         ],

         'field_lab_result_sf_bulk_density_dry_weight' => [
             'label'=> 'Bulk Density Dry weight',
             'type'=> 'fraction',
             'required' => FALSE,
             'description' => '(Unit grams)',

         ],
         'field_lab_result_sf_infiltration_rate' => [
             'label'=> 'Infiltration Rate',
             'type'=> 'fraction',
             'required' => FALSE,
             'description' => '(Inches per Hour)',
         ],
         // TODO: May need changed to enforce significant figures
         'field_lab_result_sf_ph_value' => [
             'label'=> 'pH',
             'type'=> 'fraction',
             'required' => FALSE,
             'description' => '(Decimal value between 1 and 14 to the tenth)',
         ],
         'field_lab_result_sf_electroconductivity' => [
             'label'=> 'Electroconductivity (EC)',
             'type'=> 'fraction',
             'required' => FALSE,
             'description' => '(Unit dS/m)',
         ],
         'field_lab_result_sf_ec_lab_interpretation' => [
             'label'=> 'Electroconductivity Lab Interpretation',
             'type'=> 'entity_reference',
             'target_type'=> 'taxonomy_term',
             'target_bundle' => 'd_lab_interpretation',
             'required' => FALSE,
             'description' => '',
         ],
         'field_lab_result_sf_cation_exchange_capacity' => [
             'label'=> 'Cation Exchange Capacity (CEC)',
             'type'=> 'fraction',
             'required' => FALSE,
             'description' => '(Unit ppm)',
         ],
         'field_lab_result_sf_nitrate_n' => [
             'label'=> 'Nitrate-N',
             'type'=> 'fraction',
             'required' => FALSE,
             'description' => '(Unit ppm)',
         ],
         'field_lab_result_sf_nitrate_n_lab_interpretation' => [
             'label'=> 'Nitrate-N Lab Intepretation',
             'type'=> 'entity_reference',
             'target_type'=> 'taxonomy_term',
             'target_bundle'=> 'd_lab_interpretation',
             'required' => FALSE,
             'description' => '',
         ],
         'field_lab_result_sf_nitrogen_by_dry_combustion' => [
             'label'=> 'Total Nitrogen by Dry Combustion',
             'type'=> 'fraction',
             'required' => FALSE,
             'description' => '(Unit Percent)',
         ],
         'field_lab_result_sf_phosphorous' => [
             'label'=> 'Phosphorous ',
             'type'=> 'fraction',
             'required' => FALSE,
             'description' => '(Unit ppm)',

         ],
         'field_lab_result_sf_phosphorous_lab_interpretation' => [
             'label'=> 'Phosphorous Lab Interpretation ',
             'type'=> 'entity_reference',
             'target_type'=> 'taxonomy_term',
             'target_bundle'=> 'd_lab_interpretation',
             'required' => FALSE,
             'description' => '',
         ],
         'field_lab_result_sf_potassium' => [
             'label'=> 'Potassium',
             'type'=> 'fraction',
             'required' => FALSE,
             'description' => '(Unit ppm)',
         ],
         'field_lab_result_sf_potassium_lab_interpretation' => [
             'label'=> 'Potassium Lab Interpretation',
             'type'=> 'entity_reference',
             'target_type'=> 'taxonomy_term',
             'target_bundle'=> 'd_lab_interpretation',
             'required' => FALSE,
             'description' => '',

         ],
         'field_lab_result_sf_calcium' => [
             'label'=> 'Calcium',
             'type'=> 'fraction',
             'required' => FALSE,
             'description' => '(Unit ppm)',
         ],
         'field_lab_result_sf_calcium_lab_interpretation' => [
             'label'=> 'Calcium Lab Interpretation',
             'type'=> 'entity_reference',
             'target_type'=> 'taxonomy_term',
             'target_bundle'=> 'd_lab_interpretation',
             'required' => FALSE,
             'description' => '',

         ],
         'field_lab_result_sf_magnesium' => [
             'label'=> 'Magnesium',
             'type'=> 'fraction',
             'required' => FALSE,
             'description' => '(Unit ppm)',
         ],
         'field_lab_result_sf_magnesium_lab_interpretation' => [
             'label'=> 'Magnesium Lab Interpretation',
             'type'=> 'entity_reference',
             'target_type'=> 'taxonomy_term',
             'target_bundle'=> 'd_lab_interpretation',
             'required' => FALSE,
             'description' => '',
         ],
         'field_lab_result_sf_sulfur' => [
             'label'=> 'Sulfur',
             'type'=> 'fraction',
             'required' => FALSE,
             'description' => '(Unit ppm)',
         ],
         'field_lab_result_sf_sulfur_lab_interpretation' => [
             'label'=> 'Sulfur Lab Interpretation',
             'type'=> 'entity_reference',
             'target_type'=> 'taxonomy_term',
             'target_bundle'=> 'd_lab_interpretation',
             'required' => FALSE,
             'description' => '',
         ],
         'field_lab_result_sf_iron' => [
             'label'=> 'Iron',
             'type'=> 'fraction',
             'required' => FALSE,
             'description' => '(Unit ppm)',
         ],
         'field_lab_result_sf_iron_lab_interpretation' => [
            'label'=> 'Iron Lab Interpretation',
            'type'=> 'entity_reference',
            'target_type'=> 'taxonomy_term',
            'target_bundle'=> 'd_lab_interpretation',
            'required' => FALSE,
            'description' => '',
         ],
         'field_lab_result_sf_manganese' => [
            'label'=> 'Manganese',
            'type'=> 'fraction',
            'required' => FALSE,
            'description' => '(Unit ppm)',
         ],
         'field_lab_result_sf_manganese_lab_interpretation' => [
            'label'=> 'Manganese Lab Interpretation',
            'type'=> 'entity_reference',
            'target_type'=> 'taxonomy_term',
            'target_bundle'=> 'd_lab_interpretation',
            'required' => FALSE,
            'description' => '',
         ],
         'field_lab_result_sf_copper' => [
            'label'=> 'Copper',
            'type'=> 'fraction',
            'required' => FALSE,
            'description' => '(Unit ppm)',
         ],
         'field_lab_result_sf_copper_lab_interpretation' => [
            'label'=> 'Copper Lab Interpretation',
            'type'=> 'entity_reference',
            'target_type'=> 'taxonomy_term',
            'target_bundle'=> 'd_lab_interpretation',
            'required' => FALSE,
            'description' => '',
         ],
         'field_lab_result_sf_zinc' => [
            'label'=> 'Zinc',
            'type'=> 'fraction',
            'required' => FALSE,
            'description' => '(Unit ppm)',
         ],
         'field_lab_result_sf_zinc_lab_interpretation' => [
            'label'=> 'Zinc Lab Interpretation',
            'type'=> 'entity_reference',
            'target_type'=> 'taxonomy_term',
            'target_bundle'=> 'd_lab_interpretation',
            'required' => FALSE,
            'description' => '',
         ],
         'field_lab_result_sf_boron' => [
            'label'=> 'Boron',
            'type'=> 'fraction',
            'required' => FALSE,
            'description' => '(Unit ppm)',
         ],
         'field_lab_result_sf_boron_lab_interpretation' => [
            'label'=> 'Boron Lab Interpretation',
            'type'=> 'entity_reference',
            'target_type'=> 'taxonomy_term',
            'target_bundle'=> 'd_lab_interpretation',
            'required' => FALSE,
            'description' => '',
         ],
         'field_lab_result_sf_aluminum' => [
            'label'=> 'Aluminum',
            'type'=> 'fraction',
            'required' => FALSE,
            'description' => '(Unit ppm)',
         ],
         'field_lab_result_sf_aluminum_lab_interpretation' => [
            'label'=> 'Aluminum Lab Interpretation',
            'type'=> 'entity_reference',
            'target_type'=> 'taxonomy_term',
            'target_bundle'=> 'd_lab_interpretation',
            'required' => FALSE,
            'description' => '',
         ],
         'field_lab_result_sf_molybdenum' => [
            'label'=> 'Molybdenum',
            'type'=> 'fraction',
            'required' => FALSE,
            'description' => '(Unit ppm)',
         ],
         'field_lab_result_sf_molybdenum_lab_interpretation' => [
            'label'=> 'Molybdenum Lab Interpretation',
            'type'=> 'entity_reference',
            'target_type'=> 'taxonomy_term',
            'target_bundle'=> 'd_lab_interpretation',
            'required' => FALSE,
            'description' => '',
         ],
         'field_lab_result_soil_sample' => [
            'label'=> 'Soil Sample ID',
            'type'=> 'entity_reference',
            'target_type'=> 'asset',
            'target_bundle'=> 'soil_health_sample',
            'required' => TRUE,
            'description' => $this->t('Lab Result Soil Sample'),
        ],
        'field_lab_result_project_id' =>[
            'type'  => 'fraction',
            'label' => 'Lab Result Project ID reference',
            'description' => $this->t('Lab Result Project ID reference'),
            'required' => FALSE,
            'multiple' => FALSE,
         ],


      ];

      $farmFieldFactory = new FarmFieldFactory();
      foreach($field_info as $name => $info){

		$fields[$name] = $farmFieldFactory->bundleFieldDefinition($info)
					      -> setDisplayConfigurable('form',TRUE)
					      -> setDisplayConfigurable('view', TRUE);
      }

      return $fields;
   }
}