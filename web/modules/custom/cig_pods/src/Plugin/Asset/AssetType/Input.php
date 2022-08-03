<?php

namespace Drupal\cig_pods\Plugin\Asset\AssetType;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;
use Drupal\farm_field\FarmFieldFactory;

/**
   * Provides the Input asset type.
   *
   * @AssetType(
   * id = "input",
   * label = @Translation("Input"),
   * description = @Translation("Input")
   * )
   */
class Input extends FarmAssetType {

   public function buildFieldDefinitions() {
      $fields = parent::buildFieldDefinitions();

      $field_info = [
        'field_operation' => [
            'label'=> 'Operation',
            'type'=> 'entity_reference',
            'target_type'=> 'asset',
            'target_bundle'=> 'operation',
            'required' => FALSE,
            'description' => '',
        ],
        'field_input_date' => [
           'label'=> 'Date',
           'type'=> 'timestamp',
           'required' => FALSE,
           'description' => '',
        ],
        'field_input_category' => [
            'label'=> 'Input Category',
            'type'=> 'entity_reference',
            'target_type'=> 'taxonomy_term',
            'target_bundle'=> 'd_input_category',
            'required' => FALSE,
            'description' => '',
        ],
        'field_input' => [
            'label'=> 'Input',
            'type'=> 'entity_reference',
            'target_type'=> 'taxonomy_term',
            'target_bundle'=> 'd_input',
            'required' => FALSE,
            'description' => '',
        ],
        'field_unit' => [
            'label'=> 'Unit',
            'type'=> 'entity_reference',
            'target_type'=> 'taxonomy_term',
            'target_bundle'=> 'd_unit',
            'required' => FALSE,
            'description' => '',
        ],
        'field_rate_units' => [
            'label'=> 'Rate Units',
            'type'=> 'fraction',
            'required' => FALSE,
            'description' => '',
        ],
        'field_cost_per_unit' => [
            'label'=> 'Cost Per Unit',
            'type'=> 'fraction',
            'required' => FALSE,
            'description' => '',
        ],
        'field_custom_application_unit' => [
            'label'=> 'Custom Application Unit',
            'type'=> 'entity_reference',
            'target_type'=> 'taxonomy_term',
            'target_bundle'=> 'd_unit',
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
        'field_input_project_id' =>[
            'type'  => 'fraction',
            'label' => 'Input Project ID reference',
            'description' => $this->t('Input Project ID reference'),
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