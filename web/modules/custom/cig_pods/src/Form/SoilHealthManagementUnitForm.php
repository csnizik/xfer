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
		$options[""] = '- Select -';
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
		$options[""] = '- Select -';
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
		 $options[""] = '- Select -';
		$taxonomy_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
			['vid' => 'd_tillage_system']);
		$keys = array_keys($taxonomy_terms);
		foreach($keys as $key){
			$term = $taxonomy_terms[$key];
			$options[$key] = $term -> getName();
		}
		return $options;
	}

	public function getYearOptions(){
		$month_options = [];
		$month_keys =  ["J","F","M","A","M","J","J","A","S","O","N","D"];
		$i = 0;
		foreach($month_keys as $month_key) {
		  $month_options[$i] = $month_key;
		  $i++;
		}
		$i = 0;


		return $month_options;
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
		$options[""] = '- Select -';
		$taxonomy_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
			['vid' => 'd_land_use']);
		$keys = array_keys($taxonomy_terms);
		foreach($keys as $key){
			$term = $taxonomy_terms[$key];
			$options[$key] = $term -> getName();
		}
		
		// dpm($options);
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
		// dpm("building form");
		$is_edit = $id <> NULL;
		$irrigating = false;
		$shmu = NULL;

		if ($form_state->get('load_done') == NULL){
			$form_state->set('load_done', FALSE);
		}

		// Determine if it is an edit process. If it is, load SHMU into local variable.
		if($is_edit){
			$form_state->set('operation','edit');
			$form_state->set('shmu_id', $id);
			$shmu = \Drupal::entityTypeManager()->getStorage('asset')->load($id);
			$shmu_db_crop_rotations = $this->getCropRotationIdsForShmu($shmu);
			if(!$form_state->get('load_done')){
				// dpm("Performing initial load of Crop rotations");
				$this->loadCropRotationsIntoFormState($shmu_db_crop_rotations, $form_state);
				$form_state->set('load_done',TRUE);
			}
			// dpm("Current rotations:");
			// dpm($form_state->get('rotations'));
			// The list of Crop Rotation assets that
			$form_state->set('original_crop_rotation_ids', $shmu_db_crop_rotations);
		} else {
			if(!$form_state->get('load_done')){
				// dpm("Performing initial load of Crop rotations");
				$this->loadCropRotationsIntoFormState([], $form_state);
				$form_state->set('load_done',TRUE);
			}
			// $this->loadCropRotationsIntoFormState([], $form_state);
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
			if($producer <> NULL && $producer->target_id <> NULL){
				$field_shmu_involved_producer_value = $producer->target_id;
			}
		}

		$producer_select_options = $this->getProducerOptions();
		$form['field_shmu_involved_producer'] = [
			'#type' => 'select',
			'#title' => $this->t('Select a Producer'),
			'#options' => $producer_select_options,
			'#default_value' => $field_shmu_involved_producer_value,
			'#required' => TRUE
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
			'#required' => TRUE
		];

		$field_shmu_replicate_number_value = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($shmu, 'field_shmu_replicate_number'): '';
		$form['field_shmu_replicate_number'] = [
			'#type' => 'number',
			'#title' => $this->t('Replicate Number'),
			'#description' => '',
			'#default_value' => $field_shmu_replicate_number_value,
			'#min_value' => 0,
			'#step' => 1, // We enforce integer with step = 1.
			'#required' => TRUE
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

		$field_shmu_experimental_design_value = $is_edit ? $shmu->get('field_shmu_experimental_design')->target_id: '';
		$shmu_experimental_design_options = $this->getExperimentalDesignOptions();
		$form['field_shmu_experimental_design'] = [
			'#type' => 'select',
			'#title' => $this->t('Experimental Design'),
			'#options' => $shmu_experimental_design_options,
			'#default_value' => $field_shmu_experimental_design_value,
			'#required' => TRUE
		];
		$form['static_0']['label'] = [
			'#markup' => '<div> Project Summary <b> (Will be populated with related project summary once Drupal roles are established) </b> </div>'
		];
		$form['static_0']['content'] = [
			'#markup' => '<div> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Adipiscing elit pellentesque habitant morbi tristique senectus et. Volutpat sed cras ornare arcu dui vivamus. Pellentesque id nibh tortor id aliquet lectus proin. Accumsan lacus vel facilisis volutpat est velit egestas. In massa tempor nec feugiat nisl pretium. Neque egestas congue quisque egestas diam in arcu cursus euismod. Egestas tellus rutrum tellus pellentesque eu tincidunt tortor. Tellus orci ac auctor augue mauris augue neque. Diam sit amet nisl suscipit.</div>'
		];
		// New section (Geometry entry)
		$form['subform_3'] = [
			'#markup' => '<div class="subform-title-container"><h2>Soil Health Management Unit (SHMU) Area</h2><h4>3 Fields | Section 3 of 11</h4> </div>'
		];

		$form['static_1']['content'] = [
			'#markup' => '<div>Draw your SHMU on the Map</div>',
		];

		$form['mymap'] = [
			'#type' => 'farm_map_input',
			 '#required' => TRUE,
			'#map_type' => 'pods',
			 '#behaviors' => [
				'zoom_us',
       		 	 'wkt_refresh',
      		],
			'#display_raw_geometry' => TRUE,
			'#default_value' => $is_edit ? $shmu->get('field_geofield')->value : '',
  			// '#default_value' => 'POINT(38.598964 -99.851931)',
		];

		// TODO: Add read only of project summary (Ask Justin)
		// TODO: Refine with Justin whether Lat/Longs are appropriate

		// New section (Soil and Treatment Identification)
		$form['subform_4'] = [
			'#markup' => '<div class="subform-title-container"><h2>Soil and Treatment Identification</h2><h4>2 Fields | Section 4 of 11</h4> </div>'
		];
		$form['dominant_map_unit_symbol'] = [
			'#markup' => '<div> Dominant Map Unit Symbol <br> Data fed from SSURGO <br> <br> <br> </div>' // TODO: do with CSS
		];
		$form['dominant_surface_texture'] = [
			'#markup' => '<div> Dominant Surface Texture <br> Data fed from SSURGO </div>'
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
			'#required' => TRUE
		];

		$field_shmu_current_land_use_modifiers_value = $is_edit ? $this-> getDefaultValuesArrayFromMultivaluedSHMUField($shmu, 'field_shmu_current_land_use_modifiers') : [];

		$form['field_shmu_current_land_use_modifiers'] = [
			'#type' => 'checkboxes',
			'#title' => $this->t('Current Land Use Modifiers'),
			'#options' => $land_use_modifier_options,
			'#default_value' => $field_shmu_current_land_use_modifiers_value,
			'#required' => TRUE
		];

		// New section (Overview of the Production System)
		$form['subform_6'] = [
			'#markup' => '<div class="subform-title-container"><h2>Overview of the Production System</h2><h4>5 Fields | Section 6 of 11</h4> </div>'
		];


		$form['static']['crop_rotation_description'] = [
			'#markup' => '<div> Crop rotation </div>',
		];

		$form['static']['crop_rotation_description_sequence'] = [
			'#markup' => '<div> Overview of Crop Rotation Sequence </div>'
		];

		$form['crop_sequence'] = [
			'#prefix' => '<div id ="crop_sequence">',
			'#suffix' => '</div>',
		];
		// Get Options for Year and Crop Dropdowns
		$crop_options = $this->getCropOptions();
		$crop_options[''] = '-- Select --';

		$crop_rotation_years_options = $this->getCropRotationYearOptions();
		$crop_rotation_years_options[''] = '-- Select --';

		$month_lookup = ["J","F","M","A","M","J","J","A","S","O","N","D"];
		$month_options = $this->getYearOptions();

		$fs_crop_rotations = $form_state -> get('rotations');

		$num_crop_rotations = 1;

		if(count($fs_crop_rotations) <> 0){
			$num_crop_rotations = count($fs_crop_rotations);
		}


		$form_index = 0; // Not to be confused with rotation
		foreach($fs_crop_rotations as $fs_index => $rotation  ){

			$crop_default_value = ''; // Default value for empty Rotation
			$crop_year_default_value = ''; // Default value for empty rotation
			$crop_months_present_lookup = []; // Default value for empty rotation

			$crop_default_value = $rotation['field_shmu_crop_rotation_crop'][0]['target_id'];
			$crop_years_default_value = $rotation['field_shmu_crop_rotation_year'][0]['numerator'];
			$crop_months_present_lookup_raw = $rotation['field_shmu_crop_rotation_crop_present']; // Of type array

			foreach($crop_months_present_lookup_raw as $key=>$value){
				$crop_months_present_lookup[] = $value['numerator']; // Array of values, where val maintains 0 <= val < 12 for val in values
			}

			// dpm("Rotation with fs_index:$fs_index is being shown at form_index:$fs_index");

			$form['crop_sequence'][$fs_index] = [
				'#prefix' => '<div id="crop_rotation">',
				'#suffix' => '</div>',
			];

			$form['crop_sequence'][$fs_index]['field_shmu_crop_rotation_year'] = [
				'#type' => 'select',
				'#title' => 'Year',
				'#required' => TRUE,
				'#options' => $crop_rotation_years_options,
				'#default_value' => $crop_years_default_value,
			];
			$form['crop_sequence'][$fs_index]['field_shmu_crop_rotation_crop'] = [
				'#type' => 'select',
				'#title' => 'Crop',
				'#required' => TRUE,
				'#options' => $crop_options,
				'#default_value' => $crop_default_value
			];
			$form['crop_sequence'][$fs_index]['month_wrapper'] = [
				'#prefix' => '<div id="crop_rotation_months"',
				'#suffix' => '</div>',
			];
			// for( $month = 0; $month < 12 ; $month++ ){
			// 	$form['crop_sequence'][$fs_index]['month_wrapper'][$month]['is_present'] = [
			// 		'#title' => $month_lookup[$month],
			// 		'#title_display' => 'before', // TODO: ask if we want to hide on subsequent sections
			// 		'#type' => 'checkbox',
			// 		'#return_value' => True,
			// 		'#default_value' => in_array($month, $crop_months_present_lookup),
			// 	];
			// }

			// dpm($crop_months_present_lookup);

			$form['crop_sequence'][$fs_index]['month_wrapper']['field_shmu_crop_rotation_crop_present'] = [
				'#type' => 'checkboxes',
				'#title' => '',
				'#title_display' => 'before',
				'#options' => $month_options,
				'#default_value' => $crop_months_present_lookup, // List of months present on that db
			];
			$form['crop_sequence'][$fs_index]['actions']['delete'] = [
				'#type' => 'submit',
				'#name' => $fs_index,
				'#submit' => ['::deleteCropRotation'],
				'#ajax' => [
					'callback' => "::deleteCropRotationCallback",
					'wrapper' => 'crop_sequence',
				],
				'#value' => 'X',
			];

			// Very important
			$form_index = $form_index + 1;
			// End very important
		}

		// Add another button
		$form['crop_sequence']['actions']['addCrop'] = [
			'#type' => 'submit',
			'#submit' => ['::addAnotherCropRotation'],
			'#ajax' => [
				'callback' => '::addAnotherCropRotationCallback',
				'wrapper' => 'crop_sequence',
			],
			'#value' => 'Add to Sequence',
		];

		// New section (Cover Crop History)
		// TODO: Add "Years of cover Cropping Field"
		$form['subform_7'] = [
			'#markup' => '<div class="subform-title-container"> <h2> Cover Crop History </h2> <h4> 1 Field | Section 7 of 11</h4> </div>'
		];

		$field_shmu_initial_crops_planted = $is_edit ? $this-> getDefaultValuesArrayFromMultivaluedSHMUField($shmu, 'field_shmu_initial_crops_planted') : [];

		$form['field_shmu_initial_crops_planted'] = [
			'#type' => 'checkboxes',
			'#required' => TRUE,
			'#title' => 'What Crops are Currently Planted',
			'#options' => $crop_options,
			'#default_value' => $field_shmu_initial_crops_planted,
		] ;

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
			'#required' => TRUE
		];
		$field_years_in_current_tillage_system_value = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($shmu, 'field_years_in_current_tillage_system'): '';

		$form['field_years_in_current_tillage_system'] = [
			'#type' => 'number',
			'#title' => $this->t('Years in Current Tillage System'),
			'#min_value' => 0,
			'#step' => 1, // Int
			'#description' => '',
			'#default_value' => $field_years_in_current_tillage_system_value,
			'#required' => TRUE
		];

		$field_shmu_previous_tillage_system_value = $is_edit ? $shmu->get('field_shmu_previous_tillage_system')->target_id : '';
		$form['field_shmu_previous_tillage_system'] = [
			'#type' => 'select',
			'#title' => $this->t('Previous Tillage System'),
			'#options' => $tillage_system_options,
			'#default_value' => $field_shmu_previous_tillage_system_value,
			'#required' => TRUE
		];
		$field_years_in_prev_tillage_system_value = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($shmu, 'field_years_in_prev_tillage_system'): '';

		$form['field_years_in_prev_tillage_system'] = [
			'#type' => 'number',
			'#min_value' => 0,
			'#step' => 1, // Int
			'#title' => $this->t('Years in Previous Tillage System'),
			'#description' => '',
			'#default_value' => $field_years_in_prev_tillage_system_value,
			'#required' => TRUE
		];

		$form['irrigation_radios'] = [
			'#type' => 'radios',
			'#required' => TRUE,
			'#title' => t('Is this SHMU being irrigated?'),
			'#default_value' => 'no',
			'#options' => [
				'yes' => $this->t('Yes'),
				'no' => $this->t('No')
			],
			'#attributes' => [
				'#name' => 'irrigation_radios',
			],
		];


		$form['subform_etc'] = [
			'#type' => 'item',
			'#markup' => '<p>Remember to add an irrigation sample!<p>',
			'#states' => ['visible' => [
				':input[name="irrigation_radios"]' => ['value' => 'yes'],
				],
			],
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
			'#required' => TRUE
		];

		$form['actions']['send'] = [
			'#type' => 'submit',
			'#value' => 'Save',

		];

		$form['actions']['cancel'] = [
			'#type' => 'submit',
			'#value' => $this->t('Cancel'),
			'#limit_validation_errors' => '',
			'#submit' => ['::redirectAfterCancel'],
		];

		 if($is_edit){
                $form['actions']['delete'] = [
                    '#type' => 'submit',
                    '#value' => $this->t('Delete'),
                    '#submit' => ['::deleteShmu'],
                ];
            }

		return $form;

	}

	public function redirectAfterCancel(array $form, FormStateInterface $form_state){
        $form_state->setRedirect('cig_pods.awardee_dashboard_form');
    }

	public function deleteShmu(array &$form, FormStateInterface $form_state){

        // TODO: we probably want a confirm stage on the delete button. Implementations exist online
        $shmu_id = $form_state->get('shmu_id');
        $shmu = \Drupal::entityTypeManager()->getStorage('asset')->load($shmu_id);

        $shmu->delete();
        $form_state->setRedirect('cig_pods.awardee_dashboard_form');
    }

	/**
	* {@inheritdoc}
	*/
	public function validateForm(array &$form, FormStateInterface $form_state){
		// commented out until farmOS bug with map validation is fixed
		//  parent::validateForm($form, $form_state);
 		//  $values = $form_state->getValues();
 		//  if ($values['mymap'] == '' || $values['mymap'] == "GEOMETRYCOLLECTION EMPTY") {
      	// 	$form_state->setErrorByName('mymap', $this->t('The map is required!'));
   		// }
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

		$ignored_fields = ['send','form_build_id','form_token','form_id','op','actions','irrigation_radios','subform_etc','mymap'];

		$form_values = $form_state->getValues();
		//dpm("+++++++++++++++++++++++++++++++");
		//dpm($form_state->getValues());
		 // ($form_values);

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
	$crop_rotation_fields = ['crop_sequence', 'crop_rotation','field_shmu_crop_rotation_crop','field_shmu_crop_rotation_year','is_present','field_shmu_crop_rotation_sequence'/*, 'field_shmu_initial_crops_planted'*/];

		$shmu = NULL;
		if(!$is_edit){
			$shmu_template = [];
			$shmu_template['type'] = 'soil_health_management_unit';
			$shmu = Asset::create($shmu_template);
		} else {
			// Operation is of type Edit
			$id = $form_state->get('shmu_id'); // TODO: Standardize access
			$shmu = Asset::load($id);
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
				$shmu->set( $key, strtotime( $value ) ); //Set directly on SHMU object
				continue;
			}

			$shmu->set( $key, $value );
		}

		// Map submission logic
		$shmu->set('field_geofield',$form_values['mymap']);


		// TODO: Make Dynamic
		$num_crop_rotations = count($form_values['crop_sequence']); // TODO: Can be calculate dynamically based off of form submit

		$crop_options = $this->getCropOptions();

		$crop_rotation_template = [];
		$crop_rotation_template['type'] = 'shmu_crop_rotation';

		for($rotation = 0; $rotation < $num_crop_rotations; $rotation++ ){

			// If they did not select a crop for the row, do not include it in the save
			if($form_values['crop_sequence'][$rotation]['field_shmu_crop_rotation_crop'] == NULL) continue;

			// We alwasys create a new crop rotation asset for each rotation
			$crop_rotation = Asset::create( $crop_rotation_template );

			// read the crop id from select dropdown for given rotation
			$crop_id = $form_values['crop_sequence'][$rotation]['field_shmu_crop_rotation_crop'];
			$crop_rotation->set( 'field_shmu_crop_rotation_crop', $crop_id );

			// read the crop rotation year from select dropdown for given rotation
			$crop_rotation_year = $form_values['crop_sequence'][$rotation]['field_shmu_crop_rotation_year'];
			$crop_rotation->set( 'field_shmu_crop_rotation_year', $crop_rotation_year );

			#
			$months_present  = Checkboxes::getCheckedCheckboxes($form_values['crop_sequence'][$rotation]['month_wrapper']['field_shmu_crop_rotation_crop_present']);
			$crop_rotation -> set('field_shmu_crop_rotation_crop_present', $months_present);

			$crop_rotation_name = $shmu->getName()." - Crop (".$crop_options[$crop_id].") Rotation - Year ".$crop_rotation_year;
			$crop_rotation->set('name',$crop_rotation_name);
			$crop_rotation->save();
			$crop_rotation_ids[] = $crop_rotation -> id(); // Append ID of SHMU Crop Rotation to list

			// dpm("Created new Crop rotation with ID:"); // Commented for debugging
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

		// Send success message to the user
		$this
			->messenger()
			->addStatus($this
			->t('Form submitted for Soil Health Management Unit', []));
		// Success message done

			$form_state->setRedirect('cig_pods.awardee_dashboard_form');
	}

	// Adds a new row to crop rotation
	public function addAnotherCropRotation(array &$form, FormStateInterface $form_state){
		// dpm("Adding  new rotation");
		$rotations = $form_state->get('rotations');
		$new_crop_rotation = [];
		$new_crop_rotation['field_shmu_crop_rotation_crop'] = array();
		$new_crop_rotation['field_shmu_crop_rotation_crop'][0]['target_id'] = '';
		$new_crop_rotation['field_shmu_crop_rotation_year'][0]['numerator'] = '';
		$new_crop_rotation['field_shmu_crop_rotation_crop_present'] = [];

		// dpm($new_crop_rotation);
		$crop_options = $this->getCropOptions();
		$rotations[] = $new_crop_rotation;
		$form_state->set('rotations', $rotations);
		//dpm("new indices");
		//dpm(array_keys($rotations));
		$form_state->setRebuild(True);

		// foreach($rotations as $key=>$rotation){
		// 	if($rotation[0]['target_id'] <> ''){
		// 		dpm($crop_options[$rotation[0]['target_id']]);
		// 	} else {
		// 		dpm('No target ID');
		// 	}
		// 	dpm($crop_options[$value[]]);
		// 	dpm($this->getAsset($value['id'])->getName());
		// }
		// dpm("New value for rotations in form state");
		// dpm($rotations);
		// dpm("button clicked");
	}

	public function addAnotherCropRotationCallback(array &$form, FormStateInterface $form_state){
		return $form['crop_sequence'];
	}

	public function deleteCropRotation(array &$form, FormStateInterface $form_state){
	    $idx_to_rm = $form_state->getTriggeringElement()['#name'];

		$rotations = $form_state->get('rotations');
		// dpm("old rotations:");
		// dpm($rotations);
		// dpm("old rotations");
		// dpm(array_keys($rotations));
		// dpm($rotations);
		// dpm("Removing rotation with index ".$idx_to_rm);

		unset($rotations[$idx_to_rm]); // Remove the index
		// dpm("new rotation");
		// dpm($rotations);

		// dpm(array_keys($rotations));
		// $rotations = array
		// dpm($rotations);
		$form_state->set('rotations',$rotations);

		// dpm("Num rotations");
		// dpm(count($rotations));
		// // dpm("Rotations:");
		// // dpm($rotations);
		// dpm("Removing rotation at index ".$idx_to_rm);

		// $new_rotations = [];
		// foreach($rotations as $index=> $rotation){
		// 	dpm("Trying");
		// 	dpm($index);
		// 	if($index != $idx_to_rm){
		// 		$new_rotations[] = $rotation;
		// 	} else {
		// 		dpm($rotation);
		// 	}
		// }
		// // dpm("Rotations 2:");
		// // dpm($rotations);
		// // $rotations = array_values($rotations); // Re-index to 0,1,2,3... continuous
		// // dpm("Rotations 3:");
		// // dpm($rotations);
		// // dpm("New rotations");
		// dpm($new_rotations);

		$form_state->setRebuild(True);
	}


	public function deleteCropRotationCallback(array &$form, FormStateInterface $form_state){
		return $form['crop_sequence'];
	}
}