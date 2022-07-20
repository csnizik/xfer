<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;
Use Drupal\Core\Url;

class SoilHealthSampleForm extends FormBase {

    public function getSHMUOptions() {
		$shmu_assets = \Drupal::entityTypeManager() -> getStorage('asset') -> loadByProperties(
		   ['type' => 'soil_health_management_unit']
		);
		$shmu_options = [];
		$shmu_options[''] = '- Select -';
		$shmu_keys = array_keys($shmu_assets);
		foreach($shmu_keys as $shmu_key) {
		  $asset = $shmu_assets[$shmu_key];
		  $shmu_options[$shmu_key] = $asset -> getName();
		}

		return $shmu_options;
	}

	public function getEquipmentUsedOptions(){
		$equipment_used_options = array();
		$equipment_used_options[''] = ' - Select -';
    $equipment_used_terms = \Drupal::entityTypeManager() -> getStorage('taxonomy_term') -> loadByProperties(
   			['vid' => 'd_equipment']
   		);

   		$equipment_used_keys = array_keys($equipment_used_terms);

   		 foreach($equipment_used_keys as $equipment_used_key) {
   		   $term = $equipment_used_terms[$equipment_used_key];
   		   $equipment_used_options[$equipment_used_key] = $term -> getName();
   		 }

		return $equipment_used_options;
	}

	public function getPlantOptions() {
		$plant_stage_options = [];
		$plant_stage_options[''] = ' - Select -';
		$plant_stage_terms = \Drupal::entityTypeManager() -> getStorage('taxonomy_term') -> loadByProperties(
			['vid' => 'd_plant_stage']
		);

		 $plant_stage_keys = array_keys($plant_stage_terms);

		 foreach($plant_stage_keys as $plant_stage_key) {
		   $term = $plant_stage_terms[$plant_stage_key];
		   $plant_stage_options[$plant_stage_key] = $term -> getName();
		 }

		return $plant_stage_options;
	}

    public function buildSampleInformationSection(array &$form, FormStateInterface &$form_state, $is_edit = NULL, $sample_collection = NULL){
		$form['form_title'] = [
			'#markup' => '<h1 id="form-title">Sample Collection</h1>'
		];

		$form['subform_1'] = [
			'#markup' => '<div class="subform-title-container"><h2>Sample Information</h2><h4>6 Fields | Section 1 of 2</h4></div>'
		];

        $shmu_options = $this->getSHMUOptions();
		$shmu_default_value = $is_edit ?  $sample_collection->get('field_shmu_id')->target_id : '';
		$form['shmu'] = [
		  '#type' => 'select',
		  '#title' => t('Select a Soil Health Management Unit (SHMU)'),
		  '#options' => $shmu_options,
		  '#default_value' => $shmu_default_value,
		  '#required' => TRUE,
		];

		$date_default_value = '';
		if($is_edit && $sample_collection){
            // $field_shhmu_date_land_use_changed_value is expected to be a UNIX timestamp
            $prev_date_value = $sample_collection->get('field_soil_sample_collection_dat')[0]->value;
            $date_default_value = date("Y-m-d", $prev_date_value);
        }
        $form['sample_collection_date'] = [
            '#type' => 'date',
            '#title' => t('Sample Collection Date'),
            '#date_label_position' => 'within',
			'#default_value' => $date_default_value,
            '#required' => TRUE,
        ];

        $equipment_used_options = $this->getEquipmentUsedOptions();
		$equipment_used_default_value = $is_edit ?  $sample_collection->get('field_equipment_used')->value : '';
        $form['equipment_used'] = [
            '#type' => 'select',
            '#title' => t('Equipment Used'),
            '#options' => $equipment_used_options,
			'#default_value' => $equipment_used_default_value,
            '#required' => TRUE,
        ];

    $diameter_default_value = $is_edit ?  $sample_collection->get('field_diameter')->value : '';
  	$form['field_diameter'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Probe Diameter'),
			'$description' => 'Diameter',
			'#default_value' => $diameter_default_value,
			'#required' => FALSE,
		];

		$soil_sample_default_value = $is_edit ?  $sample_collection->get('name')->value : '';
		$form['soil_sample_id'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Soil Sample ID'),
			'$description' => 'Soil Sample ID',
			'#default_value' => $soil_sample_default_value,
			'#required' => TRUE,
		];

        $plant_options = $this->getPlantOptions();
		$plant_stage_default_value = '';
		if($is_edit && $sample_collection) {
			$plant_stage_default_value = $sample_collection->get('field_plant_stage_at_sampling')->target_id;
		}
        $form['plant_stage_at_sampling'] = [
            '#type' => 'select',
            '#title' => t('Plant Stage at Sampling'),
            '#options' => $plant_options,
			'#default_value' => $plant_stage_default_value,
            '#required' => TRUE,
        ];

		$sample_depth_default_value = $is_edit ? $sample_collection->get('field_sampling_depth')->value : '';
        $form['sample_depth'] = [
			'#type' => 'number',
			'#title' => $this->t('Sampling Depth (Unit Inches)'),
			'#step' => 1,
			'$description' => 'In feet',
			'#default_value' => $sample_depth_default_value,
			'#required' => TRUE,
		];
    }

