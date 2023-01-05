<?php

namespace Drupal\cig_pods\Plugin\Asset\AssetType;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;
use Drupal\farm_field\FarmFieldFactory;

/**
 * Provides the Project asset type.
 *
 * @AssetType(
 * id = "project",
 * label = @Translation("Project"),
 * )
 */
class Project extends FarmAssetType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();

    // We do not add a "Name" field because we inherit that from the
    // FarmAssetType class.
    $field_info = [
      'field_project_agreement_number' => [
        'type'  => 'string',
        'label' => 'Agreement Number',
        'description' => '',
        'required' => FALSE,
        'multiple' => FALSE,
          // Lower weight shows up first in form.
        'weight' => [
          'form' => 1,
          'view' => 1,
        ],
      ],
      'field_grant_type' => [
        'type' => 'entity_reference',
        'label' => 'Grant Type',
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'd_grant_type',
        'handler' => 'default',
        'required' => FALSE,
        'multiple' => TRUE,
          // Lower weight shows up first in form.
        'weight' => [
          'form' => 2,
          'view' => 2,
        ],
        'form_display_options' => [
          'label' => 'inline',
          'type' => 'options_select',
        ],
      ],
      'field_funding_amount' => [
        'label' => 'Funding Amount',
        'type' => 'fraction',
        'settings' => [
          'min' => 0,
        ],
        'weight' => [
          'form' => 3,
          'view' => 3,
        ],

      ],
      'field_resource_concerns' => [
        'type'  => 'entity_reference',
        'label' => 'Possible Resource Concerns',
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'd_resource_concern',
        'handler' => 'default',
        'required' => FALSE,
        'multiple' => TRUE,
             // Lower weight shows up first in form.
        'weight' => [
          'form' => 3,
          'view' => 3,
        ],
        'form_display_options' => [
          'label' => 'inline',
          'type' => 'options_select',
        ],
      ],
      'field_summary' => [
        'type' => 'string_long',
        'label' => 'Project Summary',
        'required' => FALSE,
        'multiple' => FALSE,
        'settings' => [
          'max_length' => 1000,
          'size' => 60,
        ],
        'form_display_options' => [
          'label' => 'inline',
        ],
      ],
      'field_awardee' => [
        'type' => 'entity_reference',
        'label' => 'Awardee',
        'target_type' => 'asset',
        'target_bundle' => 'awardee',
        'required' => FALSE,
        'multiple' => FALSE,
        'form_display_options' => [
          'label' => 'inline',
          'type' => 'options_select',
        ],
      ],
      'field_awardee_eauth_id' => [
        'type' => 'string',
        'label' => 'Awardee Contact eAuth ID',
        'multiple' => TRUE,
        'required' => FALSE,
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
