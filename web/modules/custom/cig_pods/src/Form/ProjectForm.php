<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;

class ProjectForm extends FormBase {

   
	/*
		Take in form and form_state using pass by reference on the form. No need to return the form
	*/
	public function buildProjectInformationSection(array &$form, FormStateInterface &$form_state, $options = NULL){
		$form['form_title'] = [
			'#markup' => '<h1 id="form-title">Project Information</h1>'
		];

		$form['subform_1'] = [
			'#markup' => '<div class="subform-title-container"><h2>General Project Information</h2><h4>7 Fields | Section 1 of 3</h4></div>'
		];

		$form['project_name'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Project Name'),
			'$description' => 'Project Name',
			'#required' => FALSE
		];
		
		$form['agreement_number'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Agreement Number'),
			'$description' => 'Agreement Number',
			'#required' => FALSE
		];

		$form['funding_amount'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Funding Amount'),
			'$description' => 'Funding Amount',
			'#required' => FALSE
		];

		$resource_concern_terms = \Drupal::entityTypeManager() -> getStorage('taxonomy_term') -> loadByProperties(
		   ['vid' => 'd_resource_concern']
		);
		



		$resource_concern_options = [];
		$resource_concern_keys = array_keys($resource_concern_terms);

		foreach($resource_concern_keys as $resource_concern_key) {
		  $term = $resource_concern_terms[$resource_concern_key];
		  $resource_concern_options[$resource_concern_key] = $term -> getName();
		}
		$form['resource_concern'] = [
		  '#type' => 'checkboxes',
		  '#title' => t('Possible Resource Concerns'),
		  '#options' => $resource_concern_options,
		  '#required' => FALSE,
		  '#required' => FALSE,
		];

		$form['project_summary'] = [
			'#type' => 'textarea',
			'#title' => $this->t('Project Summary'),
			'$description' => 'Project Summary',
			'#required' => FALSE
		];

		// Start: Taxonomy term loaded dynamically example

		$grant_type_terms = \Drupal::entityTypeManager() -> getStorage('taxonomy_term') -> loadByProperties(
			[
				'vid' => 'd_grant_type',
			]
		);

		$grant_type_options = [];
		$grant_type_keys = array_keys($grant_type_terms);

		foreach($grant_type_keys as $grant_type_key) {
			$term = $grant_type_terms[$grant_type_key];
			$grant_type_options[$grant_type_key] = $term -> getName();
		}
		
		$form['grant_type'] = [
			'#type' => 'select',
			'#title' => $this
			  ->t('Grant Type'),
			'#options' => $grant_type_options,
			'#required' => FALSE
		];
		// End: Taxonomy term loaded dynamically example


	}

	/*
		Take in form and form_state using pass by reference on the form. No need to return the form
	*/
	public function buildProducerInformationSection(array &$form, FormStateInterface &$form_state, $options = NULL){
		/* Producers Information */
		$form['subform_3'] = [
			'#markup' => '<div class="subform-title-container"><h2>Producers</h2><h4>Section 3 of 3</h4></div>'
		];

		$form['producer_contact_name'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Producer Contact Name'),
			'$description' => 'Producer Contact Name',
			'#required' => FALSE
		];
		/* Producers Information Ends*/
	}

