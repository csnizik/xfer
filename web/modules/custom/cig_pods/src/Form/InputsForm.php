<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;
Use Drupal\Core\URL;

class InputsForm extends FormBase {

    /**
    * {@inheritdoc}
    */

    public function getInputCategoryOptions(){
		$options = [];
		$options[""] = '- Select -';
		$taxonomy_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
			['vid' => 'd_input_category']);
		$keys = array_keys($taxonomy_terms);
		foreach($keys as $key){
			$term = $taxonomy_terms[$key];
			$options[$key] = $term -> getName();
		}
		return $options;
	}

    public function getInputOptions(){
		$options = [];
		$options[""] = '- Select -';
		$taxonomy_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
			['vid' => 'd_input']);
		$keys = array_keys($taxonomy_terms);
		foreach($keys as $key){
			$term = $taxonomy_terms[$key];
			$options[$key] = $term -> getName();
		}
		return $options;
	}

    public function getCostTypeOptions(){
		$options = [];
		$options[""] = '- Select -';
		$taxonomy_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
			['vid' => 'd_cost_type']);
		$keys = array_keys($taxonomy_terms);
		foreach($keys as $key){
			$term = $taxonomy_terms[$key];
			$options[$key] = $term -> getName();
		}
		return $options;
	}

    public function getUnitOptions(){
		$options = [];
		$options[""] = '- Select -';
		$taxonomy_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
			['vid' => 'd_unit']);
		$keys = array_keys($taxonomy_terms);
		foreach($keys as $key){
			$term = $taxonomy_terms[$key];
			$options[$key] = $term -> getName();
		}
		return $options;
	}

    private function convertFraction($fraction){
        $num = $fraction->getValue()["numerator"];
        $denom = $fraction->getValue()["denominator"];
        return $num / $denom;
    }



    public function buildForm(array $form, FormStateInterface $form_state, $operation_id = NULL, $id = NULL){

        $is_edit = $id <> NULL;
        $operation_name = "Test Operation";
		$operation = [];

	    if($is_edit){
			$form_state->set('operation','edit');
			$form_state->set('input_id',$id);
			$input = \Drupal::entityTypeManager()->getStorage('asset')->load($id);
			$form_state->set('operation_id', $input->get('field_operation')->target_id);
	    } else {
			$form_state->set('operation','create');
			$form_state->set('operation_id', $operation_id);
	    }

        $form['#attached']['library'][] = 'cig_pods/inputs_form';
		$current_operation = \Drupal::entityTypeManager()->getStorage('asset')->load($form_state->get('operation_id'));

        $num_input_lines = $form_state->get('num_input_lines');//get num of inputss showing on screen. (1->n exclude:removed indexes)
		$num_inputs = $form_state->get('num_inputs');//get num of added inputs. (1->n)

        $removed_inputs = $form_state->get('removed_inputs');//get removed inputs indexes

        $input_org_default_name = $is_edit ? $input->get('field_cost') : '';

		if($is_edit){
			$cname=array();
			$fraction_count = count($input_org_default_name);
				for( $index = 0; $index < $fraction_count; $index++){
						$fractionToAdd = $this->convertFraction($input_org_default_name[$index]);
					$cname[] = $fractionToAdd;
				}

				if(count($cname) == 0){
					$ex_count = 1;
				}else{
					$ex_count = count($cname);
				}
				
		}

        if($is_edit){

			if ($num_inputs === NULL) {//initialize number of input, set to 1
				$form_state->set('num_inputs', $ex_count);
				$num_inputs = $form_state->get('num_inputs');
			}
			if ($num_input_lines === NULL) {
				$form_state->set('num_input_lines', $ex_count);
				$num_input_lines = $form_state->get('num_input_lines');
			}
		}else{
				if ($num_inputs === NULL) {//initialize number of input, set to 1
					$form_state->set('num_inputs', 1);
					$num_inputs = $form_state->get('num_inputs');
				}
				if ($num_input_lines === NULL) {
					$form_state->set('num_input_lines', 1);
					$num_input_lines = $form_state->get('num_input_lines');
				}
			}

		if ($removed_inputs === NULL) {
			$form_state->set('removed_inputs', array());//initialize arr
			$removed_inputs = $form_state->get('removed_inputs');
		}

        $form['title'] = [
			'#markup' => '<div class="title-container"><h1>Inputs</h1></div>'
		];

        $form['subtitle_1'] = [
			'#markup' => '<div class="subtitle-container"><h2>Input Information</h2><h4>5 Fields | Section 1 of 3</h4></div>'
		];

        $form['operation_description'] = [
			'#markup' => '<span class="operation-description"><h4>Operation (Determined from the operation you selected for this input)</h4></span>'
		];

        // $field_operation_value = $is_edit ? $input->get('field_operation')->target_id : '';
		$operation_taxonomy_name = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($current_operation->get('field_operation')->target_id);
		$form_state->set('current_operation_name', $operation_taxonomy_name-> getName());
        $form['field_operation'] = [
            '#markup' => $this->t('<span class="operation"><h4>@operation_name</h4></span>', ['@operation_name' => $operation_taxonomy_name-> getName()])
		];

        // For the Date input fields, we have to convert from UNIX to yyyy-mm-dd
		if($is_edit){
			// $field_input_date_value is expected to be a UNIX timestamp
			$field_input_date_value = $input->get('field_input_date')->value;
			$default_value_input_date = date("Y-m-d", $field_input_date_value);
		} else {
			$default_value_input_date = '';
		}

        $form['field_input_date'] = [
			'#type' => 'date',
			'#title' => $this->t('Date'),
			'#description' => '',
			 '#default_value' => $default_value_input_date, // Default value for "date" field type is a string in form of 'yyyy-MM-dd'
			'#required' => TRUE
		];

        $field_input_category_value = $is_edit ? $input->get('field_input_category')->target_id : '';

        $form['field_input_category'] = [
			'#type' => 'select',
			'#title' => $this->t('Input Category'),
			'#options' => $this->getInputCategoryOptions(),
			'#default_value' => $field_input_category_value,
			'#required' => TRUE
		];

        //input disabled untill we can get it working with input_category
        // $field_input_value = $is_edit ? $input->get('field_input')->target_id : '';

        $form['field_input'] = [
			'#type' => 'select',
			'#title' => $this->t('Input'),
			//'#options' => $this->getInputOptions(),
			//'#default_value' => $field_input_value,
			'#required' => FALSE,
            '#prefix' => '<span id="input-input">',
		];

        $field_unit_value = $is_edit ? $input->get('field_unit')->target_id : '';

        $form['field_unit'] = [
			'#type' => 'select',
			'#title' => $this->t('Unit'),
			'#options' => $this->getUnitOptions(),
			'#default_value' => $field_unit_value,
			'#required' => FALSE,
            '#suffix' => '</span>',
		];

         $field_rate_units_value = $is_edit && $input->get('field_rate_units')[0] ? $this->convertFraction($input->get('field_rate_units')[0]) : '';

        $form['field_rate_units'] = [
            '#type' => 'number',
			'#step' => 1,
            '#title' => $this->t('Rate Units/Ac'),
            '#description' => '',
            '#default_value' => $field_rate_units_value,
            '#required' => FALSE
        ];

        $form['subtitle_2'] = [
			'#markup' => '<div class="subtitle-container-custom-app"><h2>Custom Application</h2><h4>2 Fields | Section 2 of 3</h4></div>'
		];

        $field_cost_per_unit_value = $is_edit && $input->get('field_cost_per_unit')[0] ? $this->convertFraction($input->get('field_cost_per_unit')[0]) : '';

         $form['field_cost_per_unit'] = [
             '#type' => 'number',
            '#step' => 0.01,
            '#title' => $this->t('Cost Per Unit'),
            '#description' => '',
            '#default_value' => $field_cost_per_unit_value,
            '#required' => FALSE,
             '#prefix' => '<div id="cost-per-unit"',
        ];

        $field_custom_application_unit_value = $is_edit ? $input->get('field_custom_application_unit')->target_id : '';

        $form['field_custom_application_unit'] = [
			'#type' => 'select',
			'#title' => $this->t('Unit'),
			'#options' => $this->getUnitOptions(),
			'#default_value' => $field_custom_application_unit_value,
			'#required' => FALSE,
            '#suffix' => '</div>',
		];

        $form['subtitle_3'] = [
			'#markup' => '<div class="subtitle-container-costs"><h2>Other Costs</h2><h4>Section 3 of 3</h4></div>'
		];

		// $input_name_options = $this->getAwardeeContactNameOptions();
		$form['#tree'] = TRUE;
		$form['names_fieldset'] = [
		  '#prefix' => '<div id="names-fieldset-wrapper"',
		  '#suffix' => '</div>',
		];

		$contact_default_name = $is_edit ? $input->get('field_cost_type')->getValue() : '';
		$inputtype=array();
			foreach ($contact_default_name as $checks) {
			 $detail = $checks['target_id'];
			 $inputtype[] = $detail;
			}

		for ($i = 0; $i < $num_inputs; $i++) {//num_inputs: get num of added contacts. (1->n)

			if (in_array($i, $removed_inputs)) {// Check if field was removed
				continue;
			}
			
            $form['names_fieldset'][$i]['new_line_container1'] = [
				'#prefix' => '<div id="other-costs"',
			];
			$form['names_fieldset'][$i]['input_name'] = [
				 '#type' => 'number',
                 '#step' => 0.01,
				'#title' => $this
				  ->t("Cost"),
				//'#options' => $input_name_options,
				'#default_value' => $cname[$i],
				'attributes' => [
					'class' => 'something',
				],
				'#prefix' => ($num_input_lines > 1) ? '<div class="inline-components-short">' : '<div class="inline-components">',
		  		'#suffix' => '</div>',

			];

			$form['names_fieldset'][$i]['input_type'] = [
				'#type' => 'select',
				'#title' => $this
				  ->t('Type'),
				'#options' =>  $this->getCostTypeOptions(),
				'#default_value' => $inputtype[$i],
				 '#prefix' => '<div class="inline-components"',
		  		'#suffix' => '</div>',
			];
           

			if($num_input_lines > 1 && $i!=0){
				$form['names_fieldset'][$i]['actions'] = [
					'#type' => 'submit',
					'#value' => $this->t('Delete'),
					'#name' => $i,
					'#submit' => ['::removeInputCallback'],
					'#ajax' => [
					  'callback' => '::addInputRowCallback',
					  'wrapper' => 'names-fieldset-wrapper',
					],
					"#limit_validation_errors" => array(),
					'#prefix' => '<span class="remove-button-container">',
					'#suffix' => '</span>',
				];
			}
             $form['names_fieldset'][$i]['new_line_container2'] = [
				'#suffix' => '</div>',
			];

			//css space for a new line due to previous items' float left attr
			$form['names_fieldset'][$i]['new_line_container'] = [
				'#markup' => '<div class="clear-space"></div>'
			];

		}

        $form['actions'] = [
			'#type' => 'actions',
		];

		$form['names_fieldset']['actions']['add_name'] = [
			'#type' => 'submit',
			'#button_type' => 'button',
			'#name' => 'add_contact_button',
			'#value' => t('Add Another Cost'),
			'#submit' => ['::addInputRow'],
			'#ajax' => [
				'callback' => '::addInputRowCallback',
				'wrapper' => 'names-fieldset-wrapper',
			],
			'#states' => [
				'visible' => [
				  ":input[name='names_fieldset[0][input_name]']" => ['!value' => ''],
				  "and",
				  ":input[name='names_fieldset[0][input_type]']" => ['!value' => ''],
				],
			],
			"#limit_validation_errors" => array(),
			'#prefix' => '<div id="addmore-button-container">',
			'#suffix' => '</div>',
		];

        $form_state->setCached(FALSE);

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

        $form['actions']['save-refresh'] = [
			'#type' => 'submit',
			'#value' => $this->t('Add Another Input'),
            '#submit' => ['::addAnotherInput'],
		];


		if($is_edit){
				$form['actions']['delete'] = [
					'#type' => 'submit',
					'#value' => $this->t('Delete'),
					'#submit' => ['::deleteInputs'],
				];
			}
        return $form;
    }

     public function arrayValuesAreUnique($array){
	    $count_dict = array_count_values($array);

	    foreach($count_dict as $key => $value){
		    if($value != 1){
			    return False;
		    }
	    }
	    return True;
    }

    /**
    * {@inheritdoc}
    */
    public function validateForm(array &$form, FormStateInterface $form_state){
		$num_cost_entries = count($form['names_fieldset']) - 1;
		if($num_cost_entries > 1){
			for($i = 1; $i < $num_cost_entries; $i++){
				if($form_state->getValue(['names_fieldset', $i, 'input_name']) === ''){
					$form_state->setError($form['names_fieldset'][$i]['input_name'], $this->t("Please Fill out a Cost for the highlighted field"));
					return FALSE;
				}
				if($form_state->getValue(['names_fieldset', $i, 'input_type']) === ''){
					$form_state->setError($form['names_fieldset'][$i]['input_type'], $this->t('Please Fill out a Cost Type for the highlighted field'));
					return FALSE;
				}
			}
		}
        return;
    }

    /**
    * {@inheritdoc}
    */
    public function getFormId() {
        return 'inputform';
    }

    public function addInputRowCallback(array &$form, FormStateInterface $form_state) {
        return $form['names_fieldset'];
    }

     public function getFormEntityMapping(){
        $mapping = [];
		$mapping['field_input_date'] = 'field_input_date';
	    $mapping['field_input_category'] = 'field_input_category';
        $mapping['field_input'] = 'field_input';
	    $mapping['field_unit'] = 'field_unit';
	    $mapping['field_rate_units'] = 'field_rate_units';
	    $mapping['field_cost_per_unit'] = 'field_cost_per_unit';
        $mapping['field_custom_application_unit'] = 'field_custom_application_unit';

         return $mapping;
     }

    /**
    * {@inheritdoc}
    */
    public function submitForm(array &$form, FormStateInterface $form_state) {
       $is_create = $form_state->get('operation') === 'create';
        if($is_create){
	        $values = $form_state->getValues();

            $mapping = $this->getFormEntityMapping();

            $project_submission = [];

            $project_submission['type'] = 'input';
                 
			$operation_taxonomy_name = $form_state->get('current_operation_name');
			$input_taxonomy_name = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($form['field_input_category']['#value']);
            $project_submission['name'] = $operation_taxonomy_name."_".$input_taxonomy_name-> getName()."_".$form['field_input_date']['#value'];


	        // Single value fields can be mapped in
	        foreach($mapping as $form_elem_id => $entity_field_id){
		        // If mapping not in form or value is empty string
		        if($form[$form_elem_id] === NULL || $form[$form_elem_id] === ''){
			        continue;
		        }
		        $project_submission[$entity_field_id] = $form[$form_elem_id]['#value'];
	        }

            // Minus 1 because there is an entry with key 'actions'
	        $num_inputs = count($form['names_fieldset']) - 1;

            $contact_eauth_ids = [];
	        $input_types = [];
	        for( $i = 0; $i < $num_inputs; $i++ ){
		        $contact_eauth_ids[$i] = $form['names_fieldset'][$i]['input_name']['#value'];
		        $input_types[$i] = $form['names_fieldset'][$i]['input_type']['#value'];
	        }

 	        $project_submission['field_cost'] = $contact_eauth_ids;
 	        $project_submission['field_cost_type'] = $input_types;

            $project_submission['field_input_date'] = strtotime( $form['field_input_date']['#value'] );

	        $project = Asset::create($project_submission);
			$project->set('field_operation', \Drupal::entityTypeManager()->getStorage('asset')->load($form_state->get('operation_id')));
	         $project -> save();
         } else {
		    $input_id = $form_state->get('input_id');
		    $input = \Drupal::entityTypeManager()->getStorage('asset')->load($input_id);

             $mapping = $this->getFormEntityMapping();
             // Single value fields can be mapped in
	        foreach($mapping as $form_elem_id => $entity_field_id){
		        // If mapping not in form or value is empty string
		        if($form[$form_elem_id] === NULL || $form[$form_elem_id] === ''){
			        continue;
		        }
		        $input->set($entity_field_id, $form[$form_elem_id]['#value']);
	        }

		     // Minus 1 because there is an entry with key 'actions'
		    $num_inputs = count($form['names_fieldset']) - 1;

		    $input_cost = [];
	        $input_cost_types = [];
	        for( $i = 0; $i < $num_inputs; $i++ ){
				$input_cost[$i] = $form['names_fieldset'][$i]['input_name']['#value'];
				$input_cost_types[$i] = $form['names_fieldset'][$i]['input_type']['#value'];
	        }

 	        $input->set('field_cost', $input_cost);
 	        $input->set('field_cost_type', $input_cost_types);

            $input->set('field_input_date', strtotime( $form['field_input_date']['#value'] ));
			$input->set('field_operation', \Drupal::entityTypeManager()->getStorage('asset')->load($form_state->get('operation_id')));
		     $input->save();
			
	}
	if($form_state->get('redirect_input') == TRUE){
		$form_state->setRedirect('cig_pods.inputs_form', ['operation_id' => $form_state->get('operation_id')]);
	}else{
		$form_state->setRedirect('cig_pods.awardee_dashboard_form');
	}
}

    public function addInputRow(array &$form, FormStateInterface $form_state) {
        $num_inputs = $form_state->get('num_inputs');
	    $num_input_lines = $form_state->get('num_input_lines');
        $form_state->set('num_inputs', $num_inputs + 1);
	    $form_state->set('num_input_lines', $num_input_lines + 1);
        $form_state->setRebuild();
  }

  public function removeInputCallback(array &$form, FormStateInterface $form_state) {
    $trigger = $form_state->getTriggeringElement();
	$num_line = $form_state->get('num_input_lines');
    $indexToRemove = $trigger['#name'];

   // Remove the fieldset from $form (the easy way)
    unset($form['names_fieldset'][$indexToRemove]);

    // Keep track of removed fields so we can add new fields at the bottom
    // Without this they would be added where a value was removed
    $removed_inputs = $form_state->get('removed_inputs');
    $removed_inputs[] = $indexToRemove;

	$form_state->set('removed_inputs', $removed_inputs);
	$form_state->set('num_input_lines', $num_line - 1);

    // Rebuild form_state
    $form_state->setRebuild();
 }

  public function addAnotherInput(array &$form, FormStateInterface $form_state) {
	$form_state->set('redirect_input', TRUE);
	 $this->submitForm($form, $form_state);
  }
  public function cancelSubmit(array &$form, FormStateInterface $form_state) {
		$form_state->setRedirect('cig_pods.awardee_dashboard_form');
		return;
	}

    public function deleteInputs(array &$form, FormStateInterface $form_state){
        $input_id = $form_state->get('input_id');
		$input_to_delete = \Drupal::entityTypeManager()->getStorage('asset')->load($input_id);

		try{
			$input_to_delete->delete();
			$form_state->setRedirect('cig_pods.awardee_dashboard_form');
		}catch(\Exception $e){
			$this
		  ->messenger()
		  ->addError($this
		  ->t($e->getMessage()));
		}
    }
}