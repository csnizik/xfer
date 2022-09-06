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
         'eauth_id' => [
            'label'=> 'Eauth ID',
            'type'=> 'string',
            'required' => TRUE,
            'description' => '',
         ],
         'field_contact_email' => [
            'label'=> 'Contact Email',
            'type'=> 'string',
            'required' => FALSE,
            'description' => '',
         ],
         'field_contact_type' => [
            'label'=> 'Contact Type',
            'type'=> 'entity_reference',
            'target_type'=> 'taxonomy_term',
            'target_bundle'=> 'd_contact_type',
            'required' => FALSE,
            'description' => '',
         ],
         'project' =>[
           'label' => 'Project',
           'type' => 'entity_reference',
           'target_type' => 'asset',
           'target_bundle' => 'project',
           'required' => FALSE,
           'multiple' => TRUE,
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