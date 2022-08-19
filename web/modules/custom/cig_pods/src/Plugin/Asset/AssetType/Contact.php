<?php

namespace Drupal\cig_pods\Plugin\Asset\AssetType;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;
use Drupal\farm_field\FarmFieldFactory;

/**
   * Provides the Cost asset type.
   *
   * @AssetType(
   * id = "contact",
   * label = @Translation("Contact"),
   * description = @Translation("Contact")
   * )
   */
class Contact extends FarmAssetType {

   public function buildFieldDefinitions() {
      $fields = parent::buildFieldDefinitions();

      $field_info = [
         'field_eauth_id' => [
            'label'=> 'Eauth ID',
            'type'=> 'fraction',
            'required' => TRUE,
            'description' => '',
         ],
         'field_contact_type' => [
            'label'=> 'Contact Type',
            'type'=> 'entity_reference',
            'target_type'=> 'taxonomy_term',
            'target_bundle'=> 'd_contact_type',
            'required' => TRUE,
            'description' => '',
         ],
         'field_contact_project_id' =>[
            'type'  => 'fraction',
            'label' => 'Contact Project ID reference',
            'description' => $this->t('Contact Project ID reference'),
            'required' => TRUE,
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