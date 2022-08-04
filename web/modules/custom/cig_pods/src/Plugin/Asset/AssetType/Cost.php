<?php

namespace Drupal\cig_pods\Plugin\Asset\AssetType;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;
use Drupal\farm_field\FarmFieldFactory;

/**
   * Provides the Cost asset type.
   *
   * @AssetType(
   * id = "cost",
   * label = @Translation("Cost"),
   * description = @Translation("Cost")
   * )
   */
class Cost extends FarmAssetType {

   public function buildFieldDefinitions() {
      $fields = parent::buildFieldDefinitions();

      $field_info = [
         'field_cost_amount' => [
            'label'=> 'Cost',
            'type'=> 'fraction',
            'required' => FALSE,
            'description' => '',
         ],
         'field_cost_type' => [
            'label'=> 'Type',
            'type'=> 'entity_reference',
            'target_type'=> 'taxonomy_term',
            'target_bundle'=> 'd_cost_type',
            'required' => FALSE,
            'description' => '',
         ],
         'field_cost_project_id' =>[
            'type'  => 'fraction',
            'label' => 'Cost Project ID reference',
            'description' => $this->t('Cost Project ID reference'),
            'required' => FALSE,
            'multiple' => FALSE,
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