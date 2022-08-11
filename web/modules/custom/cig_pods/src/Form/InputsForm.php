<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;

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



    public function buildForm(array $form, FormStateInterface $form_state, $id = NULL){

        $is_edit = $id <> NULL;

	    if($is_edit){
			$form_state->set('operation','edit');
			$form_state->set('awardee_id',$id);
			$awardee = \Drupal::entityTypeManager()->getStorage('asset')->load($id);
            $input = \Drupal::entityTypeManager()->getStorage('asset')->load($id);
	    } else {
			$form_state->set('operation','create');
	    }

        $form['#attached']['library'][] = 'cig_pods/inputs_form';

        $num_contact_lines = $form_state->get('num_contact_lines');//get num of contacts showing on screen. (1->n exclude:removed indexes)
		$num_contacts = $form_state->get('num_contacts');//get num of added contacts. (1->n)

        $removed_contacts = $form_state->get('removed_contacts');//get removed contacts indexes

        $awardee_org_default_name = $is_edit ? $awardee->get('field_cost') : '';

		if($is_edit){
			$cname=array();
			$fraction_count = count($awardee_org_default_name);
				for( $index = 0; $index < $fraction_count; $index++){
						$fractionToAdd = $this->convertFraction($awardee_org_default_name[$index]);
					$cname[] = $fractionToAdd;
				}

				$ex_count = count($cname);
		}

        if($is_edit){

			if ($num_contacts === NULL) {//initialize number of contact, set to 1
				$form_state->set('num_contacts', $ex_count);
				$num_contacts = $form_state->get('num_contacts');
			}
			if ($num_contact_lines === NULL) {
				$form_state->set('num_contact_lines', $ex_count);
				$num_contact_lines = $form_state->get('num_contact_lines');
			}
		}else{
				if ($num_contacts === NULL) {//initialize number of contact, set to 1
					$form_state->set('num_contacts', 1);
					$num_contacts = $form_state->get('num_contacts');
				}
				if ($num_contact_lines === NULL) {
					$form_state->set('num_contact_lines', 1);
					$num_contact_lines = $form_state->get('num_contact_lines');
				}
			}

		if ($removed_contacts === NULL) {
			$form_state->set('removed_contacts', array());//initialize arr
			$removed_contacts = $form_state->get('removed_contacts');
		}

        $form['title'] = [
			'#markup' => '<div class="title-container"><h1>Inputs</h1></div>'
		];

        $form['subtitle_1'] = [
			'#markup' => '<div class="subtitle-container"><h2>Input Information</h2><h4>5 Fields | Section 1 of 3</h4></div>'
		];

        //  $field_operation_value = $is_edit ? $input->get('field_operation')->target_id : '';

        $form['field_operation'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Operation (Determined from the operation you selected for this input)'),
            '#description' => '',
            // '#default_value' => $field_operation_value,
            '#required' => FALSE
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
			'#required' => FALSE
		];

        //input disabled untill we can get it working with input_category
        // $field_input_value = $is_edit ? $input->get('field_input')->target_id : '';

        $form['field_input'] = [
			'#type' => 'select',
			'#title' => $this->t('Input'),
			//'#options' => $this->getInputOptions(),
			//'#default_value' => $field_input_value,
			'#required' => FALSE,
            '#prefix' => '<div id="input-input"',
		];

        $field_unit_value = $is_edit ? $input->get('field_unit')->target_id : '';

        $form['field_unit'] = [
			'#type' => 'select',
			'#title' => $this->t('Unit'),
			'#options' => $this->getUnitOptions(),
			'#default_value' => $field_unit_value,
			'#required' => FALSE,
            '#suffix' => '</div>',
		];

         $field_rate_units_value = $is_edit ? $this->convertFraction($input->get('field_rate_units')[0]) : '';

        $form['field_rate_units'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Rate Units/Ac'),
            '#description' => '',
            '#default_value' => $field_rate_units_value,
            '#required' => FALSE
        ];

        $form['subtitle_2'] = [
			'#markup' => '<div class="subtitle-container"><h2>Custom Application</h2><h4>2 Fields | Section 2 of 3</h4></div>'
		];

        $field_cost_per_unit_value = $is_edit ? $this->convertFraction($input->get('field_cost_per_unit')[0]) : '';

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
			'#markup' => '<div class="subtitle-container"><h2>Other Costs</h2><h4>Section 3 of 3</h4></div>'
		];

		// $contact_name_options = $this->getAwardeeContactNameOptions();
		$form['#tree'] = TRUE;
		$form['names_fieldset'] = [
		  '#prefix' => '<div id="names-fieldset-wrapper"',
		  '#suffix' => '</div>',
		];

		$contact_default_name = $is_edit ? $input->get('field_cost_type')->getValue() : '';
		$contacttype=array();
			foreach ($contact_default_name as $checks) {
			 $detail = $checks['target_id'];
			 $contacttype[] = $detail;
			}

		for ($i = 0; $i < $num_contacts; $i++) {//num_contacts: get num of added contacts. (1->n)

			if (in_array($i, $removed_contacts)) {// Check if field was removed
				continue;
			}
            $form['names_fieldset'][$i]['new_line_container1'] = [
				'#prefix' => '<div id="other-costs"',
			];
			$form['names_fieldset'][$i]['contact_name'] = [
				 '#type' => 'number',
                 '#step' => 0.01,
				'#title' => $this
				  ->t("Cost"),
				//'#options' => $contact_name_options,
				'#default_value' => $cname[$i],
				'attributes' => [
					'class' => 'something',
				],
				'#prefix' => ($num_contact_lines > 1) ? '<div class="inline-components-short">' : '<div class="inline-components">',
		  		'#suffix' => '</div>',

			];

			$form['names_fieldset'][$i]['contact_type'] = [
				'#type' => 'select',
				'#title' => $this
				  ->t('Type'),
				'#options' =>  $this->getCostTypeOptions(),
			//'#default_value' => $field_shmu_curren,
				'#default_value' => $contacttype[$i],
				 '#prefix' => '<div class="inline-components"',
		  		'#suffix' => '</div>',
			];
            $form['names_fieldset'][$i]['new_line_container2'] = [
				'#suffix' => '</div>',
			];

			if($num_contact_lines > 1 && $i!=0){
				$form['names_fieldset'][$i]['actions'] = [
					'#type' => 'submit',
					'#value' => $this->t('Delete'),
					'#name' => $i,
					'#submit' => ['::removeContactCallback'],
					'#ajax' => [
					  'callback' => '::addContactRowCallback',
					  'wrapper' => 'names-fieldset-wrapper',
					],
					"#limit_validation_errors" => array(),
					'#prefix' => '<div class="remove-button-container">',
					'#suffix' => '</div>',
				];
			}

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
			'#value' => t('Add Another Contact'),
			'#submit' => ['::addContactRow'],
			'#ajax' => [
				'callback' => '::addContactRowCallback',
				'wrapper' => 'names-fieldset-wrapper',
			],
			'#states' => [
				'visible' => [
				  ":input[name='names_fieldset[0][contact_name]']" => ['!value' => ''],
				  "and",
				  ":input[name='names_fieldset[0][contact_type]']" => ['!value' => ''],
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
			'#type' => 'button',
			'#value' => $this->t('Cancel'),
			'#attributes' => array('onClick' => 'window.location.href="/pods_admin_dashboard"'),
		];

		if($is_edit){
				$form['actions']['delete'] = [
					'#type' => 'submit',
					'#value' => $this->t('Delete'),
					'#submit' => ['::deleteProject'],
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
        return;
    }

    /**
    * {@inheritdoc}
    */
    public function getFormId() {
        return 'inputform';
    }

    public function addContactRowCallback(array &$form, FormStateInterface $form_state) {
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

                 //for tessting only
                // TODO: remove before PR
            $project_submission['name'] = 'test1';


	        // Single value fields can be mapped in
	        foreach($mapping as $form_elem_id => $entity_field_id){
		        // If mapping not in form or value is empty string
		        if($form[$form_elem_id] === NULL || $form[$form_elem_id] === ''){
			        continue;
		        }
		        $project_submission[$entity_field_id] = $form[$form_elem_id]['#value'];
	        }

            // Minus 1 because there is an entry with key 'actions'
	        $num_contacts = count($form['names_fieldset']) - 1;

            $contact_eauth_ids = [];
	        $contact_types = [];
	        for( $i = 0; $i < $num_contacts; $i++ ){
		        $contact_eauth_ids[$i] = $form['names_fieldset'][$i]['contact_name']['#value'];
		        $contact_types[$i] = $form['names_fieldset'][$i]['contact_type']['#value'];
	        }

 	        $project_submission['field_cost'] = $contact_eauth_ids;
 	        $project_submission['field_cost_type'] = $contact_types;

            $project_submission['field_input_date'] = strtotime( $form['field_input_date']['#value'] );

	        $project = Asset::create($project_submission);
	        $project -> save();
	        $form_state->setRedirect('cig_pods.admin_dashboard_form');
         } else {
		    $awardee_id = $form_state->get('awardee_id');
		    $awardee = \Drupal::entityTypeManager()->getStorage('asset')->load($awardee_id);

             $mapping = $this->getFormEntityMapping();
             // Single value fields can be mapped in
	        foreach($mapping as $form_elem_id => $entity_field_id){
		        // If mapping not in form or value is empty string
		        if($form[$form_elem_id] === NULL || $form[$form_elem_id] === ''){
			        continue;
		        }
		        $awardee->set($entity_field_id, $form[$form_elem_id]['#value']);
	        }

		     // Minus 1 because there is an entry with key 'actions'
		    $num_contacts = count($form['names_fieldset']) - 1;

		    $contact_eauth_ids = [];
	        $contact_types = [];
	        for( $i = 0; $i < $num_contacts; $i++ ){
		        $contact_eauth_ids[$i] = $form['names_fieldset'][$i]['contact_name']['#value'];
		        $contact_types[$i] = $form['names_fieldset'][$i]['contact_type']['#value'];
	        }

 	        $awardee->set('field_cost', $contact_eauth_ids);
 	        $awardee->set('field_cost_type', $contact_types);

             $awardee->set('field_input_date', strtotime( $form['field_input_date']['#value'] ));
		    $awardee->save();
		    $form_state->setRedirect('cig_pods.admin_dashboard_form');
	}
}

    public function addContactRow(array &$form, FormStateInterface $form_state) {
        $num_contacts = $form_state->get('num_contacts');
	    $num_contact_lines = $form_state->get('num_contact_lines');
        $form_state->set('num_contacts', $num_contacts + 1);
	    $form_state->set('num_contact_lines', $num_contact_lines + 1);
        $form_state->setRebuild();
  }

  public function removeContactCallback(array &$form, FormStateInterface $form_state) {
    $trigger = $form_state->getTriggeringElement();
	$num_line = $form_state->get('num_contact_lines');
    $indexToRemove = $trigger['#name'];

   // Remove the fieldset from $form (the easy way)
    unset($form['names_fieldset'][$indexToRemove]);

    // Keep track of removed fields so we can add new fields at the bottom
    // Without this they would be added where a value was removed
    $removed_contacts = $form_state->get('removed_contacts');
    $removed_contacts[] = $indexToRemove;

	$form_state->set('removed_contacts', $removed_contacts);
	$form_state->set('num_contact_lines', $num_line - 1);

    // Rebuild form_state
    $form_state->setRebuild();
 }
}