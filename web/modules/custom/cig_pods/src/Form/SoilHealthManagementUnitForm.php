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

	/**
	* {@inheritdoc}
	*/
	public function buildForm(array $form, FormStateInterface $form_state, $id = NULL){
		$is_edit = $id <> NULL;

		// First section
		$form['subform_1'] = [
			'#markup' => '<div class="subform-title-container"><h2>Soil Health Management Unit (SHMU) Setup</h2><h4>5 Fields | Section 1 of 11</h4></div>'
		];
		$producer_select_options = $this->getProducerOptions();
		$form['field_shmu_involved_producer'] = [
			'#type' => 'select',
			'#title' => $this->t('Select a Producer'),
			'#options' => $producer_select_options,
			'#required' => FALSE
		];
		 
		$form['field_shmu_name'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Soil Health Management (SHMU) Name'),
			'#description' => '',
			'#required' => TRUE
		];
		$shmu_type_options = $this->getShmuTypeOptions();
		$form['field_shmu_type'] = [
			'#type' => 'select',
			'#title' => $this->t('Soil Health Management Unit (SHMU) Type'),
			'#options' => $shmu_type_options,
			'#required' => FALSE
		]; 

		$form['field_shmu_replicate_number'] = [
			'#type' => 'number',
			'#title' => $this->t('Replicate Number'),
			'#description' => '',
			'#required' => FALSE
		]; 


		$form['field_shmu_treatment_narrative'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Treatment Narrative'),
			'#description' => '',
			'#required' => FALSE
		]; 
		$form['subform_2'] = [
			'#markup' => '<div class="subform-title-container"><h2>Experimental Design</h2><h4>1 Field | Section 2 of 11</h4></div>'
		];
		
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
		$form['field_shmu_latitude'] = [
			'#type' => 'number',
			'#title' => $this->t('Latitude'),
			'#description' => '',
			'#required' => FALSE
		];
		// TODO: Add read only of project summary (Ask Justin)
		$form['field_shmu_longitude'] = [
			'#type' => 'number',
			'#title' => $this->t('Longitude'),
			'#description' => '',
			'#required' => FALSE
		];  
		// TODO: Refine with Justin whether Lat/Longs are appropriate

		// New section (Soil and Treatment Identification)
		$form['subform_4'] = [
			'#markup' => '<div class="subform-title-container"><h2>Soil and Treatment Identification</h2><h4>2 Fields | Section 4 of 11</h4> <p> Under construction </p> </div>'
		];
		
		// New section (Land Use History)
		$form['subform_5'] = [
			'#markup' => '<div class="subform-title-container"><h2> Land Use History </h2><h4> 5 Fields | Section 5 of 11</h4></div>'
		];
		$land_use_options = $this->getLandUseOptions();
		$form['field_shmu_prev_land_use'] = [
			'#type' => 'select',
			'#title' => $this->t('Previous Land Use'),
			'#options' => $land_use_options,
			'#required' => FALSE
		];
		$land_use_modifier_options = $this->getLandUseModifierOptions(); 
		$form['field_shmu_prev_land_use_modifiers'] = [
			'#type' => 'checkboxes',
			'#title' => $this->t('Previous Land Use Modifiers'),
			'#options' => $land_use_modifier_options,
			'#required' => FALSE
		]; 
		
		$form['field_shmu_date_land_use_changed'] = [
			'#type' => 'date',
			'#title' => $this->t('Date Land Use Changed'),
			'#description' => '',
			'#required' => FALSE
		]; 

		$form['field_shmu_current_land_use'] = [
			'#type' => 'select',
			'#title' => $this->t('Current Land Use'),
			'#options' => $land_use_options,
			'#required' => FALSE
		];

		$form['field_shmu_current_land_use_modifiers'] = [
			'#type' => 'checkboxes',
			'#title' => $this->t('Current Land Use Modifiers'),
			'#options' => $land_use_modifier_options,
			'#required' => FALSE
		];

		// New section (Overview of the Production System)
		$form['subform_6'] = [
			'#markup' => '<div class="subform-title-container"><h2>Overview of the Production System</h2><h4>5 Fields | Section 6 of 11</h4> <p> Under construction</p></div>'
		];

		// New section (Cover Crop History)
		$form['subform_7'] = [
			'#markup' => '<div class="subform-title-container" Cover Crop History <h2> 1 Field | Section 7 of 11</h2> </div>'	
		];

		// New section (Tillage Type)
		$form['subform_8'] = [
			'#markup' => '<div class="subform-title-container"><h2>Tillage Type</h2><h4> 4 Fields | Section 8 of 11</h4></div>'
		];
		$tillage_system_options = $this->getTillageSystemOptions();
		$form['field_current_tillage_system'] = [
			'#type' => 'select',
			'#title' => $this->t('Current Tillage System'),
			'#options' => $tillage_system_options,
			'#required' => FALSE
		]; 

		$form['field_years_in_current_tillage_system'] = [
			// TODO: Check if needs integer number of years
			'#type' => 'number',
			'#title' => $this->t('Years in Current Tillage System'),
			'#description' => '',
			'#required' => FALSE
		]; 
		$form['field_shmu_previous_tillage_system'] = [
			'#type' => 'select',
			'#title' => $this->t('Previous Tillage System'),
			'#options' => $tillage_system_options,
			'#required' => FALSE
		];
		$form['field_years_in_prev_tillage_system'] = [
			// TODO: Check if needs integer number of years
			'#type' => 'number',
			'#title' => $this->t('Years in Previous Tillage System'),
			'#description' => '',
			'#required' => FALSE
		]; 		 

		// New Section (Irrigation water testing)
		$form['subform_9'] = [
			'#markup' => '<div class="subform-title-container"><h2>Irrigation Water Testing</h2><h4> 9 Fields | Section 9 of 11</h4></div>'
		];		
		$irrigation_in_arid_or_high_options = [];
		$irrigation_in_arid_or_high_options['true'] = 'Yes';
		$irrigation_in_arid_or_high_options['false'] = 'No';

		$form['field_is_irrigation_in_arid_or_high'] = [
			'#type' => 'select',
			'#title' => $this->t('Are you Irrigating in Arid Climate or High Tunnel?'),
			'#options' => $irrigation_in_arid_or_high_options,
			'#required' => FALSE
		];
		
		$form['field_shmu_irrigation_sample_date'] = [
			'#type' => 'date',
			'#title' => $this->t('Sample Date'),
			'#description' => '',
			'#required' => FALSE
		];
		$form['field_shmu_irrigation_water_ph'] = [
			'#type' => 'number',
			'#title' => $this->t('Water pH'),
			'#description' => '',
			'#required' => FALSE
		];
		$form['field_shmu_irrigation_sodium_absorption_ratio'] = [
			'#type' => 'number',
			'#title' => $this->t('Sodium Adsoprtion Ratio'),
			'#description' => '',
			'#required' => FALSE
		]; 
		
		$form['field_shmu_irrigation_total_dissolved_solids'] = [
			'#type' => 'number',
			'#title' => $this->t('Total Dissolved Solids'),
			'#description' => '',
			'#required' => FALSE
		]; 
		$form['field_shmu_irrigation_total_alkalinity'] = [
			'#type' => 'number',
			'#title' => $this->t('Total Alkalinity'),
			'#description' => '',
			'#required' => FALSE
		]; 
		$form['field_shmu_irrigation_chlorides'] = [
			'#type' => 'number',
			'#title' => $this->t('Chlorides'),
			'#description' => '',
			'#required' => FALSE
		]; 
		$form['field_shmu_irrigation_sulfates'] = [
			'#type' => 'number',
			'#title' => $this->t('Sulfates'),
			'#description' => '',
			'#required' => FALSE
		]; 
		$form['field_shmu_irrigation_nitrates'] = [
			'#type' => 'number',
			'#title' => $this->t('Nitrates'),
			'#description' => '',
			'#required' => FALSE
		];
		// New section (Additional Concerns or Impacts)
		$form['subform_10'] = [
			'#markup' => '<div class="subform-title-container"><h2>Additional Concerns or Impacts</h2><h4> 2 Fields | Section 10 of 11</h4></div>'
		];
		$major_resource_concerns_options = $this->getMajorResourceConcernOptions();
		$form['field_shmu_major_resource_concern'] = [
			'#type' => 'checkboxes',
			'#title' => $this->t('Other Major Resource Concerns'),
			'#options' => $major_resource_concerns_options,
			'#required' => FALSE
		];

		$resource_concern_options = $this->getResourceConcernOptions();
		$form['field_shmu_resource_concern'] = [
			'#type' => 'checkboxes',
			'#title' => $this->t('Other Specific Resource Concerns'),
			'#options' => $resource_concern_options,
			'#required' => TRUE
		]; 
		$form['subform_11'] = [
			'#markup' => '<div class="subform-title-container"><h2>NRCS Practices</h2><h4> 1 Field | Section 11 of 11</h4></div>'
		];
		$practices_addressed_options = $this->getPracticesAddressedOptions();
		$form['field_shmu_practices_addressed'] = [
			'#type' => 'checkboxes',
			'#title' => $this->t('Practices Addressed'),
			'#options' => $practices_addressed_options,
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
		$values = $form_state->getValues();
		// dpm($values);

		// All of the field types that support multi-select on the page
		$checkbox_types = ['field_shmu_prev_land_use_modifiers',
						   'field_shmu_current_land_use_modifiers',
						   'field_shmu_major_resource_concern',
						   'field_shmu_resource_concern',
							'field_shmu_practices_addressed'];
		// To be submitted to asset creation
		$shmu_submission = [];
		$shmu_submission['type'] = 'soil_health_management_unit';
		foreach($values as $key => $value){
			if(in_array($key,$checkbox_types)){
				// Value is of type array (Multi-select)
				$shmu_submission[$key] =  Checkboxes::getCheckedCheckboxes($value);
			} else {
				// Value is primative or reference
				$shmu_submission[$key] = $value;
			}
		}

		$shmu = Asset::create($shmu_submission);
		$shmu -> save();

		$this
			->messenger()
			->addStatus($this
			->t('Form submitted for Soil Helath Management Unit @shmu_name', [
			'@shmu_name' => $form['shmu_name']['#value'],
		]));

	}
}