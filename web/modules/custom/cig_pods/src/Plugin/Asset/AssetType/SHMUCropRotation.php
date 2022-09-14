<?php

namespace Drupal\cig_pods\Plugin\Asset\AssetType;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;
use Drupal\farm_field\FarmFieldFactory;

/**
   * Provides the SHMU Crop Rotation asset type.
   *
   * @AssetType(
   * id = "shmu_crop_rotation",
   * label = @Translation("SHMU Crop Rotation"),
   * )
   */
class SHMUCropRotation extends FarmAssetType {

   public function buildFieldDefinitions() {
      $fields = parent::buildFieldDefinitions();

      $field_info = [
        'field_shmu_crop_rotation_crop' => [
             'label'=> 'Crop',
             'type'=> 'entity_reference',
             'target_type'=> 'taxonomy_term',
             'target_bundle'=> 'd_crop',
             'required' => FALSE,
             'description' => '',
        ],
        'field_shmu_crop_rotation_year' => [
             'label'=> 'Year',
             'type'=> 'fraction',
             'required' => FALSE,
             'description' => '',
        ],
        'field_shmu_crop_rotation_crop_present' => [
             'label'=> 'Months Planted',
             'type'=> 'fraction', // TRUE if Crop is present in that month
             'required' => TRUE,
             'multiple' => TRUE,
             'description' => 'Month index (Starting with January at index 0)',
        ],
      ];

      $farmFieldFactory = new FarmFieldFactory();
      foreach($field_info as $name => $info){
         // Check if it is one of the default fields that we want to disable (I.e. Images ,)
 
 
       $fields[$name] = $farmFieldFactory->bundleFieldDefinition($info)
                      -> setDisplayConfigurable('form',TRUE)
                      -> setDisplayConfigurable('view', TRUE);
       }

       return $fields;
   }

}
