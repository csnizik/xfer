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
        'required' => TRUE,
        'description' => '',
      ],
      'field_operation_date' => [
        'label' => 'Date Of Operation',
        'type' => 'timestamp',
        'required' => TRUE,
        'description' => '',
      ],
      'field_operation' => [
        'label' => 'Operation',
        'type' => 'entity_reference',
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'd_operation_type',
        'required' => TRUE,
        'description' => '',
      ],
      'field_input' => [
        'label' => 'Input Reference',
        'type' => 'entity_reference',
        'target_type' => 'asset',
        'target_bundle' => 'input',
        'required' => TRUE,
        'multiple' => TRUE,
        'description' => '',
      ],
      'field_ownership_status' => [
        'label' => 'Ownership Status',
        'type' => 'entity_reference',
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'd_equipment_ownership',
        'required' => TRUE,
        'description' => '',
      ],
      'field_tractor_self_propelled_machine' => [
        'label' => 'Tractor/Self-Propelled Machine',
        'type' => 'entity_reference',
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'd_equipment',
        'required' => TRUE,
        'description' => '',
      ],
      'field_row_number' => [
        'label' => 'Number of Rows',
        'type' => 'fraction',
        'required' => FALSE,
        'description' => '',
      ],
      'field_width' => [
        'label' => 'Width',
        'type' => 'fraction',
        'required' => FALSE,
        'description' => '',
      ],
      'field_horsepower' => [
        'label' => 'Horsepower',
        'type' => 'fraction',
        'required' => FALSE,
        'description' => '',
      ],
      'field_operation_cost_sequences' => [
        'label' => 'Cost Sequence',
        'type' => 'entity_reference',
        'target_type' => 'asset',
        'target_bundle' => 'cost_sequence',
        'required' => FALSE,
        'multiple' => TRUE,
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
      // Check if it is one of the default fields that we want to disable
      // (I.e. Images)
      $fields[$name] = $farmFieldFactory->bundleFieldDefinition($info)
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);
    }

    return $fields;
  }

}
