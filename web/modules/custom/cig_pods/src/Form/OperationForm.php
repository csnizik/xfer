<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;
Use Drupal\Core\Render\Element\Checkboxes;
Use Drupal\Core\Url;


class OperationForm extends FormBase {

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

	public function getEquipmentOptions(){
		$options = [];
		$options[''] = '- Select -';
		$taxonomy_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
			['vid' => 'd_tractor_self_propelled_machine']);
		$keys = array_keys($taxonomy_terms);
		foreach($keys as $key){
			$term = $taxonomy_terms[$key];
			$options[$key] = $term -> getName();
		}
		return $options;
	}

	public function getEquipmentOwnershipOptions(){
		$options = [];
		$options[''] = '- Select -';
		$taxonomy_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
			['vid' => 'd_equipment_ownership']);
		$keys = array_keys($taxonomy_terms);
		foreach($keys as $key){
			$term = $taxonomy_terms[$key];
			$options[$key] = $term -> getName();
		}
		return $options;
	}

	public function getOperationOptions(){
		$options = [];
		$options[''] = '- Select -';
		$taxonomy_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
			['vid' => 'd_operation_type']);
		$keys = array_keys($taxonomy_terms);
		foreach($keys as $key){
			$term = $taxonomy_terms[$key];
			$options[$key] = $term -> getName();
		}
		return $options;
	}

	public function getCostSequenceIdsForOperation($operation){
		$cost_sequence_target_ids = [];

		$field_shmu_cost_sequence_list = $operation->get('field_cost_sequences'); // Expected type of FieldItemList

		foreach($field_shmu_cost_sequence_list as $key=>$value){
			$cost_sequence_target_ids[] = $value->target_id; // $value is of type EntityReferenceItem (has access to value through target_id)
		}
		return $cost_sequence_target_ids;
	}

	public function getAsset($id){
		// We use load instead of load by properties here because we are looking by id
		$asset = \Drupal::entityTypeManager()->getStorage('asset')->load($id);
		return $asset;

	}


	public function loadOtherCostsIntoFormState($cost_sequence_ids, $form_state){

		$ignored_fields = ['uuid','revision_id','langcode','type','revision_user','revision_log_message','uid','name', 'status', 'created', 'changed', 'archived', 'default_langcode', 'revision_default'];
		$sequences = [];
		$i = 0;
		foreach($cost_sequence_ids as $key=>$cost_sequence_id){
			$tmp_sequence = $this->getAsset($cost_sequence_id)->toArray();
			$sequences[$i] = array();
			$sequences[$i]['field_operation_cost_type'] = $tmp_sequence['field_operation_cost_type'];
			$sequences[$i]['field_operation_cost'] = $tmp_sequence['field_operation_cost'];
			$i++;
		}
		// If sequences is still empty, set a blank sequence at index 0
		if($i == 0){
			$sequences[0]['field_operation_cost_type'] = '';
			$sequences[0]['field_operation_cost'] = '';
		}

		$form_state->set('sequences', $sequences);
		return;
	}

	public function getOtherCostsOptions(){
		$options = [];
		$options[''] = '- Select -';
		$taxonomy_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
			['vid' => 'd_cost_type']);
		$keys = array_keys($taxonomy_terms);
		foreach($keys as $key){
			$term = $taxonomy_terms[$key];
			$options[$key] = $term -> getName();
		}
		return $options;
	}

	public function getDecimalFromSHMUFractionFieldType(object $shmu, string $field_name){
		return $shmu->get($field_name)-> numerator / $shmu->get($field_name)->denominator;
	}

	/**
	* {@inheritdoc}
	*/
	public function buildForm(array $form, FormStateInterface $form_state, $id = NULL){
        $is_edit = $id <> NULL;
		$default_options[''] = '- Select -';


        if ($form_state->get('load_done') == NULL){
			$form_state->set('load_done', FALSE);
		}
        $form['#attached']['library'][] = 'cig_pods/operation_form';
		$form['#attached']['library'][] = 'cig_pods/css_form';
		$form['#tree'] = TRUE;
		// Determine if it is an edit process. If it is, load irrigation into local variable.
		if($is_edit){
			$form_state->set('operation','edit');
			$form_state->set('operation_id', $id);
			$operation = \Drupal::entityTypeManager()->getStorage('asset')->load($id);
			$operation_cost_sequences_ids = $this->getCostSequenceIdsForOperation($operation);
			if(!$form_state->get('load_done')){
				$this->loadOtherCostsIntoFormState($operation_cost_sequences_ids, $form_state);
                $form_state->set('load_done',TRUE);
			}
			$form_state->set('original_cost_sequence_ids', $operation_cost_sequences_ids);
		} else {
			if(!$form_state->get('load_done')){
				$this->loadOtherCostsIntoFormState([], $form_state);
				$form_state->set('load_done',TRUE);
			}
			$form_state->set('operation','create');
		}
		$form['subform_1'] = [
			'#markup' => '<div class="subform-title-container"><h1>Operation</h1></div>'
		];


		$shmu_options = $this->getSHMUOptions();
		$shmu_default_value = $is_edit ?  $operation->get('field_operation_shmu')->target_id : $default_options;
		$form['field_operation_shmu'] = [
		  '#type' => 'select',
		  '#title' => t('Select a Soil Health Management Unit (SHMU)'),
		  '#options' => $shmu_options,
		  '#default_value' => $shmu_default_value,
		  '#required' => TRUE,
		];

		if($is_edit){
			// $ field_shmu_irrigation_sample_date_timestamp is expected to be a UNIX timestamp

			$field_operation_timestamp = $operation->get('field_operation_date')->value;
			$field_operation_timestamp_default_value = date("Y-m-d", $field_operation_timestamp);
		} else {
			$field_operation_timestamp_default_value = ''; // TODO: Check behavior
		}

		// $field_shmu_irrigation_sample_date_value = $is_edit ? $shmu->get('field_shmu_irrigation_sample_date')->value : '';
		$form['field_operation_date'] = [
			'#type' => 'date',
			'#title' => $this->t('Operation Date'),
			'#description' => '',
			'#default_value' => $field_operation_timestamp_default_value,
			'#required' => TRUE
		];

		$form['subform_2'] = [
			'#markup' => '<div class="subform-title-container"><h2>Operation Information</h2><h4>2 Fields | Section 2 of 3</h4></div>'
		];

		$field_operation_type = $is_edit ? $operation->get('field_operation')->target_id :'';
		$field_operation_options = $this->getOperationOptions();
		$form['field_operation'] = [
			'#type' => 'select',
			'#title' => $this->t('Operation'),
			'#default_value' => $field_operation_type,
			'#options' => $field_operation_options,
			'#required' => TRUE
		];

		$field_ownership_implement = $is_edit ? $operation->get('field_ownership_status')->target_id: '';
		$field_ownership_options = $this->getEquipmentOwnershipOptions();
		$form['field_ownership_status'] = [
			'#type' => 'select',
			'#title' => $this->t('Equipment/Implement Ownership Status'),
			'#options' => $field_ownership_options,
			'#default_value' => $field_ownership_implement,
			'#required' => TRUE
		];

		$form['subform_3'] = [
			'#markup' => '<div class="subform-title-container"><h2>Tractor/Self-Propelled Machine Information</h2><h4>4 Fields | Section 2 of 3</h4></div>'
		];

		$field_tractor_self = $is_edit ? $operation->get('field_tractor_self_propelled_machine')->target_id : '';
		$field_equipment_options = $this->getEquipmentOptions();
		$form['field_tractor_self_propelled_machine'] = [
			'#type' => 'select',
			'#title' => $this->t('Tractor/Self-Propelled Machine'),
			'#options' => $field_equipment_options,
			'#default_value' => $field_tractor_self,
			'#required' => TRUE
		];


		$field_number_of_rows = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($operation, 'field_row_number'): '';

		$form['field_row_number'] = [
			'#type' => 'number',
			'#min_value' => 0,// Capped at 1 million because you can't have more than 1 million parts per million
			'#step' => 1, // Float
			'#title' => $this->t('Number of Rows'),
			'#default_value' => $field_number_of_rows,
			'#required' => FALSE
		];


		$field_width_of = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($operation, 'field_width'): '';

		$form['field_width'] = [
			'#type' => 'number',
			'#min_value' => 0,
			'#max_value' => 1000000, // Capped at 1 million because you can't have more than 1 million parts per million
			'#step' => 0.01, // Float
			'#title' => $this->t('Width'),
			'#default_value' => $field_width_of,
			'#required' => FALSE
		];

		$field_horsepower_of = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($operation, 'field_horsepower'): '';

		$form['field_horsepower'] = [
			'#type' => 'number',
			'#min_value' => 0,
			'#max_value' => 1000000, // Capped at 1 million because you can't have more than 1 million parts per million
			'#step' => 0.01, // Float
			'#title' => $this->t('Horsepower'),
			'#default_value' => $field_horsepower_of,
			'#required' => FALSE
		];

		$form['subform_4'] = [
			'#markup' => '<div class="subform-title-container"><h2>Other Costs</h2><h4>2 Fields | Section 3 of 3</h4></div>'
		];



		$form['cost_sequence'] = [
			'#prefix' => '<div id ="cost_sequence">',
			'#suffix' => '</div>',
		];
		// Get Options
		$cost_options = $this->getOtherCostsOptions();

		$fs_cost_sequences = $form_state -> get('sequences');

		$num_cost_sequences = 1;
		if(count($fs_cost_sequences) <> 0){
			$num_cost_sequences = count($fs_cost_sequences);
		}


		$form_index = 0; // Not to be confused with rotation
		foreach($fs_cost_sequences as $fs_index => $sequence  ){

			$cost_type_default_value = ''; // Default value for empty Rotation
			$cost_default_value = ''; // Default value for empty rotation

			$cost_default_value = $sequence['field_operation_cost'][0]['numerator'] / $sequence['field_operation_cost'][0]['denominator'];
			$cost_type_default_value = $sequence['field_operation_cost_type'][0]['target_id'];

			$form['cost_sequence'][$fs_index] = [
				'#prefix' => '<div id="cost_rotation">',
				'#suffix' => '</div>',
			];

			$form['cost_sequence'][$fs_index]['field_operation_cost'] = [
				'#type' => 'number',
				'#title' => 'Cost',
				'#step' => 0.01,
				'#default_value' => $cost_default_value,
			];
			$form['cost_sequence'][$fs_index]['field_operation_cost_type'] = [
				'#type' => 'select',
				'#title' => 'Type',
				'#options' => $cost_options,
				'#default_value' => $cost_type_default_value
			];

			$form['cost_sequence'][$fs_index]['actions']['delete'] = [
				'#type' => 'submit',
				'#name' => $fs_index,
				'#submit' => ['::deleteCostSequence'],
				'#ajax' => [
					'callback' => "::deleteCostSequenceCallback",
					'wrapper' => 'cost_sequence',
				],
				'#limit_validation_errors' => [],
				'#value' => $this->t('Delete'),
			];

			// Very important
			$form_index = $form_index + 1;
			// End very important
		}




		$form['addCost'] = [
			'#type' => 'submit',
			'#submit' => ['::addAnotherCostSequence'],
			'#ajax' => [
				'callback' => '::addAnotherCostSequenceCallback',
				'wrapper' => 'cost_sequence',
			],
			'#limit_validation_errors' => [],
			'#value' => 'Add Another Cost',
		];

		$form['actions'] = [
			'#type' => 'actions',
		  ];

        $form['actions']['save'] = [
			'#type' => 'submit',
			'#value' => $this->t('Save'),

		];

		$form['actions']['cancel'] = [
			'#type' => 'submit',
			'#value' => $this->t('Cancel'),
			'#submit' => [[$this, 'cancelSubmit']],
			'#limit_validation_errors' => array(),
		];
		$form['actions']['add_input'] = [
			'#type' => 'submit',
			'#value' => $this->t('Add Inputs'),
			'#submit' => ['::addInput'],
		];

		if($is_edit){
			$form['actions']['delete'] = [
				'#type' => 'submit',
				'#value' => $this->t('Delete'),
				'#submit' => [[$this, 'deleteSubmit']],
				'#limit_validation_errors' => array(),
			];
		}

        return $form;
    }

	public function cancelSubmit(array &$form, FormStateInterface $form_state) {
		$form_state->setRedirect('cig_pods.awardee_dashboard_form');
		return;
	}

	public function deleteSubmit(array &$form, FormStateInterface $form_state) {
		$id = $form_state->get('operation_id'); // TODO: Standardize access
		$operation = \Drupal::entityTypeManager()->getStorage('asset')->load($id);
		$sequence_ids = $this->getCostSequenceIdsForOperation($operation);
		try{
			$operation->delete();
			$form_state->setRedirect('cig_pods.awardee_dashboard_form');
		}catch(\Exception $e){
			$this
		  ->messenger()
		  ->addError($this
		  ->t($e->getMessage()));
		}
		foreach($sequence_ids as $sid) {
			try{
				$cost_sequence = \Drupal::entityTypeManager()->getStorage('asset')->load($sid);
				$cost_sequence->delete();
			}catch(\Exception $e){
				$this
			  ->messenger()
			  ->addError($this
			  ->t($e->getMessage()));
			}
		}
		return;
	}

	public function addInput(array &$form, FormStateInterface $form_state) {
		$form_state->set('input_redirect', TRUE);
		$this->submitForm($form, $form_state);
	}
    /**
	* {@inheritdoc}
	*/
	public function getFormId() {

		return 'operation_form';
	}

    /**
	* {@inheritdoc}
	*/
	public function submitForm(array &$form, FormStateInterface $form_state) {
		$cost_fields = ['sequences', 'cost_sequence','field_operation_cost', 'field_operation_cost_type'];
        $is_edit = $form_state->get('operation') == 'edit';
		$ignored_fields = ['send','form_build_id','form_token','form_id','op','actions', 'delete', 'cancel', 'add_input', 'addCost'];
		$date_fields = ['field_operation_date'];
		$form_values = $form_state->getValues();

        if(!$is_edit){
			$operation_template = [];
			$operation_template['type'] = 'operation';
			$operation = Asset::create($operation_template);
		} else {
			// Operation is of type Edit
			$id = $form_state->get('operation_id'); // TODO: Standardize access
			$operation = \Drupal::entityTypeManager()->getStorage('asset')->load($id);
		}

        foreach($form_values as $key => $value){
			// If it is an ignored field, skip the loop
			if(in_array($key, $ignored_fields)){ continue; }
			if(in_array($key,$date_fields)){
				// $value is expected to be a string of format yyyy-mm-dd
				$operation->set( $key, strtotime( $value ) ); //Set directly on SHMU object
				continue;
			}
			if(in_array($key, $cost_fields)){ continue; }

            $operation->set( $key, $value );
        }

		$num_cost_sequences = count($form_values['cost_sequence']); // TODO: Can be calculate dynamically based off of form submit

		$cost_options = $this->getOtherCostsOptions();

		$cost_template = [];
		$cost_template['type'] = 'operation_cost_sequence';

		for($sequence = 0; $sequence < $num_cost_sequences; $sequence++ ){

			// If they did not select a cost for the row, do not include it in the save
			if($form_values['cost_sequence'][$sequence]['field_operation_cost_type'] == NULL) continue;

			// We alwasys create a new cost sequence asset for each rotation
			$cost_sequence = Asset::create( $cost_template );

			// read the cost id from select dropdown for given rotation
			$cost_id = $form_values['cost_sequence'][$sequence]['field_operation_cost_type'];
			$cost_sequence->set( 'field_operation_cost_type', $cost_id );

			// read the cost rotation year from select dropdown for given rotation
			$cost_value = $form_values['cost_sequence'][$sequence]['field_operation_cost'];
			$cost_sequence->set( 'field_operation_cost', $cost_value );

			#

			$cost_sequence->save();
			$cost_sequence_ids[] = $cost_sequence -> id(); // Append ID of SHMU Cost Sequence to list

		}

		$operation->set('field_cost_sequences', $cost_sequence_ids);
		$operation->save();

		// Cleanup - remove the old cost Sequence Assets that are no longer used
		if($is_edit){
			$trash_rotation_ids = $form_state->get('original_cost_sequence_ids');
			foreach($trash_rotation_ids as $key => $id){
				$cost_sequence_old = Asset::load($id);
				$cost_sequence_old->delete();
			}
		}
		// Success message done
		if($form_state->get('input_redirect')){
			$form_state->setRedirect('cig_pods.inputs_form', ['operation_id' => $operation->get('id')->value]);
		}else{
			$form_state->setRedirect('cig_pods.awardee_dashboard_form');
		}
    }

	public function addAnotherCostSequence(array &$form, FormStateInterface $form_state){
		$sequences = $form_state->get('sequences');
		$new_cost_sequence = [];
		$new_cost_sequence['field_operation_cost'][0]['value'] = '';
		$new_cost_sequence['field_operation_cost_type'][0]['value'] = '';

		$cost_options = $this->getOtherCostsOptions();
		$sequences[] = $new_cost_sequence;
		$form_state->set('sequences', $sequences);

		$form_state->setRebuild(True);
	}

	public function addAnotherCostSequenceCallback(array &$form, FormStateInterface $form_state){
		return $form['cost_sequence'];
	}


	public function deleteCostSequence(array &$form, FormStateInterface $form_state){
	    $idx_to_rm = $form_state->getTriggeringElement()['#name'];

		$sequences = $form_state->get('sequences');

		unset($sequences[$idx_to_rm]); // Remove the index

		$form_state->set('sequences',$sequences);


		$form_state->setRebuild(True);
	}

	public function deleteCostSequenceCallback(array &$form, FormStateInterface $form_state){
		return $form['cost_sequence'];
	}
}