	public function buildGPSPointsSection(array &$form, FormStateInterface &$form_state, $options = NULL){

		$form['subform_2'] = [
			'#markup' => '<div class="subform-title-container"><h2>GPS Points</h2><h4>6 Fields | Section 2 of 2</h4></div>'
		];

		$form['latitude1'] = [//5 decimal
			'#type' => 'number',
			'#title' => $this->t('Latitude'),
			'#description' => '',
			//'#default_value' => $field_shmu_latitude_value,
			'#min_value' => -90,
			'#max_value' => 90,
			'#step' => 0.00001,
			'#required' => TRUE
		];

		$form['longitude1'] = [
			'#type' => 'number',
			'#title' => $this->t('Longitude'),
			'#description' => '',
			//'#default_value' => $field_shmu_longitude_value,
			'#min_value' => -180,
			'#max_value' => 180,
			'#step' => 0.00001, // Based off of precision given in FarmOS map.
			'#required' => TRUE
		];

		$form['latitude2'] = [
			'#type' => 'number',
			'#title' => $this->t('Latitude'),
			'#description' => '',
			//'#default_value' => $field_shmu_latitude_value,
			'#min_value' => -90,
			'#max_value' => 90,
			'#step' => 0.00001,
			'#required' => TRUE
		];

		$form['longitude2'] = [
			'#type' => 'number',
			'#title' => $this->t('Longitude'),
			'#description' => '',
			//'#default_value' => $field_shmu_longitude_value,
			'#min_value' => -180,
			'#max_value' => 180,
			'#step' => 0.00001, // Based off of precision given in FarmOS map.
			'#required' => TRUE
		];

		$form['latitude3'] = [
			'#type' => 'number',
			'#title' => $this->t('Latitude'),
			'#description' => '',
			//'#default_value' => $field_shmu_latitude_value,
			'#min_value' => -90,
			'#max_value' => 90,
			'#step' => 0.00001,
			'#required' => TRUE
		];

		$form['longitude3'] = [
			'#type' => 'number',
			'#title' => $this->t('Longitude'),
			'#description' => '',
			//'#default_value' => $field_shmu_longitude_value,
			'#min_value' => -180,
			'#max_value' => 180,
			'#step' => 0.00001,
			'#required' => TRUE
		];
	}

	public function dashboardRedirect(array &$form, FormStateInterface $form_state){
		$form_state->setRedirect('cig_pods.awardee_dashboard_form');
	}

	/**
	* Deletes the sample_collection that is currently being viewed.
	*/
	public function deleteSampleCollection(array &$form, FormStateInterface $form_state){

		$sample_collection_id = $form_state->get('sample_id');
		$sample_collection = \Drupal::entityTypeManager()->getStorage('asset')->load($sample_collection_id);

		$sample_collection->delete();

		$form_state->setRedirect('cig_pods.awardee_dashboard_form');
	}

