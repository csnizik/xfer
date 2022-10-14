<?php

namespace Drupal\cig_pods\Plugin\Asset\AssetType;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;
use Drupal\farm_field\FarmFieldFactory;

/**
 * Provides the Cost Sequence asset type.
 *
 * @AssetType(
 * id = "cost_sequence",
 * label = @Translation("Cost Sequence"),
 * )
 */
class CostSequence extends FarmAssetType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();

    $field_info = [
      'field_cost_type' => [
        'label' => 'Cost Type',
        'type' => 'entity_reference',
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'd_cost_type',
        'required' => FALSE,
        'description' => '',
      ],
      'field_cost' => [
        'label' => 'Cost',
        'type' => 'fraction',
        'required' => FALSE,
        'description' => '',
      ],

    ];

    $farmFieldFactory = new FarmFieldFactory();
    foreach ($field_info as $name => $info) {
      // Check if it is one of the default fields that we want to disable (I.e. Images ,)
      $fields[$name] = $farmFieldFactory->bundleFieldDefinition($info)
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);
    }

    return $fields;
  }

}
