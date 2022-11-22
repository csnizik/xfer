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
 * )
 */
class Input extends FarmAssetType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();

    $field_info = [
      'field_input_date' => [
        'label' => 'Date',
        'type' => 'timestamp',
        'required' => FALSE,
        'description' => '',
      ],
      'field_input_category' => [
        'label' => 'Input Category',
        'type' => 'entity_reference',
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'd_input',
        'required' => FALSE,
        'description' => '',
      ],
      'field_input' => [
        'label' => 'Input',
        'type' => 'entity_reference',
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'd_input',
        'required' => FALSE,
        'description' => '',
      ],
      'field_unit' => [
        'label' => 'Unit',
        'type' => 'entity_reference',
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'd_unit',
        'required' => FALSE,
        'description' => '',
      ],
      'field_rate_units' => [
        'label' => 'Rate Units',
        'type' => 'fraction',
        'required' => FALSE,
        'description' => '',
      ],
      'field_cost_per_unit' => [
        'label' => 'Cost Per Unit',
        'type' => 'fraction',
        'required' => FALSE,
        'description' => '',
      ],
      'field_custom_application_unit' => [
        'label' => 'Custom Application Unit',
        'type' => 'entity_reference',
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'd_unit',
        'required' => FALSE,
        'description' => '',
      ],
      'field_input_cost_sequences' => [
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

      $fields[$name] = $farmFieldFactory->bundleFieldDefinition($info)
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);
    }

    return $fields;

  }

}