   /**
   * {@inheritdoc}
   */
	public function buildForm(array $form, FormStateInterface $form_state, $id = NULL){
		 $form['#attached']['library'][] = 'cig_pods/soil_health_sample_form';
		$sample_collection = [];
		$is_edit = $id <> NULL;
        $form['#attached']['library'][] = 'cig_pods/css_form';

		if($is_edit){
			$form_state->set('operation','edit');
			$form_state->set('sample_id',$id);
			$sample_collection = \Drupal::entityTypeManager()->getStorage('asset')->load($id);
		} else {
			$form_state->set('operation','create');
		}

        $this->buildSampleInformationSection($form, $form_state, $is_edit, $sample_collection);
		$this->buildGPSPointsSection($form, $form_state);

		// Add submit button
		$button_save_label = $is_edit ? $this->t('Save Changes') : $this->t('Save');
		$form['actions']['save'] = array(
			'#type' => 'submit',
			'#value' => $button_save_label,
		);

		$form['actions']['cancel'] = [
			'#type' => 'submit',
			'#value' => $this->t('Cancel'),
			"#limit_validation_errors" => array(),
			'#submit' => ['::dashboardRedirect'],
		];

		if($is_edit){
			$form['actions']['delete'] = [
				'#type' => 'submit',
				'#value' => $this->t('Delete'),
				'#submit' => ['::deleteSampleCollection'],
				"#limit_validation_errors" => array(),
				'#prefix' => '<div class="remove-button-container">',
				'#suffix' => '</div>',
			];
		}

		$form['actions']['add_assessment'] = array(
			'#type' => 'submit',
			'#value' => $this->t('Next: Add Assessment'),
			'#submit' => ['::dashboardRedirect'],
		);

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
  public function submitForm(array &$form, FormStateInterface $form_state) {

	$mapping = $this->getFormEntityMapping();

	$is_create = $form_state->get('operation') === 'create';

	if($is_create){
		$sample_collection_submission = [];
		$sample_collection_submission['type'] = 'soil_health_sample';

		// Single value fields can be mapped in
		foreach($mapping as $form_elem_id => $entity_field_id){
			// If mapping not in form or value is empty string
			if($form[$form_elem_id] === NULL || $form[$form_elem_id] === ''){
				continue;
			}
			$sample_collection_submission[$entity_field_id] = $form[$form_elem_id]['#value'];
		}
		$sample_plant_stage_at_sampling = $form_state->getValue('plant_stage_at_sampling');
		$sample_collection_date = strtotime($form_state->getValue('sample_collection_date'));

		$sample_collection_submission['field_plant_stage_at_sampling'] = $sample_plant_stage_at_sampling;
		$sample_collection_submission['field_soil_sample_collection_dat'] = $sample_collection_date;

		$sample_collection = Asset::create($sample_collection_submission);
		$sample_collection->save();

		$form_state->setRedirect('cig_pods.awardee_dashboard_form');
	} else {
		$id = $form_state->get('sample_id');
		$sample_collection_submission = \Drupal::entityTypeManager()->getStorage('asset')->load($id);

		$sample_shmu = $form_state->getValue('shmu');
		$sample_collection_date = strtotime($form_state->getValue('sample_collection_date'));
		$sample_equipment_used = $form_state->getValue('equipment_used');
		$sample_plant_stage_at_sampling = $form_state->getValue('plant_stage_at_sampling');
		$sample_depth = $form_state->getValue('sample_depth');
		$sample_name = $form_state->getValue('soil_sample_id');
    $diameter = $form_state->getValue('field_diameter');

		$sample_collection_submission->set('field_shmu_id', $sample_shmu);
		$sample_collection_submission->set('field_soil_sample_collection_dat', $sample_collection_date);
		$sample_collection_submission->set('field_equipment_used', $sample_equipment_used);
		$sample_collection_submission->set('field_plant_stage_at_sampling', $sample_plant_stage_at_sampling);
		$sample_collection_submission->set('field_sampling_depth', $sample_depth);
		$sample_collection_submission->set('name', $sample_name);
    $sample_collection_submission->set('field_diameter', $diameter);

		$sample_collection_submission->save();
		$form_state->setRedirect('cig_pods.awardee_dashboard_form');
	}

	return;
  }

  public function getFormEntityMapping(){
	$mapping = [];

	$mapping['shmu'] = 'field_shmu_id';
	$mapping['equipment_used'] = 'field_equipment_used';
	$mapping['sample_depth'] = 'field_sampling_depth';
	$mapping['soil_sample_id'] = 'name';
  $mapping['field_diameter'] = 'field_diameter';

	return $mapping;

}

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sample_collection_form';
  }
}
