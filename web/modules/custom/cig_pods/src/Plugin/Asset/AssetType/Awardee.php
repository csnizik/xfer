<?php

namespace Drupal\cig_pods\Plugin\Asset\AssetType;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;
use Drupal\farm_field\FarmFieldFactory;

/**
   * Provides the CIG Project asset type.
   *
   * @AssetType(
   * id = "awardee",
   * label = @Translation("Awardee"),
   * )
   */
class Awardee extends FarmAssetType {

   /**
    * {@inheritdoc}
    */
    public function buildFieldDefinitions() {

      $fields = parent::buildFieldDefinitions();

      $field_info = [
       'project' => [
            'type' => 'entity_reference',
            'label' => 'Projects',
            'target_type' => 'asset',
            'target_bundle' => 'project',
            'required' => TRUE,
            'multiple' => TRUE,
        ],
       'organization_acronym' => [
            'type' => 'string',
            'label' => 'Organization Acronym',
            'description' => '' ,
            'required' => FALSE ,
            'multiple' => FALSE
       ],
       'organization_short_name' => [
         'type' => 'string',
         'label' => 'Organization Short Name',
         'description' => '' ,
         'required' => FALSE ,
         'multiple' => FALSE
       ],
       'organization_state_territory' => [
         'type' => 'entity_reference',
         'label' => 'Awardee Organization State Or Territory',
         'description' => $this->t(''),
			  'target_type' => 'taxonomy_term',
			  'target_bundle' => 'd_state_territory',
			  'handler' => 'default',
            'required' => FALSE,
            'multiple' => FALSE,
            // Lower weight shows up first in form
           'weight' => [
               'form' => 14,
               'view' => 14
			      ],
            'form_display_options' => [
                'label' => 'inline',
                'type' => 'options_select'
            ]
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
