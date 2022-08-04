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

	public function loadOtherCostsIntoFormState($cost_sequence_ids, $form_state){

		$ignored_fields = ['uuid','revision_id','langcode','type','revision_user','revision_log_message','uid','name', 'status', 'created', 'changed', 'archived', 'default_langcode', 'revision_default'];

		$sequences = [];
		$i = 0;
		foreach($cost_seqence_ids as $key=>$cost_seqence_id){
			$tmp_rotation = $this->getAsset($cost_seqence_id)->toArray();
			$sequences[$i] = array();
			$sequences[$i]['field_shmu_crop_rotation_crop'] = $tmp_rotation['field_shmu_crop_rotation_crop'];
			$sequences[$i]['field_shmu_crop_rotation_year'] = $tmp_rotation['field_shmu_crop_rotation_year'];
			$sequences[$i]['field_shmu_crop_rotation_crop_present'] = $tmp_rotation['field_shmu_crop_rotation_crop_present'];
			$i++;
		}

		// If rotations is still empty, set a blank crop rotation at index 0
		if($i == 0){
			$sequences[0]['field_shmu_crop_rotation_year'] = '';
			$sequences[0]['field_shmu_crop_rotation_year'] = '';
		}
		$form_state->set('sequences', $sequences);
		// dpm($rotations);

		return;
	}

	public function getOtherCostsOptions(){
		$options = [];
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
		$form['#tree'] = TRUE;
		// Determine if it is an edit process. If it is, load irrigation into local variable.
		if($is_edit){
			$form_state->set('operation','edit');
			$form_state->set('operation_id', $id);
			$operation = \Drupal::entityTypeManager()->getStorage('asset')->load($id);
			if(!$form_state->get('load_done')){
                $form_state->set('load_done',TRUE);
			}
		} else {
			if(!$form_state->get('load_done')){
				$form_state->set('load_done',TRUE);
			}
			$form_state->set('operation','create');
		}
		$form['subform_1'] = [
			'#markup' => '<div class="subform-title-container"><h1>Operation</h1></div>'
		];

        
		$shmu_options = $this->getSHMUOptions();
		$shmu_default_value = $is_edit ?  $operation->get('field_shmu')->target_id : $default_options;
		$form['field_shmu'] = [
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
			'#markup' => '<div class="subform-title-container"><h2>Tractor/Self-Propelled Machine Information</h2><h4>2 Fields | Section 2 of 3</h4></div>'
		];

		$field_operation_type = $is_edit ? $operation->get('field_operation'):'';

		$form['field_operation'] = [
			'#type' => 'select',
			'#title' => $this->t('Operation'),
			'#default_value' => $field_operation_type,
			'#options' => $default_options,
			'#required' => TRUE
		];

		$field_ownership_implement = $is_edit ? $operation->get('field_ownership_status'): '';

		$form['field_ownership_status'] = [
			'#type' => 'select',
			'#title' => $this->t('Equipment/Implement Ownership Status'),
			'#options' => $default_options,
			'#default_value' => $field_ownership_implement,
			'#required' => TRUE
		];

		$form['subform_3'] = [
			'#markup' => '<div class="subform-title-container"><h2>Tractor/Self-Propelled Machine Information</h2><h4>4 Fields | Section 2 of 3</h4></div>'
		];

		$field_tractor_self = $is_edit ? $operation->get('field_tractor_self_propelled_machine'): '';

		$form['field_tractor_self_propelled_machine'] = [
			'#type' => 'select',
			'#title' => $this->t('Tractor/Self-Propelled Machine'),
			'#options' => $default_options,
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

		$form['field_shmu_irrigation_total_alkalinity'] = [
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
		$cost_options = $this->getOtherCostsOptions();
		$cost_options[''] = '-- Select --';


        $form['actions']['save'] = [
			'#type' => 'submit',
			'#value' => $this->t('Save'),

		];
		$form['cancel'] = [
			'#type' => 'submit',
			'#value' => $this->t('Cancel'),
			'#submit' => [[$this, 'cancelSubmit']],
			'#limit_validation_errors' => array(),
		];
		$form['add_input'] = [
			'#type' => 'submit',
			'#value' => $this->t('Add Inputs'),
			'#submit' => [[$this, 'addInput']],
			'#limit_validation_errors' => array(),
		];

		if($is_edit){
			$form['delete'] = [
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
		$irrigation = \Drupal::entityTypeManager()->getStorage('asset')->load($id);
		$irrigation->delete();
		$form_state->setRedirect('cig_pods.awardee_dashboard_form');
		return;
	}

	public function addInput(array &$form, FormStateInterface $form_state) {
		$form_state->setRedirect('cig_pods.input_form');
		return;
	}
    /**
	* {@inheritdoc}
	*/
	public function getFormId() {
		
		return 'irrigation_form';
	}

    /**
	* {@inheritdoc}
	*/
	public function submitForm(array &$form, FormStateInterface $form_state) {

        $is_edit = $form_state->get('operation') == 'edit';
		$ignored_fields = ['send','form_build_id','form_token','form_id','op','actions', 'delete', 'cancel'];
		$date_fields = ['field_shmu_irrigation_sample_date'];
		$form_values = $form_state->getValues();
		
        if(!$is_edit){
			$irrigation_template = [];
			$irrigation_template['type'] = 'irrigation';
			// dpm($irrigation_template);
			// dpm("------------");
			$irrigation = Asset::create($irrigation_template);
		} else {
			// Operation is of type Edit
			$id = $form_state->get('irrigation_id'); // TODO: Standardize access
			$irrigation = \Drupal::entityTypeManager()->getStorage('asset')->load($id);
		}

        foreach($form_values as $key => $value){
			// If it is an ignored field, skip the loop
			if(in_array($key, $ignored_fields)){ continue; }
			if(in_array($key,$date_fields)){
				// $value is expected to be a string of format yyyy-mm-dd
				$irrigation->set( $key, strtotime( $value ) ); //Set directly on SHMU object
				continue;
			}

            $irrigation->set( $key, $value );
        }

		$irrigation->save();
		// Success message done

		$form_state->setRedirect('cig_pods.awardee_dashboard_form');
    }
}