   /**
   * {@inheritdoc}
   */
	public function buildForm(array $form, FormStateInterface $form_state, $options = NULL){

		$form['#attached']['library'][] = 'cig_pods/project_entry_form';


		// $this->buildProjectInformationSection($form, $form_state, $options);
		
		$form_state->set('num_indexes', $form_state->get('num_indexes') 	  == NULL ? 1 : $form_state->get('num_indexes') );
		$form_state->set('num_lines', $form_state->get('num_lines') 		  == NULL ? 1 : $form_state->get('removed_fields') );
		$form_state->set('removed_fields', $form_state->get('removed_fields') == NULL ? array() : $form_state->get('removed_fields') );

		$num_indexes = $form_state->get('num_indexes');
		$num_lines = $form_state->get('num_lines');
		$removed_fields = $form_state->get('removed_fields');

		// $removed_fields = $form_state->get('removed_fields');//get removed contacts indexes
		/* Variables declaration end*/

		/* Awardee Information */
		$form['subform_2'] = [
			'#markup' => '<div class="subform-title-container"><h2>Awardee Information</h2><h4>Section 2 of 3</h4></div>'
		];

		/* Selection of existing Awardees*/

		$awardee_assets = \Drupal::entityTypeManager() -> getStorage('asset') -> loadByProperties(
			['type' => 'awardee']
		);
		$awardee_options = array();
		$awardee_keys = array_keys($awardee_assets);
		print_r($awardee_keys);
		foreach($awardee_keys as $awardee_key) {
		  $asset = $awardee_assets[$awardee_key];
		  $awardee_options[$awardee_key] = $asset->getName();
		}
		$form['awardee'] = [
		  '#type' => 'select',
		  '#title' => t('Awardee Organization'),
		  '#options' => $awardee_options,
		  '#required' => FALSE
		];


		$form['create_organization'] = [
			'#type' => 'button',
			'#value' => $this->t('Create New Awardee Organization(s)'),
			'#attributes' => array('onClick' => 'alert(1)'),
		];

		$form['#tree'] = TRUE; // TODO: figure out what this accomplishes
		
		$form['names_fieldset'] = [
		  '#prefix' => '<div id="names-fieldset-wrapper"',
		  '#suffix' => '</div>',
		];

		// Start: Load the list of existing contact types 

		$contact_type_terms = \Drupal::entityTypeManager() -> getStorage('taxonomy_term') -> loadByProperties(
			['vid' => 'd_contact_type']
		);

		$contact_type_options = [];
		$contact_type_keys = array_keys($contact_type_terms);
		foreach($contact_type_keys as $contact_type_key) {
			$term = $contact_type_terms[$contact_type_key];
			$contact_type_options[$contact_type_key] = $term -> getName();
		}

		// End: Load the list of existing contact types

		$contact_type_form_element_settings = [
			'#type' => 'select',
			'#title' => $this->t('Contact Type'),
			'#options' => $contact_type_options,
			'#prefix' => '<div class="inline-components"',
			'#suffix' => '</div>',
		];

		$contact_name_form_element_settings = [
			'#type' => 'select',
			'#title' => $this
			  ->t("Contact Name"),
			'#options' => [
			  '' => $this
				->t(' - Select - '),
			  'dacn1' => $this
				->t('Dummy awardee contact name 1'),
			  'dacn2' => $this
				->t('Dummy awardee contact name 2'),
			  'dacn3' => $this
				->t('Dummy awardee contact name 3'),
			],
			'attributes' => [
				'class' => 'something',
			],

			// TODO: Check if this is the best way 
			'#prefix' => ($num_lines > 1) ? '<div class="inline-components-short">' : '<div class="inline-components">',
			  '#suffix' => '</div>',
		];

		$contact_delete_button_form_element_settings = [
			'#type' => 'submit',
			'#attributes' => array('onClick' => 'alert(1)'),
			'#value' => $this->t('Delete'),
			'#name' => 'delete_button',
			'#submit' => ['::removeCallback'],
			'#ajax' => [
			  'callback' => '::addmoreCallback',
			  'wrapper' => 'names-fieldset-wrapper',
			],
			"#limit_validation_errors" => array(),
			'#prefix' => '<div class="remove-button-container">',
			'#suffix' => '</div>',
		];

		// contact_delete_button_form_element_settings = []

		// This is temporary, need to go 
		for ($i = 0; $i < 10; $i++) {//num_indexes: get num of added contacts. (1->n)
			print_r("Num indexes go brr");
			if (in_array($i, $removed_fields)) {// Check if field was removed
				continue;
			}

			$form['names_fieldset'][$i]['contact_name'] = $contact_name_form_element_settings;

			$form['names_fieldset'][$i]['contact_type'] = $contact_type_form_element_settings;

			$form['names_fieldset'][$i]['actions'] = $contact_delete_button_form_element_settings;

			//css space for a new line due to previous items' float left attr
			$form['names_fieldset'][$i]['new_line_container'] = [
				'#markup' => '<div class="clear-space"></div>'
			];
			
		}

		
		
		$form['names_fieldset']['actions']['add_name'] = [
			'#type' => 'submit',
			'#button_type' => 'button',
			'#name' => 'add_contact_button',
			'#value' => t('Add Another Contact'),
			'#submit' => ['::addOne'],
			'#ajax' => [
				'callback' => '::addmoreCallback',
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
		
		// $this->buildProducerInformationSection($form, $form_state, $options);
		$form_state->setCached(FALSE);

		$form['actions']['save'] = [
			'#type' => 'submit',
			'#value' => $this->t('Save'),
		];

		$form['actions']['cancel'] = [
			'#type' => 'submit',
			'#value' => $this->t('Cancel'),
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
  public function submitForm(array &$form, FormStateInterface $form_state) {

	$this->messenger()->addStatus($this->t('Form submitted!'));

	if($form_state->getValues()['actions']['#value'] == t('Save')){
		$this
		->messenger()
		->addStatus($this
		->t('Form submitted for project @project_name', [
			'@project_name' => $form['project_name']['#value'],
		])
		);
	}
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'project_create_form';
  }

  public function addmoreCallback(array &$form, FormStateInterface $form_state) {
    return $form['names_fieldset'];
  }

  public function addOne(array &$form, FormStateInterface &$form_state) {
	print_r("BRRRR");
	$this->messenger()->addStatus('BRRRR');
    $num_indexes = $form_state->get('num_indexes');
	$num_lines = $form_state->get('num_lines');
    $form_state->set('num_indexes', $num_indexes + 1);
	$form_state->set('num_lines', $num_lines + 1);
    $form_state->setRebuild();
  }



  public function removeContactFromList(array &$form, FormStateInterface &$form_state){
	  $trigger = $form_state->getTriggeringElement();
  }



  public function removeCallback(array &$form, FormStateInterface &$form_state) {
	print_r("Removecallbackgobrrr");
    $trigger = $form_state->getTriggeringElement();
	$num_line = $form_state->get('num_lines');
    $indexToRemove = $trigger['#name'];

    // Remove the fieldset from $form (the easy way)
    unset($form['names_fieldset'][$indexToRemove]);

    // Keep track of removed fields so we can add new fields at the bottom
    // Without this they would be added where a value was removed
    $removed_fields = $form_state->get('removed_fields');
    $removed_fields[] = $indexToRemove;
    $form_state->set('removed_fields', $removed_fields);
	$form_state->set('num_lines', $num_line - 1);

    // Rebuild form_state
    $form_state->setRebuild();
  }
}