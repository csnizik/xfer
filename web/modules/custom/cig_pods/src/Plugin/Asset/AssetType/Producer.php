<?php

namespace Drupal\cig_pods\Plugin\Asset\AssetType;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;
use Drupal\farm_field\FarmFieldFactory;

/**
   * Provides the Producer asset type.
   *
   * @AssetType(
   * id = "producer",
   * label = @Translation("Producer"),
   * handlers = {
   *  "form" = {
   *     "add"="Drupal\cig_pods\Form\ProducerForm",
   *  }
   * },
   * )
   */
class Producer extends FarmAssetType {

   public function buildFieldDefinitions() {
      $fields = parent::buildFieldDefinitions();

      $field_info = [
         'field_producer_first_name' => [
            'type' => 'string',
            'label' => 'Producer First Name',
            'description' => '',
            'required' => TRUE,
         ],
         'field_producer_last_name' => [
            'type' => 'string',
            'label' => 'Producer Last Name',
            'description' => '',
            'required' => TRUE,
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