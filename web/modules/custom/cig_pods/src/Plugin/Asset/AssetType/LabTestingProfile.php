<?php

// cig_pods namespace implies this file depends on the cig_pods module
namespace Drupal\cig_pods\Plugin\Asset\AssetType;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;
use Drupal\farm_field\FarmFieldFactory;
/**
   * Provides the LabTestingProfile asset type.
   *
   * @AssetType(
   * id = "lab_testing_profile",
   * label = @Translation("LabTestingProfile"),
   * description = @Translation("LabTestingProfile")
   * )
   */
class LabTestingProfile extends FarmAssetType {


   /**
    * {@inheritdoc}
    */

    public function buildFieldDefinitions() {

      // 
      $fields = parent::buildFieldDefinitions();

      // We do not add a "Name" field because we inherit that from the FarmAssetType class
      $field_info = [
         'ph_method' => [
            'type'  => 'entity_reference',
            'label' => 'PH method',
            'description' => $this->t(''),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => FALSE,
            'multiple' => FALSE,
            // Lower weight shows up first in form
            'weight' => [
               'form' => 10,
               'view' => 10
			],
		],
		'electroconductivity_method' => [
            'type'  => 'entity_reference',
            'label' => 'Electroconductivity Method',
            'description' => $this->t(''),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => FALSE,
            'multiple' => FALSE,
            // Lower weight shows up first in form
            'weight' => [
               'form' => 11,
               'view' => 11
			],
		],
		'nitrate_n_method' => [
            'type'  => 'entity_reference',
            'label' => 'Nitrate-N Method',
            'description' => $this->t(''),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => FALSE,
            'multiple' => FALSE,
            // Lower weight shows up first in form
            'weight' => [
               'form' => 12,
               'view' => 12
			],
		],
		'phosphorus_method' => [
            'type'  => 'entity_reference',
            'label' => 'Phosphorus Method',
            'description' => $this->t(''),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => FALSE,
            'multiple' => FALSE,
            // Lower weight shows up first in form
            'weight' => [
               'form' => 13,
               'view' => 13
			],
		],
		'potassium_method' => [
            'type'  => 'entity_reference',
            'label' => 'PH method',
            'description' => $this->t(''),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => FALSE,
            'multiple' => FALSE,
            // Lower weight shows up first in form
            'weight' => [
               'form' => 14,
               'view' => 14
			],
		],
		'calcium_method' => [
            'type'  => 'entity_reference',
            'label' => 'Calcium Method',
            'description' => $this->t(''),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => FALSE,
            'multiple' => FALSE,
            // Lower weight shows up first in form
            'weight' => [
               'form' => 15,
               'view' => 15
			],
		],
		'magnesium_method' => [
            'type'  => 'entity_reference',
            'label' => 'Magnesium Method',
            'description' => $this->t(''),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => FALSE,
            'multiple' => FALSE,
            // Lower weight shows up first in form
            'weight' => [
               'form' => 16,
               'view' => 16
			],
		],
		'sulfur_method' => [
            'type'  => 'entity_reference',
            'label' => 'Sulfur Method',
            'description' => $this->t(''),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => FALSE,
            'multiple' => FALSE,
            // Lower weight shows up first in form
            'weight' => [
               'form' => 17,
               'view' => 17
			],
		],
		'iron_method' => [
            'type'  => 'entity_reference',
            'label' => 'Iron Method',
            'description' => $this->t(''),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => FALSE,
            'multiple' => FALSE,
            // Lower weight shows up first in form
            'weight' => [
               'form' => 18,
               'view' => 18
			],
		],
		'manganese_method' => [
            'type'  => 'entity_reference',
            'label' => 'Magnanese Method',
            'description' => $this->t(''),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => FALSE,
            'multiple' => FALSE,
            // Lower weight shows up first in form
            'weight' => [
               'form' => 19,
               'view' => 19
			],
		],
		'copper_method' => [
            'type'  => 'entity_reference',
            'label' => 'Copper Method',
            'description' => $this->t(''),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => FALSE,
            'multiple' => FALSE,
            // Lower weight shows up first in form
            'weight' => [
               'form' => 20,
               'view' => 20
			],
		],
		'zinc_method' => [
            'type'  => 'entity_reference',
            'label' => 'Zinc Method',
            'description' => $this->t(''),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => FALSE,
            'multiple' => FALSE,
            // Lower weight shows up first in form
            'weight' => [
               'form' => 21,
               'view' => 21
			],
		],
		'boron_method' => [
            'type'  => 'entity_reference',
            'label' => 'Boron Method',
            'description' => $this->t(''),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => FALSE,
            'multiple' => FALSE,
            // Lower weight shows up first in form
            'weight' => [
               'form' => 22,
               'view' => 22
			],
		],
		'aluminum_method' => [
            'type'  => 'entity_reference',
            'label' => 'Aluminum Method',
            'description' => $this->t(''),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => FALSE,
            'multiple' => FALSE,
            // Lower weight shows up first in form
            'weight' => [
               'form' => 23,
               'view' => 23
			],
		],
		'molybdenum_method' => [
            'type'  => 'entity_reference',
            'label' => 'Molybdenum Method',
            'description' => $this->t(''),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => FALSE,
            'multiple' => FALSE,
            // Lower weight shows up first in form
            'weight' => [
               'form' => 24,
               'view' => 24
			],
		],		

      ];
      
      $farmFieldFactory = new FarmFieldFactory();
      foreach($field_info as $name => $info){

		$fields[$name] = $farmFieldFactory->bundleFieldDefinition($info)
					      -> setDisplayConfigurable('form',TRUE)
					      -> setDisplayConfigurable('view', TRUE);

		if($fields[$name]['type'] == 'entity_reference'){
			$fields[$name]->setDisplayOptions('form', array(
				'type' => 'options_select'
			));
		}

      }
      
      return $fields;
    }

}