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
       'field_project' => [
            'type' => 'entity_reference',
            'label' => 'Projects',
            'target_type' => 'asset',
            'target_bundle' => 'project',
            'required' => FALSE,
            'multiple' => TRUE,
        ],
       'field_organization_acronym' => [
            'type' => 'string',
            'label' => 'Organization acronym',
            'description' => '' ,
            'required' => FALSE ,
            'multiple' => FALSE
       ]
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
