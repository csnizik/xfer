<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;

class ProjectForm extends FormBase {



	public function getAwardeeOptions(){
		$awardee_assets = \Drupal::entityTypeManager() -> getStorage('asset') -> loadByProperties(
			['type' => 'awardee']
		);
		$awardee_options = array();
		$awardee_keys = array_keys($awardee_assets);
		foreach($awardee_keys as $awardee_key) {
		  $asset = $awardee_assets[$awardee_key];
		  $awardee_options[$awardee_key] = $asset->getName();
		}

		return $awardee_options;
	}


	# Making this function dynamically pull contact names breaks AJAX calls.
	public function getAwardeeContactNameOptions(){
		$contact_name_options = array();
		$contact_name_options[''] = ' - Select -';
		$contact_name_options['aw'] = 'Agatha Wallace';

		return $contact_name_options;
	}

	// TODO: improvement: Make this dynamic
	public function getAwardeeContactTypeOptions(){
		$contact_type_options[''] = '- Select -';
		$contact_type_options['0'] = 'Awardee Administrative Contact';
		$contact_type_options['1'] = 'Awardee Principal Contact';
		$contact_type_options['2'] = 'Awardee Technical Contact';


		return $contact_type_options;
	}
	
	public function getResourceConcernOptions(){
		$resource_concern_options = [];
		$resource_concern_terms = \Drupal::entityTypeManager() -> getStorage('taxonomy_term') -> loadByProperties(
			['vid' => 'd_resource_concern']
		 );

		 $resource_concern_keys = array_keys($resource_concern_terms);
 
		 foreach($resource_concern_keys as $resource_concern_key) {
		   $term = $resource_concern_terms[$resource_concern_key];
		   $resource_concern_options[$resource_concern_key] = $term -> getName();
		 }
		
		return $resource_concern_options;
	}

	public function getGrantTypeOptions(){

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
		return $grant_type_options;
	}
	
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

		// Start: Taxonomy term loaded dynamically example
		$grant_type_options = $this->getGrantTypeOptions();
		
		$form['grant_type'] = [
			'#type' => 'select',
			'#title' => $this
			  ->t('Grant Type'),
			'#options' => $grant_type_options,
			'#required' => FALSE
		];
		// End: Taxonomy term loaded dynamically example

		$form['funding_amount'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Funding Amount'),
			'$description' => 'Funding Amount',
			'#required' => FALSE
		];

		$resource_concern_options = $this->getResourceConcernOptions();
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

	}
   /**
   * {@inheritdoc}
   */
	public function buildForm(array $form, FormStateInterface $form_state, $options = NULL){

		$form['#attached']['library'][] = 'cig_pods/project_entry_form';

		$num_lines = $form_state->get('num_lines');//get num of contacts showing on screen. (1->n exclude:removed indexes)
		$num_indexes = $form_state->get('num_indexes');//get num of added contacts. (1->n)

		if ($num_indexes === NULL) {//initialize number of contact, set to 1
			$form_state->set('num_indexes', 1);
			$num_indexes = $form_state->get('num_indexes');
		}
		if ($num_lines === NULL) {
			$form_state->set('num_lines', 1);
			$num_lines = $form_state->get('num_lines');
		}

		$removed_fields = $form_state->get('removed_fields');//get removed contacts indexes
		if ($removed_fields === NULL) {
			$form_state->set('removed_fields', array());//initialize arr
			$removed_fields = $form_state->get('removed_fields');
		}
		/* Variables declaration end*/

		$this->buildProjectInformationSection($form, $form_state);

		$awardee_options = $this->getAwardeeOptions();
		$contact_name_options = $this->getAwardeeContactNameOptions();
		$contact_type_options = $this->getAwardeeContactTypeOptions();
		/* Awardee Information */
		$form['subform_2'] = [
			'#markup' => '<div class="subform-title-container"><h2>Awardee Information</h2><h4>Section 2214 of 3</h4></div>'
		];

		$form['organization_name'] = [
			'#type' => 'select',
			'#title' => 'Awardee Organization Name',
			'#options' => $awardee_options,
			'#required' => TRUE
		];


		// $contact_name_options = $this->getAwardeeContactNameOptions();
		$form['#tree'] = TRUE;
		$form['names_fieldset'] = [
		  '#prefix' => '<div id="names-fieldset-wrapper"',
		  '#suffix' => '</div>',
		];



		for ($i = 0; $i < $num_indexes; $i++) {//num_indexes: get num of added contacts. (1->n)

			if (in_array($i, $removed_fields)) {// Check if field was removed
				continue;
			}

			$form['names_fieldset'][$i]['contact_name'] = [
				'#type' => 'select',
				'#title' => $this
				  ->t("Contact Name"),
				'#options' => $contact_name_options,
				'attributes' => [
					'class' => 'something',
				],
				'#prefix' => ($num_lines > 1) ? '<div class="inline-components-short">' : '<div class="inline-components">',
		  		'#suffix' => '</div>',
			];

			$form['names_fieldset'][$i]['contact_type'] = [
				'#type' => 'select',
				'#title' => $this
				  ->t('Contact Type'),
				'#options' => $contact_type_options,
				'#prefix' => '<div class="inline-components"',
		  		'#suffix' => '</div>',
			];

			if($num_lines > 1 && $i!=0){
				$form['names_fieldset'][$i]['actions'] = [
					'#type' => 'submit',
					'#value' => $this->t('Delete'),
					'#name' => $i,
					'#submit' => ['::removeCallback'],
					'#ajax' => [
					  'callback' => '::addmoreCallback',
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

		/* Producers Information */
		$form['subform_3'] = [
			'#markup' => '<div class="subform-title-container"><h2>Producers</h2><h4>Section 3 of 3</h4></div>'
		];

		$form['producer_contact_name'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Producer Contact Name'),
			'$description' => 'Producer Contact Name',
			'#required' => TRUE
		];
		/* Producers Information Ends*/

		$form['actions'] = [
			'#type' => 'actions',
		];

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
	if($form_state['values']['op'] == t('Save')){
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

  public function addOne(array &$form, FormStateInterface $form_state) {
    $num_field = $form_state->get('num_indexes');
	$num_line = $form_state->get('num_lines');
    $add_button = $num_field + 1;
    $form_state->set('num_indexes', $add_button);
	$form_state->set('num_lines', $num_line + 1);
    $form_state->setRebuild();
  }

  public function removeCallback(array &$form, FormStateInterface $form_state) {
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