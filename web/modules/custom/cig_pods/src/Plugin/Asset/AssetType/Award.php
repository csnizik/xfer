<?php

namespace Drupal\cig_pods\Plugin\Asset\AssetType;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;
use Drupal\farm_field\FarmFieldFactory;

/**
 * Provides the CIG Project asset type.
 *
 * @AssetType(
 * id = "award",
 * label = @Translation("Award"),
 * )
 */
class Award extends FarmAssetType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {

    $fields = parent::buildFieldDefinitions();

    $field_info = [
      'field_award_agreement_number' => [
        'type'  => 'string',
        'label' => 'Award Agreement Number',
        'description' => 'Award Agreement Number',
        'required' => TRUE,
        'multiple' => FALSE,
      ],
      'field_award_awardee_org' => [
        'type' => 'entity_reference',
        'label' => 'Awards Awardee Field',
        'description' => 'Awards Awardee Field',
        'target_type' => 'asset',
        'target_bundle' => 'awardee',
        'required' => FALSE,
        'multiple' => FALSE,
        'form_display_options' => [
          'label' => 'inline',
          'type' => 'options_select',
        ],
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
