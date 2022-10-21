<?php

namespace Drupal\cig_pods\Plugin\Asset\AssetType;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;
use Drupal\farm_field\FarmFieldFactory;

/**
 * Provides the Soil Health Management Unit asset type.
 *
 * @AssetType(
 * id = "irrigation",
 * label = @Translation("Irrigation"),
 * )
 */
class Irrigation extends FarmAssetType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      'shmu' => [
        'label' => 'Soil Health Management Unit',
        'type' => 'entity_reference',
        'target_type' => 'asset',
        'target_bundle' => 'soil_health_management_unit',
        'required' => FALSE,
        'description' => '',
      ],
      'field_shmu_irrigation_sample_date' => [
        'label' => 'Irrigation - Sample Date',
        'type' => 'timestamp',
        'required' => FALSE,
        'description' => '',
      ],
      'field_shmu_irrigation_water_ph' => [
        'label' => 'Irrigation - Water pH',
        'type' => 'fraction',
        'required' => FALSE,
        'description' => '',
      ],
      'field_shmu_irrigation_sodium_absorption_ratio' => [
        'label' => 'Irrigation - Sodium Absorption Ratio',
        'type' => 'fraction',
        'required' => FALSE,
        'description' => '',

      ],
      'field_shmu_irrigation_total_dissolved_solids' => [
        'label' => 'Irrigation - Total Disolved Solids',
        'type' => 'fraction',
        'required' => FALSE,
        'description' => '',

      ],
      'field_shmu_irrigation_total_alkalinity' => [
        'label' => 'Irrigation - Total Alkalinity',
        'type' => 'fraction',
        'required' => FALSE,
        'description' => '',

      ],
      'field_shmu_irrigation_chlorides' => [
        'label' => 'Irrigation - Chlorides',
        'type' => 'fraction',
        'required' => FALSE,
        'description' => '',

      ],
      'field_shmu_irrigation_sulfates' => [
        'label' => 'Irrigation - Sulfates',
        'type' => 'fraction',
        'required' => FALSE,
        'description' => '',

      ],
      'field_shmu_irrigation_nitrates' => [
        'label' => 'Irrigation - Nitrates',
        'type' => 'fraction',
        'required' => FALSE,
        'description' => '',

      ],
      'project' => [
        'label' => 'Project',
        'type' => 'entity_reference',
        'target_type' => 'asset',
        'target_bundle' => 'project',
        'required' => TRUE,
        'multiple' => TRUE,
      ],
    ];

    $farmFieldFactory = new FarmFieldFactory();
    foreach ($field_info as $name => $info) {

      $fields[$name] = $farmFieldFactory->bundleFieldDefinition($info)
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);
    }

    return $fields;
  }

}
