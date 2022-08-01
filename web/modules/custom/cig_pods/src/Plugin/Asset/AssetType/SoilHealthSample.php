<?php

namespace Drupal\cig_pods\Plugin\Asset\AssetType;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;
use Drupal\farm_field\FarmFieldFactory;
/**
   * Provides the Soil Health Sample asset type.
   *
   * @AssetType(
   * id = "soil_health_sample",
   * label = @Translation("Soil Health Sample"),
   * )
   */
class SoilHealthSample extends FarmAssetType {

  /**
  * {@inheritdoc}
  */
  public function buildFieldDefinitions() {

    $fields = parent::buildFieldDefinitions();

    $field_info = [
      'field_diameter' => [
        'type' => 'fraction',
        'label' => 'Soil Sample Diameter',
        'required' => FALSE,
        'multiple' => FALSE,
      ],
      'field_equipment_used' => [
        'label'=> 'Soil Sample Equipment',
        'type'=> 'entity_reference',
        'target_type'=> 'taxonomy_term',
        'target_bundle' => 'd_equipment',
        'required' => FALSE,
        'multiple' => FALSE,
      ],
      'field_latitude_1' => [
        'type' => 'fraction',
        'label' => 'Soil Sample Latitude 1',
        'required' => FALSE,
        'multiple' => TRUE,
      ],
      'field_latitude_2' => [
        'type' => 'fraction',
        'label' => 'Soil Sample Latitude 2',
        'required' => FALSE,
        'multiple' => FALSE,
      ],
      'field_latitude_3' => [
        'type' => 'fraction',
        'label' => 'Soil Sample Latitude 3',
        'required' => FALSE,
        'multiple' => FALSE,
      ],
      'field_longtitude_1' => [
        'type' => 'fraction',
        'label' => 'Soil Sample Longtitude 1',
        'required' => FALSE,
        'multiple' => FALSE,
      ],
      'field_longtitude_2' => [
        'type' => 'fraction',
        'label' => 'Soil Sample Longtitude 2',
        'required' => FALSE,
        'multiple' => FALSE,
      ],
      'field_longtitude_3' => [
        'type' => 'fraction',
        'label' => 'Soil Sample Longtitude 3',
        'required' => FALSE,
        'multiple' => FALSE,
      ],
      'field_plant_stage_at_sampling' => [
        'label'=> 'Soil Sample Plant Stage',
        'type'=> 'entity_reference',
        'target_type'=> 'taxonomy_term',
        'target_bundle' => 'd_plant_stage',
        'required' => FALSE,
        'multiple' => FALSE,
      ],
      'field_sampling_depth' => [
        'type' => 'fraction',
        'label' => 'Soil Sample Sampleing Depth',
        'required' => FALSE,
        'multiple' => FALSE,
      ],
      'field_shmu_id' => [
        'type'  => 'entity_reference',
        'label' => 'Soil Sample SHMU ID',
        'description' => $this->t('Soil Sample SHMU ID'),
        'target_type' => 'asset',
        'target_bundle' => 'soil_health_management_unit',
        'handler' => 'default',
        'required' => TRUE,
        'multiple' => FALSE,
      ],
      'field_soil_sample_collection_dat' => [
        'label'=> 'Date Sample was collected',
        'type'=> 'timestamp',
        'required' => FALSE,
        'description' => '',
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