<?php

namespace Drupal\cig_pods\Plugin\Asset\AssetType;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;
use Drupal\farm_field\FarmFieldFactory;

/**
   * Provides the Operation asset type.
   *
   * @AssetType(
   * id = "operation",
   * label = @Translation("Operation"),
   * )
   */
class Operation extends FarmAssetType {

   public function buildFieldDefinitions() {
      $fields = parent::buildFieldDefinitions();

      $field_info = [
        'field_shmu' => [
             'label'=> 'Soil Health Management Unit (SHMU)',
             'type'=> 'entity_reference',
             'target_type'=> 'asset',
             'target_bundle'=> 'soil_health_management_unit', 
             'required' => FALSE,
             'description' => '',
        ],
        'field_operation_date' => [
            'label'=> 'Date Of Operation',
            'type'=> 'timestamp',
            'required' => FALSE,
            'description' => '',
        ],
        'field_operation' => [
            'label'=> 'Operation',
            'type'=> 'entity_reference',
            'target_type'=> 'taxonomy_term',
            'target_bundle'=> 'd_operation_type',
            'required' => FALSE,
            'description' => '',
        ],
        'field_ownership_status' => [
            'label'=> 'Ownership Status',
            'type'=> 'entity_reference',
            'target_type'=> 'taxonomy_term',
            'target_bundle'=> 'd_equipment_ownership',
            'required' => FALSE,
            'description' => '',
        ],
        'field_tractor_self_propelled_machine' => [
            'label'=> 'Tractor/Self-Propelled Machine',
            'type'=> 'entity_reference',
            'target_type'=> 'taxonomy_term',
            'target_bundle'=> 'd_tractor_self_propelled_machine',
            'required' => FALSE,
            'description' => '',
        ],
        'field_row_number' => [
            'label'=> 'Number of Rows',
            'type'=> 'fraction',
            'required' => FALSE,
            'description' => '',
        ],
        'field_width' => [
            'label'=> 'Width',
            'type'=> 'fraction',
            'required' => FALSE,
            'description' => '',
        ],
        'field_horsepower' => [
            'label'=> 'Horsepower',
            'type'=> 'fraction',
            'required' => FALSE,
            'description' => '',
        ],
        'field_cost' => [
            'type' => 'entity_reference',
            'label' => 'Cost',
            'target_type' => 'asset',
            'target_bundle' => 'cost',
            'required' => FALSE,
            'multiple' => FALSE,
            'form_display_options' => [
               'label' => 'inline',
               'type' => 'options_select',
            ],
        ],
      ];

      $farmFieldFactory = new FarmFieldFactory();
      foreach($field_info as $name => $info){
         // Check if it is one of the default fields that we want to disable (I.e. Images ,)
 
 
       $fields[$name] = $farmFieldFactory->bundleFieldDefinition($info)
                      -> setDisplayConfigurable('form',TRUE)
                      -> setDisplayConfigurable('view', TRUE);
       }

       return $fields;
   }

}
