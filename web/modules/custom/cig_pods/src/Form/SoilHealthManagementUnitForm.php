<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;
Use Drupal\Core\Render\Element\Checkboxes;
Use Drupal\Core\Url;


class SoilHealthManagementUnitForm extends FormBase {

	public function getShmuTypeOptions(){
		$options = [];
		$taxonomy_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
			['vid' => 'd_shmu_type']);
		$keys = array_keys($taxonomy_terms);
		foreach($keys as $key){
			$term = $taxonomy_terms[$key];
			$options[$key] = $term -> getName();
		}
		return $options;
	}
	public function getExperimentalDesignOptions(){
		$options = [];
		$taxonomy_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
			['vid' => 'd_experimental_design']);
		$keys = array_keys($taxonomy_terms);
		foreach($keys as $key){
			$term = $taxonomy_terms[$key];
			$options[$key] = $term -> getName();
		}
		return $options;
}
	public function getTillageSystemOptions(){
		$options = [];
		$taxonomy_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
			['vid' => 'd_tillage_system']);
		$keys = array_keys($taxonomy_terms);
		foreach($keys as $key){
			$term = $taxonomy_terms[$key];
			$options[$key] = $term -> getName();
		}
		return $options;
	}

	public function getMajorResourceConcernOptions(){
		$options = [];
		$taxonomy_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
			['vid' => 'd_major_resource_concern']);
		$keys = array_keys($taxonomy_terms);
		foreach($keys as $key){
			$term = $taxonomy_terms[$key];
			$options[$key] = $term -> getName();
		}
		return $options;
	}
	
	public function getResourceConcernOptions(){
		$options = [];
		$taxonomy_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
			['vid' => 'd_resource_concern']);
		$keys = array_keys($taxonomy_terms);
		foreach($keys as $key){
			$term = $taxonomy_terms[$key];
			$options[$key] = $term -> getName();
		}
		return $options;
	}

	public function getLandUseOptions(){
		$options = [];
		$taxonomy_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
			['vid' => 'd_land_use']);
		$keys = array_keys($taxonomy_terms);
		foreach($keys as $key){
			$term = $taxonomy_terms[$key];
			$options[$key] = $term -> getName();
		}
		return $options;
	}

	public function getPracticesAddressedOptions(){
		$options = [];
		$taxonomy_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
			['vid' => 'd_practice']);
		$keys = array_keys($taxonomy_terms);
		foreach($keys as $key){
			$term = $taxonomy_terms[$key];
			$options[$key] = $term -> getName();
		}
		return $options;
	}

	public function getProducerOptions(){
		$producer_assets = \Drupal::entityTypeManager() -> getStorage('asset') -> loadByProperties(
			['type' => 'producer']
		 );
		 $producer_options = [];
		 $producer_options[''] = '- Select -';
		 $producer_keys = array_keys($producer_assets);
		 foreach($producer_keys as $producer_key) {
		   $asset = $producer_assets[$producer_key];
		   $producer_options[$producer_key] = $asset -> getName();
		 }
 
		 return $producer_options;		
	}
	public function getLandUseModifierOptions(){
		$options = [];
		$taxonomy_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
			['vid' => 'd_land_use_modifiers']);
		$keys = array_keys($taxonomy_terms);
		foreach($keys as $key){
			$term = $taxonomy_terms[$key];
			$options[$key] = $term -> getName();
		}
		return $options;
	}

	public function getCropRotationYearOptions(){
		// TODO: Use this to test adding config value
		$max_years = 20; // Maximum number of years for crop rotations.

		$options=[];
		// Include upper bound
		for($i=1; $i < $max_years + 1; $i++){
			$options[$i] = $i;
		}
		return $options;
	}
	public function getCropOptions(){
		$options = [];
		$taxonomy_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
			['vid' => 'd_crop']);
		$keys = array_keys($taxonomy_terms);
		foreach($keys as $key){
			$term = $taxonomy_terms[$key];
			$options[$key] = $term -> getName();
		}
		return $options;
	}

	// goal is to replace this logic
	// $field_shmu_irrigation_water_ph_value = $is_edit ? $shmu->get('field_shmu_irrigation_water_ph')->numerator : '';
	// SHMU is a reference to SoilHealthManagmentUnit entity
	public function getDecimalFromSHMUFractionFieldType(object $shmu, string $field_name){
		return $shmu->get($field_name)-> numerator / $shmu->get($field_name)->denominator;
	}

	// Returns array of options suitable to be passed into '#default_value' for the checkboxes type
	// field_name must be a string relating to a field witn "multiple -> TRUE" in its definition
	public function getDefaultValuesArrayFromMultivaluedSHMUField(object $shmu, string $field_name){
		$field_iter = $shmu->get($field_name);

		$populated_values = [];
		foreach($field_iter as $key => $term){
			$populated_values[] = $term->target_id; // This is the PHP syntax to append to the array
		}
		return $populated_values;
	}
	public function getAsset($id){
		// We use load instead of load by properties here because we are looking by id
		$asset = \Drupal::entityTypeManager()->getStorage('asset')->load($id);
		return $asset;

	}

	// $shmu is expected to be of type EntityInterface
	public function getCropRotationIdsForShmu($shmu){
		$crop_rotation_target_ids = [];

		$field_shmu_crop_rotation_list = $shmu->get('field_shmu_crop_rotation_sequence'); // Expected type of FieldItemList
		foreach($field_shmu_crop_rotation_list as $key=>$value){
			$crop_rotation_target_ids[] = $value->target_id; // $value is of type EntityReferenceItem (has access to value through target_id)
		}
		return $crop_rotation_target_ids;
	}

	// Load from database into form state
	public function loadCropRotationsIntoFormState($crop_rotation_ids, $form_state){
		
		$ignored_fields = ['uuid','revision_id','langcode','type','revision_user','revision_log_message','uid','name', 'status', 'created', 'changed', 'archived', 'default_langcode', 'revision_default'];

		$rotations = [];
		$i = 0;
		foreach($crop_rotation_ids as $key=>$crop_rotation_id){
			$tmp_rotation = $this->getAsset($crop_rotation_id)->toArray();
			$rotations[$i] = array();
			$rotations[$i]['field_shmu_crop_rotation_crop'] = $tmp_rotation['field_shmu_crop_rotation_crop'];
			$rotations[$i]['field_shmu_crop_rotation_year'] = $tmp_rotation['field_shmu_crop_rotation_year'];
			$rotations[$i]['field_shmu_crop_rotation_crop_present'] = $tmp_rotation['field_shmu_crop_rotation_crop_present'];
			$i++; 
		}
		
		// If rotations is still empty, set a blank crop rotation at index 0 
		if($i == 0){
			$rotations[0]['field_shmu_crop_rotation_year'] = '';
			$rotations[0]['field_shmu_crop_rotation_year'] = '';
			$rotations[0]['field_shmu_crop_rotation_crop_present'] = [];
		}
		$form_state->set('rotations', $rotations);
		// dpm($rotations);
		
		return;
	}


	// TODO: check that producer reference saves correctly
	/**
	* {@inheritdoc}
	*/
	public function buildForm(array $form, FormStateInterface $form_state, $id = NULL){
		dpm("building form");
		$is_edit = $id <> NULL;

		$shmu = NULL;

		// Determine if it is an edit process. If it is, load SHMU into local variable.
		if($is_edit){
			if ($form_state->get('load_done') == NULL){
				$form_state->set('load_done', FALSE);
			}
			$form_state->set('operation','edit');
			$form_state->set('shmu_id', $id);
			$shmu = \Drupal::entityTypeManager()->getStorage('asset')->load($id);
			$shmu_db_crop_rotations = $this->getCropRotationIdsForShmu($shmu);
			if(!$form_state->get('load_done')){
				dpm("Performing initial load of Crop rotations");
				$this->loadCropRotationsIntoFormState($shmu_db_crop_rotations, $form_state);
				$form_state->set('load_done',TRUE);
			}
			// dpm("Current rotations:");
			// dpm($form_state->get('rotations'));
			// The list of Crop Rotation assets that 
			$form_state->set('original_crop_rotation_ids', $shmu_db_crop_rotations);
		} else {
			$this->loadCropRotationsIntoFormState([], $form_state);
			$form_state->set('operation','create');
		}

		// Attach the SHMU css library
		$form['#attached']['library'][] = 'cig_pods/soil_health_management_unit_form';
		$form['#tree'] = TRUE; // Allows getting at the values hierarchy in form state

		// First section
		$form['subform_1'] = [
			'#markup' => '<div class="subform-title-container"><h2>Soil Health Management Unit (SHMU) Setup</h2><h4>5 Fields | Section 1 of 11</h4></div>'
		];

		$field_shmu_involved_producer_value = '';
		
		// Look for existing producers on the SHMU
		if($is_edit){
			$producer = $shmu->get('field_shmu_involved_producer');
			if($producer <> NULL && $prod->target_id <> NULL){
				$field_shmu_involved_producer_value = $prod->target_id;
			}  
		}

		$producer_select_options = $this->getProducerOptions();
		$form['field_shmu_involved_producer'] = [
			'#type' => 'select',
			'#title' => $this->t('Select a Producer'),
			'#options' => $producer_select_options,
			'#default_value' => $field_shmu_involved_producer_value,
			'#required' => FALSE
		];
		
		$name_value = $is_edit ? $shmu->get('name')->value : ''; // Default Value: Empty string
		$form['name'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Soil Health Management (SHMU) Name'),
			'#description' => '',
			'#default_value' => $name_value,
			'#required' => TRUE
		];

		$field_shmu_type_value = $is_edit ? $shmu->get('field_shmu_type')->target_id: ''; // Default Value: Empty String
		$shmu_type_options = $this->getShmuTypeOptions();
		$form['field_shmu_type'] = [
			'#type' => 'select',
			'#title' => $this->t('Soil Health Management Unit (SHMU) Type'),
			'#options' => $shmu_type_options,
			'#default_value' => $field_shmu_type_value,
			'#required' => FALSE
		]; 

		$field_shmu_replicate_number_value = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($shmu, 'field_shmu_replicate_number'): '';
		$form['field_shmu_replicate_number'] = [
			'#type' => 'number',
			'#title' => $this->t('Replicate Number'),
			'#description' => '',
			'#default_value' => $field_shmu_replicate_number_value,
			'#min_value' => 0,
			'#step' => 1, // We enforce integer with step = 1.
			'#required' => FALSE
		]; 

		$field_treatmenent_narrative_value = $is_edit ? $shmu->get('field_shmu_treatment_narrative')->value: '';

		$form['field_shmu_treatment_narrative'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Treatment Narrative'),
			'#description' => '',
			'#default_value' => $field_treatmenent_narrative_value,
			'#required' => FALSE
		]; 
		$form['subform_2'] = [
			'#markup' => '<div class="subform-title-container"><h2>Experimental Design</h2><h4>1 Field | Section 2 of 11</h4></div>'
		];
		
		$field_shmu_experimental_design_value = $is_edit ? $shmu->get('field_shmu_experimental_design')->target_ide: '';
		$shmu_experimental_design_options = $this->getExperimentalDesignOptions();
		$form['field_shmu_experimental_design'] = [
			'#type' => 'select',
			'#title' => $this->t('Experimental Design'),
			'#options' => $shmu_experimental_design_options,
			'#required' => FALSE
		];
		// New section (Geometry entry)
		$form['subform_3'] = [
			'#markup' => '<div class="subform-title-container"><h2>Soil Health Management Unit (SHMU) Area</h2><h4>5 Fields | Section 3 of 11</h4> </div>'
		];
		// TODO: Lat/Long input needs decimal precision
		$field_shmu_latitude_value = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($shmu, 'field_shmu_latitude'): '';
		$form['field_shmu_latitude'] = [
			'#type' => 'number',
			'#title' => $this->t('Latitude'),
			'#description' => '',
			'#default_value' => $field_shmu_latitude_value,
			'#min_value' => -90,
			'#max_value' => 90,
			'#step' => 0.000000000000001, // Based off of precision given in FarmOS map.
			'#required' => FALSE
		];
		$field_shmu_longitude_value = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($shmu, 'field_shmu_longitude'): '';

		$form['field_shmu_longitude'] = [
			'#type' => 'number',
			'#title' => $this->t('Longitude'),
			'#description' => '',
			'#default_value' => $field_shmu_longitude_value,
			'#min_value' => -180,
			'#max_value' => 180,
			'#step' => 0.000000000000001, // Based off of precision given in FarmOS map.
			'#required' => FALSE
		];  
		// TODO: Add read only of project summary (Ask Justin)
		// TODO: Refine with Justin whether Lat/Longs are appropriate

		// New section (Soil and Treatment Identification)
		$form['subform_4'] = [
			'#markup' => '<div class="subform-title-container"><h2>Soil and Treatment Identification</h2><h4>2 Fields | Section 4 of 11</h4> </div>'
		];
		
		// New section (Land Use History)
		$form['subform_5'] = [
			'#markup' => '<div class="subform-title-container"><h2> Land Use History </h2><h4> 5 Fields | Section 5 of 11</h4></div>'
		];
		$field_shmu_prev_land_use_value = $is_edit ? $shmu->get('field_shmu_prev_land_use')->target_id : '';
		$land_use_options = $this->getLandUseOptions();
		$form['field_shmu_prev_land_use'] = [
			'#type' => 'select',
			'#title' => $this->t('Previous Land Use'),
			'#options' => $land_use_options,
			'#default_value' => $field_shmu_prev_land_use_value,
			'#required' => FALSE
		];

		$field_shmu_prev_land_use_modifiers_values = $is_edit ? $this-> getDefaultValuesArrayFromMultivaluedSHMUField($shmu, 'field_shmu_prev_land_use_modifiers') : [];
		$land_use_modifier_options = $this->getLandUseModifierOptions(); 
		$form['field_shmu_prev_land_use_modifiers'] = [
			'#type' => 'checkboxes',
			'#title' => $this->t('Previous Land Use Modifiers'),
			'#options' => $land_use_modifier_options,
			'#default_value' => $field_shmu_prev_land_use_modifiers_values,
			'#required' => FALSE
		]; 
		


		// For the Date input fields, we have to convert from UNIX to yyyy-mm-dd 
		if($is_edit){
			// $field_shhmu_date_land_use_changed_value is expected to be a UNIX timestamp
			$field_shmu_date_land_use_changed_value = $shmu->get('field_shmu_date_land_use_changed')[0]->value;
			$default_value_shmu_date_land_use_changed = date("Y-m-d", $field_shmu_date_land_use_changed_value);
		} else {
			$default_value_shmu_date_land_use_changed = '';
		}
		
		$form['field_shmu_date_land_use_changed'] = [
			'#type' => 'date',
			'#title' => $this->t('Date Land Use Changed'),
			'#description' => '',
			'#default_value' => $default_value_shmu_date_land_use_changed, // Default value for "date" field type is a string in form of 'yyyy-MM-dd'
			'#required' => FALSE
		]; 
		
		$field_shmu_current_land_use_value = $is_edit ? $shmu->get('field_shmu_current_land_use')->target_id : '';
		$form['field_shmu_current_land_use'] = [
			'#type' => 'select',
			'#title' => $this->t('Current Land Use'),
			'#options' => $land_use_options,
			'#default_value' => $field_shmu_current_land_use_value,
			'#required' => FALSE
		];

		$field_shmu_current_land_use_modifiers_value = $is_edit ? $this-> getDefaultValuesArrayFromMultivaluedSHMUField($shmu, 'field_shmu_current_land_use_modifiers') : [];

		$form['field_shmu_current_land_use_modifiers'] = [
			'#type' => 'checkboxes',
			'#title' => $this->t('Current Land Use Modifiers'),
			'#options' => $land_use_modifier_options,
			'#default_value' => $field_shmu_current_land_use_modifiers_value,
			'#required' => FALSE
		];

		// New section (Overview of the Production System)
		$form['subform_6'] = [
			'#markup' => '<div class="subform-title-container"><h2>Overview of the Production System</h2><h4>5 Fields | Section 6.2 of 11</h4> </div>'
		];


		$form['crop_sequence'] = [
			'#prefix' => '<div id ="crop_sequence">',
			'#suffix' => '</div>',
		];
		
		$fs_crop_rotations = $form_state -> get('rotations');
		// dpm($fs_crop_rotations);
		
		$num_crop_rotations = 1;
		if($is_edit && count($fs_crop_rotations) <> 0){
			$num_crop_rotations = count($fs_crop_rotations);
		}
		
		// Get Options for Year and Crop Dropdowns
		$crop_options = $this->getCropOptions();
		$crop_options[''] = '-- Select --'; 

		$crop_rotation_years_options = $this->getCropRotationYearOptions();
		$crop_rotation_years_options[''] = '-- Select --';

		$month_lookup = ["J","F","M","A","M","J","J","A","S","O","N","D"];


		// dpm($num_crop_rotations);
		for( $rotation = 0; $rotation < $num_crop_rotations; $rotation++ ){

			$crop_default_value = ''; // Default value for empty Rotation
			$crop_year_default_value = ''; // Default value for empty rotation
			$crop_months_present_lookup = []; // Default value for empty rotation

			// dpm("Brrr 10");
			// dpm(array_keys($fs_crop_rotations));
			// If this rotation is indexed in fs crop rotations
			if(in_array($rotation, array_keys($fs_crop_rotations))){
				// dpm("Populating crop rotation from form state");
				$crop_default_value = $fs_crop_rotations[$rotation]['field_shmu_crop_rotation_crop'][0]['target_id'];
				$crop_years_default_value = $fs_crop_rotations[$rotation]['field_shmu_crop_rotation_year'][0]['numerator'];
				$crop_months_present_lookup_raw = $fs_crop_rotations[$rotation]['field_shmu_crop_rotation_crop_present']; // Of type array
				
				$crop_months_present_lookup = [];
				foreach($crop_months_present_lookup_raw as $key=>$value){
					$crop_months_present_lookup[] = $value['numerator']; // Array of values, where val maintains 0 <= val < 12 for val in values
				}
				// dpm("Values for crop rotation:");
				// dpm($crop_default_value);
				// dpm($crop_years_default_value);
				// dpm($crop_months_present_lookup);
			} else {
				// There's nothing in the form state, don't draw.
				dpm("Skipping rendering Crop rotation section because it is not in the form state (Something bad happened) ");
				// dpm($rotation);
				// dpm($fs_crop_rotations);

				continue;
			}
			// dpm("Rendering Crop rotation section");
			$form['crop_sequence'][$rotation] = [
				'#prefix' => '<div id="crop_rotation">', 
				'#suffix' => '</div>',				
			];

			$form['crop_sequence'][$rotation]['field_shmu_crop_rotation_crop'] = [
				'#type' => 'select',
				'#title' => 'Crop',
				'#options' => $crop_options,
				'#default_value' => $crop_default_value
			];
			$form['crop_sequence'][$rotation]['field_shmu_crop_rotation_year'] = [
				'#type' => 'select',
				'#title' => 'Year',
				'#options' => $crop_rotation_years_options,
				'#default_value' => $crop_years_default_value,
			];
			$form['crop_sequence'][$rotation]['month_wrapper'] = [
				'#prefix' => '<div id="crop_rotation_months"',
				'#suffix' => '</div>',				
			];
			for( $month = 0; $month < 12 ; $month++ ){
				$month_default_value = False;
				$form['crop_sequence'][$rotation]['month_wrapper'][$month]['is_present'] = [
					'#title' => $month_lookup[$month],
					'#title_display' => 'before', // TODO: ask if we want to hide on subsequent sections
					'#type' => 'checkbox',
					'#return_value' => True, 
					'#default_value' => in_array($month, $crop_months_present_lookup),
				];
			}
			$form['crop_sequence'][$rotation]['actions']['send'] = [
				'#type' => 'submit',
				'#value' => 'Delete (Not functional)',
			];
		}
		$form['crop_sequence']['actions']['addCrop'] = [
			'#type' => 'submit',
			'#submit' => ['::addAnotherCropRotation'],
			'#ajax' => [
				'callback' => '::addAnotherCropRotationCallback',
				'wrapper' => 'crop_sequence',
			],
			'#value' => 'Add Another Crop Rotation',
		];

		// New section (Cover Crop History)
		$form['subform_7'] = [
			'#markup' => '<div class="subform-title-container"> <h3> Cover Crop History </h3> <h4> 1 Field | Section 7 of 11</h4> </div>'	
		];

		// New section (Tillage Type)
		$form['subform_8'] = [
			'#markup' => '<div class="subform-title-container"><h2>Tillage Type</h2><h4> 4 Fields | Section 8 of 11</h4></div>'
		];

		$field_current_tillage_system_value = $is_edit ? $shmu->get('field_current_tillage_system')->target_id : '';
		$tillage_system_options = $this->getTillageSystemOptions();
		$form['field_current_tillage_system'] = [
			'#type' => 'select',
			'#title' => $this->t('Current Tillage System'),
			'#options' => $tillage_system_options,
			'#default_value' => $field_current_tillage_system_value,
			'#required' => FALSE
		]; 
		$field_years_in_current_tillage_system_value = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($shmu, 'field_years_in_current_tillage_system'): '';

		$form['field_years_in_current_tillage_system'] = [
			'#type' => 'number',
			'#title' => $this->t('Years in Current Tillage System'),
			'#min_value' => 0,
			'#step' => 1, // Int
			'#description' => '',
			'#default_value' => $field_years_in_current_tillage_system_value,
			'#required' => FALSE
		];
		
		$field_shmu_previous_tillage_system_value = $is_edit ? $shmu->get('field_shmu_previous_tillage_system')->target_id : '';
		$form['field_shmu_previous_tillage_system'] = [
			'#type' => 'select',
			'#title' => $this->t('Previous Tillage System'),
			'#options' => $tillage_system_options,
			'#default_value' => $field_shmu_previous_tillage_system_value,
			'#required' => FALSE
		];
		$field_years_in_prev_tillage_system_value = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($shmu, 'field_years_in_prev_tillage_system'): '';

		$form['field_years_in_prev_tillage_system'] = [
			'#type' => 'number',
			'#min_value' => 0,
			'#step' => 1, // Int
			'#title' => $this->t('Years in Previous Tillage System'),
			'#description' => '',
			'#default_value' => $field_years_in_prev_tillage_system_value,
			'#required' => FALSE
		]; 		 

		// New Section (Irrigation water testing)
		$form['subform_9'] = [
			'#markup' => '<div class="subform-title-container"><h2>Irrigation Water Testing</h2><h4> 9 Fields | Section 9 of 11</h4></div>'
		];		
		$irrigation_in_arid_or_high_options = [];
		$irrigation_in_arid_or_high_options['true'] = 'Yes';
		$irrigation_in_arid_or_high_options['false'] = 'No';
		
		// TODO: Make fields visible based on irrigation selection.
		$field_is_irrigation_in_arid_or_high_value = $is_edit ? $shmu->get('field_is_irrigation_in_arid_or_high')->target_id : '';

		$form['field_is_irrigation_in_arid_or_high'] = [
			'#type' => 'select',
			'#title' => $this->t('Are you Irrigating in Arid Climate or High Tunnel?'),
			'#options' => $irrigation_in_arid_or_high_options,
			'#default_value' => $field_is_irrigation_in_arid_or_high_value,
			'#required' => FALSE
		];
		
		if($is_edit){
			// $ field_shmu_irrigation_sample_date_timestamp is expected to be a UNIX timestamp
			$field_shmu_irrigation_sample_date_timestamp = $shmu->get('field_shmu_irrigation_sample_date')[0]->value;

			$field_shmu_irrigation_sample_date_timestamp_default_value = date("Y-m-d", $field_shmu_irrigation_sample_date_timestamp);
		} else {
			$field_shmu_irrigation_sample_date_timestamp_default_value = NULL; // TODO: Check behavior
		}

		// TODO: This field is not working correctly on create
		// $field_shmu_irrigation_sample_date_value = $is_edit ? $shmu->get('field_shmu_irrigation_sample_date')->value : '';
		$form['field_shmu_irrigation_sample_date'] = [
			'#type' => 'date',
			'#title' => $this->t('Sample Date'),
			'#description' => '',
			'#default_value' => $field_shmu_irrigation_sample_date_timestamp_default_value,
			'#required' => FALSE
		];

		$field_shmu_irrigation_water_ph_value = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($shmu, 'field_shmu_irrigation_water_ph'): '';

		$form['field_shmu_irrigation_water_ph'] = [
			'#type' => 'number',
			'#title' => $this->t('Water pH'),
			'#min_value' => 1,
			'#max_value' => 14,
			'#step' => 0.01, // Float
			'#description' => '',
			'#default_value' => $field_shmu_irrigation_water_ph_value,
			'#required' => FALSE
		];

		$field_shmu_irrigation_sodium_adsorption_ratio_value = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($shmu, 'field_shmu_irrigation_sodium_adsorption_ratio'): '';

		$form['field_shmu_irrigation_sodium_adsorption_ratio'] = [
			'#type' => 'number',
			'#min_value' => 0,
			'#step' => 0.01, // Float
			'#title' => $this->t('Sodium Adsoprtion Ratio'),
			'#description' => '(Unit meq/L)',
			'#default_value' => $field_shmu_irrigation_sodium_adsorption_ratio_value,
			'#required' => FALSE
		]; 
		
		$field_shmu_irrigation_total_dissolved_solids_value = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($shmu, 'field_shmu_irrigation_total_dissolved_solids'): '';

		$form['field_shmu_irrigation_total_dissolved_solids'] = [
			'#type' => 'number',
			'#min_value' => 0,
			'#max_value' => 1000000, // Capped at 1 million because you can't have more than 1 million parts per million
			'#step' => 0.01, // Float
			'#title' => $this->t('Total Dissolved Solids'),
			'#description' => '(Unit ppm)',
			'#default_value' => $field_shmu_irrigation_total_dissolved_solids_value,
			'#required' => FALSE
		];
		

		$field_shmu_irrigation_total_alkalinity_value = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($shmu, 'field_shmu_irrigation_total_alkalinity'): '';

		$form['field_shmu_irrigation_total_alkalinity'] = [
			'#type' => 'number',
			'#min_value' => 0,
			'#max_value' => 1000000, // Capped at 1 million because you can't have more than 1 million parts per million
			'#step' => 0.01, // Float
			'#title' => $this->t('Total Alkalinity'),
			'#description' => '(Unit ppm CaCO3)',
			'#default_value' => $field_shmu_irrigation_total_alkalinity_value,
			'#required' => FALSE
		]; 

		$field_shmu_irrigation_chlorides_value = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($shmu, 'field_shmu_irrigation_chlorides'): '';

		$form['field_shmu_irrigation_chlorides'] = [
			'#type' => 'number',
			'#min_value' => 0,
			'#max_value' => 1000000, // Capped at 1 million because you can't have more than 1 million parts per million
			'#step' => 0.01, // Float
			'#title' => $this->t('Chlorides'),
			'#description' => '(Unit ppm)',
			'#default_value' => $field_shmu_irrigation_chlorides_value,
			'#required' => FALSE
		]; 
		$field_shmu_irrigation_sulfates_value = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($shmu, 'field_shmu_irrigation_sulfates'): '';
		$form['field_shmu_irrigation_sulfates'] = [
			'#type' => 'number',
			'#min_value' => 0,
			'#max_value' => 1000000, // Capped at 1 million because you can't have more than 1 million parts per million
			'#step' => 0.01, // Float
			'#title' => $this->t('Sulfates'),
			'#description' => '(Unit ppm)',
			'#default_value' => $field_shmu_irrigation_sulfates_value,
			'#required' => FALSE
		]; 

		$field_shmu_irrigation_nitrates_value = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($shmu, 'field_shmu_irrigation_nitrates'): '';
		
		$form['field_shmu_irrigation_nitrates'] = [
			'#type' => 'number',
			'#min_value' => 0,
			'#max_value' => 1000000, // Capped at 1 million because you can't have more than 1 million parts per million
			'#step' => 0.01, // Float
			'#title' => $this->t('Nitrates'),
			'#description' => '(Unit ppm)',
			'#default_value' => $field_shmu_irrigation_nitrates_value,
			'#required' => FALSE
		];
		// New section (Additional Concerns or Impacts)
		$form['subform_10'] = [
			'#markup' => '<div class="subform-title-container"><h2>Additional Concerns or Impacts</h2><h4> 2 Fields | Section 10 of 11</h4></div>'
		];
		
		$major_resource_concerns_options = $this->getMajorResourceConcernOptions();

		$field_shmu_major_resource_concern_values = $is_edit ? $this-> getDefaultValuesArrayFromMultivaluedSHMUField($shmu, 'field_shmu_major_resource_concern') : [];

		$form['field_shmu_major_resource_concern'] = [
			'#type' => 'checkboxes',
			'#title' => $this->t('Other Major Resource Concerns'),
			'#options' => $major_resource_concerns_options,
			'#default_value' => $field_shmu_major_resource_concern_values,
			'#required' => FALSE
		];
		
		$field_shmu_resource_concern_values = $is_edit ? $this-> getDefaultValuesArrayFromMultivaluedSHMUField($shmu, 'field_shmu_resource_concern') : [];

		$resource_concern_options = $this->getResourceConcernOptions();
		$form['field_shmu_resource_concern'] = [
			'#type' => 'checkboxes',
			'#title' => $this->t('Other Specific Resource Concerns'),
			'#options' => $resource_concern_options,
			'#default_value' => $field_shmu_resource_concern_values,
			'#required' => FALSE
		]; 
		$form['subform_11'] = [
			'#markup' => '<div class="subform-title-container"><h2>NRCS Practices</h2><h4> 1 Field | Section 11 of 11</h4></div>'
		];

		$field_shmu_practices_addressed_values = $is_edit ? $this-> getDefaultValuesArrayFromMultivaluedSHMUField($shmu, 'field_shmu_practices_addressed') : [];
		$practices_addressed_options = $this->getPracticesAddressedOptions();
		$form['field_shmu_practices_addressed'] = [
			'#type' => 'checkboxes',
			'#title' => $this->t('Practices Addressed'),
			'#options' => $practices_addressed_options,
			'#default_value' => $field_shmu_practices_addressed_values,
			'#required' => FALSE
		];
		
		$form['actions']['send'] = [
			'#type' => 'submit',
			'#value' => 'Save',
			
		];
		
		return $form;

	}

	/**
	* {@inheritdoc}
	*/
	public function validateForm(array &$form, FormStateInterface $form_state){
		return;
	}

	/**
	* {@inheritdoc}
	*/
	public function getFormId() {
		return 'soil_health_management_unit_form';
	}

	/**
	* {@inheritdoc}
	*/
	public function submitForm(array &$form, FormStateInterface $form_state) {
		
		// We aren't interested in some of the attributes that $form_state->getValues() gives us.
		// Tracked in $ignored_fields
		$is_edit = $form_state->get('operation') == 'edit'; 
		$ignored_fields = ['send','form_build_id','form_token','form_id','op','actions'];
		
		$form_values = $form_state->getValues();

		// All of the fields that support multi-select checkboxes on the page
		$checkboxes_fields = ['field_shmu_prev_land_use_modifiers',
						   'field_shmu_current_land_use_modifiers',
						   'field_shmu_major_resource_concern',
						   'field_shmu_resource_concern',
							'field_shmu_practices_addressed'];
		// All of the fields that support date input on the page
		// TODO: This field is not saving on submit 
		$date_fields = ['field_shmu_date_land_use_changed','field_shmu_irrigation_sample_date'];

		// Specialty crop rotation section fields
		$crop_rotation_fields = ['crop_sequence', 'crop_rotation','field_shmu_crop_rotation_crop','field_shmu_crop_rotation_year','is_present','field_shmu_crop_rotation_sequence'];

		$shmu = NULL;
		if(!$is_edit){
			$shmu_template = [];
			$shmu_template['type'] = 'soil_health_management_unit'; 
			$shmu = Asset::create($shmu_template);
		} else {
			// Operation is of type Edit
			$id = $form_state->get('shmu_id'); // TODO: Standardize access
			$shmu = \Drupal::entityTypeManager()->getStorage('asset')->load($id);
		}
		foreach($form_values as $key => $value){
			// If it is an ignored field, skip the loop
			if(in_array($key, $ignored_fields)){ continue; }

			// These fields have special handling. Shown below
			if(in_array($key, $crop_rotation_fields)){ continue; }
			
			if(in_array($key, $checkboxes_fields)){
				// Value is of type array (Multi-select). Use built-in Checkbox method.
				$shmu->set($key,Checkboxes::getCheckedCheckboxes($value)); //Set directly on SHMU object 
				continue;
			}
			if(in_array($key,$date_fields)){
				// $value is expected to be a string of format yyyy-mm-dd
				$shmu->set( $key,strtotime($value) ); //Set directly on SHMU object 
				continue;
			}
			
			$shmu->set( $key,$value );
		}

		// TODO: Make Dynamic
		$num_crop_rotations = count($form_values['crop_sequence']); // TODO: Can be calculate dynamically based off of form submit

		$crop_options = $this->getCropOptions();

		$crop_rotation_template = [];
		$crop_rotation_template['type'] = 'shmu_crop_rotation';

		for($rotation = 0; $rotation < $num_crop_rotations; $rotation++ ){
			dpm("Brrr 4");

			// If they did not select a crop for the row, do not include it in the save
			if($form_values['crop_sequence'][$rotation]['field_shmu_crop_rotation_crop'] == NULL) continue;
			dpm("Brrr 5");

			// We alwasys create a new crop rotation asset for each rotation
			$crop_rotation = Asset::create( $crop_rotation_template );

			// read the crop id from select dropdown for given rotation
			$crop_id = $form_values['crop_sequence'][$rotation]['field_shmu_crop_rotation_crop'];
			$crop_rotation->set( 'field_shmu_crop_rotation_crop', $crop_id );
		
			// read the crop rotation year from select dropdown for given rotation
			$crop_rotation_year = $form_values['crop_sequence'][$rotation]['field_shmu_crop_rotation_year'];
			$crop_rotation->set( 'field_shmu_crop_rotation_year', $crop_rotation_year );
			dpm("Brrr 6");

			$months_present = []; // List of months in which the crop is present in the SHMU for year specified in $crop_rotation_year
			for($month = 0; $month < 12; $month++){
				$crop_present = $form_values['crop_sequence'][$rotation]['month_wrapper'][$month]['is_present'];
				if($crop_present) $months_present[] = $month; 
			}
			dpm("Brrr 7");

			$crop_rotation -> set('field_shmu_crop_rotation_crop_present', $months_present);
			
			// TODO: Possibly change Name
			$crop_rotation_name = $shmu->getName()." - Crop (".$crop_options[$crop_id].") Rotation - Year ".$crop_rotation_year;
			$crop_rotation->set('name',$crop_rotation_name);
			$crop_rotation->save();
			$crop_rotation_ids[] = $crop_rotation -> id(); // Append ID of SHMU Crop Rotation to list
			dpm("Created new Crop rotation with ID:");
			dpm($crop_rotation->id());
		}
		$shmu->set('field_shmu_crop_rotation_sequence', $crop_rotation_ids);
		$shmu->save();

		// Cleanup - remove the old Crop Rotation Assets that are no longer used
		if($is_edit){
			$trash_rotation_ids = $form_state->get('original_crop_rotation_ids');
			foreach($trash_rotation_ids as $key => $id){
				$crop_rotation_old = Asset::load($id);
				$crop_rotation_old->delete();
			}
		}
		// Cleanup done


		// Send success message to user.
		$this
			->messenger()
			->addStatus($this
			->t('Form submitted for Soil Health Management Unit', []));

		$form_state->setRedirect('cig_pods.awardee_dashboard_form');
	}

	// Adds a new row to crop rotation
	public function addAnotherCropRotation(array &$form, FormStateInterface $form_state){
		dpm("Adding  new rotation");
		$rotations = $form_state->get('rotations');
		$new_crop_rotation = [];
		$new_crop_rotation['field_shmu_crop_rotation_crop'] = array();
		$new_crop_rotation['field_shmu_crop_rotation_crop'][0]['target_id'] = '';
		$new_crop_rotation['field_shmu_crop_rotation_year'][0]['numerator'] = '';
		$new_crop_rotation['field_shmu_crop_rotation_crop_present'] = [];

		dpm($new_crop_rotation);
		$rotations[] = $new_crop_rotation;
		$form_state->set('rotations', $rotations);
		$form_state->setRebuild(True);
		dpm("New value for rotations in form state");
		dpm($rotations);
		// dpm("button clicked");
	}

	public function addAnotherCropRotationCallback(array &$form, FormStateInterface $form_state){
		return $form['crop_sequence'];
	}
}