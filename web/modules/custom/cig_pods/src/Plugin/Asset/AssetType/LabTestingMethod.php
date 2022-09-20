<?php

// cig_pods namespace implies this file depends on the cig_pods module
namespace Drupal\cig_pods\Plugin\Asset\AssetType;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;
use Drupal\farm_field\FarmFieldFactory;
/**
   * Provides the LabTestingMethod asset type.
   *
   * @AssetType(
   * id = "lab_testing_method",
   * label = @Translation("LabTestingMethod"),
   * description = @Translation("LabTestingMethod")
   * )
   */
class LabTestingMethod extends FarmAssetType {


   /**
    * {@inheritdoc}
    */

    public function buildFieldDefinitions() {

      //
      $fields = parent::buildFieldDefinitions();

      // We do not add a "Name" field because we inherit that from the FarmAssetType class


      $field_info = [
        'field_lab_method_name' => [
            'type'  => 'string',
            'label' => 'Lab Method Name',
            'description' => $this->t('Lab Test Method Name'),
            'required' => TRUE,
		],
        'field_lab_method_project' => [
            'type' => 'entity_reference',
            'label' => 'Lab Method Project',
            'description' => $this->t('Lab Test Method Project'),
            'target_type' => 'asset',
            'target_bundle' => 'project',
            'handler' => 'default',
            'required' => TRUE,
            'multiple' => FALSE,
        ],
        'field_lab_method_aggregate_stability_unit' => [
            'type'  => 'entity_reference',
            'label' => 'Aggregate Stability Unit',
            'description' => $this->t('Lab Test Method Aggregate Stability Unit'),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_aggregate_stability_un',
			'handler' => 'default',
            'required' => TRUE,
            'multiple' => FALSE,
		],
        'field_lab_method_aggregate_stability_method' => [
            'type'  => 'entity_reference',
            'label' => 'Aggregate Stability Method',
            'description' => $this->t('Lab Test Method Aggregate Stability Method'),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_aggregate_stability_me',
			'handler' => 'default',
            'required' => TRUE,
            'multiple' => FALSE,
		],
        'field_lab_method_respiration_incubation_days' =>[
            'type'  => 'entity_reference',
            'label' => 'Respiration Incubation Days',
            'target_type' => 'taxonomy_term',
            'target_bundle' => 'd_respiration_incubation',
            'description' => $this->t('Lab Test Method Respiration Incubation Days'),
            'required' => TRUE,
            'multiple' => FALSE,
		],
        'field_lab_method_respiration_detection_method' =>[
            'type'  => 'entity_reference',
            'label' => 'Respiration Detection Method',
            'description' => $this->t('Lab Test Method Respiration Detection Method'),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_respiration_detection_',
			'handler' => 'default',
            'required' => TRUE,
            'multiple' => FALSE,
		],
        'field_lab_method_bulk_density_core_diameter' =>[
            'type' => 'fraction',
            'label' => 'Bulk Density Core Diameter',
            'description' => $this->t('Lab Test Method Bulk Density Core'),
            'required' => TRUE,
        ],
        'field_lab_method_bulk_density_volume' =>[
            'type' => 'fraction',
            'label' => 'Bulk Density Volume',
            'description' => $this->t('Lab Test Method Bulk Density Volume'),
            'required' => TRUE,
        ],
        'field_lab_method_infiltration_method' => [
            'type'  => 'entity_reference',
            'label' => 'Infiltration Method',
            'description' => $this->t('Lab Test Method Infiltration Method'),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_infiltration_method',
			'handler' => 'default',
            'required' => FALSE,
            'multiple' => FALSE,
		],
		'field_lab_method_electroconductivity_method' => [
            'type'  => 'entity_reference',
            'label' => 'Electroconductivity Method',
            'description' => $this->t('Lab Test Method Electroconductivity Method'),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_ec_method',
			'handler' => 'default',
            'required' => FALSE,
            'multiple' => FALSE,
		],
		'field_lab_method_nitrate_n_method' => [
            'type'  => 'entity_reference',
            'label' => 'Nitrate-N Method',
            'description' => $this->t('Lab Test Method Nitrate N Method'),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_nitrate_n_method',
			'handler' => 'default',
            'required' => FALSE,
            'multiple' => FALSE,
		],
		'field_lab_method_phosphorus_method' => [
            'type'  => 'entity_reference',
            'label' => 'Phosphorus Method',
            'description' => $this->t('Lab Test Method Phosphorus Method'),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => TRUE,
            'multiple' => FALSE,
		],
		'field_lab_method_potassium_method' => [
            'type'  => 'entity_reference',
            'label' => 'Potassium Method',
            'description' => $this->t('Lab Test Method Potassium Method'),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => TRUE,
            'multiple' => FALSE,
		],
		'field_lab_method_calcium_method' => [
            'type'  => 'entity_reference',
            'label' => 'Calcium Method',
            'description' => $this->t('Lab Test Method Calcium Method'),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => TRUE,
            'multiple' => FALSE,
		],
		'field_lab_method_magnesium_method' => [
            'type'  => 'entity_reference',
            'label' => 'Magnesium Method',
            'description' => $this->t('Lab Test Method Magnesium Method'),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => TRUE,
            'multiple' => FALSE,
		],
		'field_lab_method_sulfur_method' => [
            'type'  => 'entity_reference',
            'label' => 'Sulfur Method',
            'description' => $this->t('Lab Test Method Sulfur Method'),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => TRUE,
            'multiple' => FALSE,
		],
		'field_lab_method_iron_method' => [
            'type'  => 'entity_reference',
            'label' => 'Iron Method',
            'description' => $this->t('Lab Test Method Iron Method'),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => TRUE,
            'multiple' => FALSE,
		],
		'field_lab_method_manganese_method' => [
            'type'  => 'entity_reference',
            'label' => 'Magnanese Method',
            'description' => $this->t('Lab Test Method Manganese Method'),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => TRUE,
            'multiple' => FALSE,
		],
		'field_lab_method_copper_method' => [
            'type'  => 'entity_reference',
            'label' => 'Copper Method',
            'description' => $this->t('Lab Test Method Copper Method'),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => TRUE,
            'multiple' => FALSE,
		],
		'field_lab_method_zinc_method' => [
            'type'  => 'entity_reference',
            'label' => 'Zinc Method',
            'description' => $this->t('Lab Test Method Zinc Method'),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => TRUE,
            'multiple' => FALSE,
		],
		'field_lab_method_boron_method' => [
            'type'  => 'entity_reference',
            'label' => 'Boron Method',
            'description' => $this->t('Lab Test Method Boron Method'),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => TRUE,
            'multiple' => FALSE,
		],
		'field_lab_method_aluminum_method' => [
            'type'  => 'entity_reference',
            'label' => 'Aluminum Method',
            'description' => $this->t('Lab Test Method Aluminum Method'),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => FALSE,
            'multiple' => FALSE,
		],
		'field_lab_method_molybdenum_method' => [
            'type'  => 'entity_reference',
            'label' => 'Molybdenum Method',
            'description' => $this->t('Lab Test Method Molybdenum Method'),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_soil_health_extraction',
			'handler' => 'default',
            'required' => FALSE,
            'multiple' => FALSE,
        ],
        'field_lab_soil_test_laboratory' => [
            'type'  => 'entity_reference',
            'label' => 'Soil Health Test Laboratory',
            'description' => $this->t('Lab Test Method d_laboratory'),
			'target_type' => 'taxonomy_term',
			'target_bundle' => 'd_laboratory',
			'handler' => 'default',
            'required' => TRUE,
            'multiple' => FALSE,
        ],
        'project' =>[
          'label' => 'Project',
          'type' => 'entity_reference',
          'target_type' => 'asset',
          'target_bundle' => 'project',
          'required' => TRUE,
          'multiple' => TRUE,
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