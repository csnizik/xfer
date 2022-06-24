<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;
Use Drupal\Core\Render\Element\Checkboxes;
Use Drupal\Core\Url;



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


	# Eventually, this function will get replaced with a call to EAuth to find registered users.
	public function getAwardeeContactNameOptions(){
		$contact_name_options = array();
		$contact_name_options[''] = ' - Select -';
		$contact_name_options['Agatha Wallace'] = 'Agatha Wallace';
		$contact_name_options['Prescott Olehui'] = 'Prescott Olehui';
		$contact_name_options['Rachel Rutherford'] = 'Rachel Rutherford';

		return $contact_name_options;
	}

	public function getAwardeeContactTypeOptions(){
		$contact_type_options = [];
		$contact_type_options[''] = ' - Select -';

		$contact_type_terms = \Drupal::entityTypeManager() -> getStorage('taxonomy_term') -> loadByProperties(
		   ['vid' => 'd_contact_type']
		);
		$contact_type_keys = array_keys($contact_type_terms);
		foreach($contact_type_keys as $contact_type_key) {
		  $term = $contact_type_terms[$contact_type_key];
		  $contact_type_options[$contact_type_key] = $term -> getName();
		}

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

	public function getProducerOptions() {
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
			'#required' => TRUE,
		];

		$form['agreement_number'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Agreement Number'),
			'$description' => 'Agreement Number',
			'#required' => TRUE,
		];


		$form['funding_amount'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Funding Amount'),
			'$description' => 'Funding Amount',
			'#required' => TRUE,
		];

		$resource_concern_options = $this->getResourceConcernOptions();
		$form['resource_concern'] = [
		  '#type' => 'checkboxes',
		  '#title' => t('Possible Resource Concerns'),
		  '#options' => $resource_concern_options,
		  '#required' => TRUE,
		];

		$form['project_summary'] = [
			'#type' => 'textarea',
			'#title' => $this->t('Project Summary'),
			'$description' => 'Project Summary',
			'#required' => TRUE,
		];

	}
   /**
   * {@inheritdoc}
   */
	public function buildForm(array $form, FormStateInterface $form_state, $options = NULL){

		$form['#attached']['library'][] = 'cig_pods/project_entry_form';

		$num_contact_lines = $form_state->get('num_contact_lines');//get num of contacts showing on screen. (1->n exclude:removed indexes)
		$num_contacts = $form_state->get('num_contacts');//get num of added contacts. (1->n)
		$removed_contacts = $form_state->get('removed_contacts');//get removed contacts indexes

		if ($num_contacts === NULL) {//initialize number of contact, set to 1
			$form_state->set('num_contacts', 1);
			$num_contacts = $form_state->get('num_contacts');
		}
		if ($num_contact_lines === NULL) {
			$form_state->set('num_contact_lines', 1);
			$num_contact_lines = $form_state->get('num_contact_lines');
		}

		if ($removed_contacts === NULL) {
			$form_state->set('removed_contacts', array());//initialize arr
			$removed_contacts = $form_state->get('removed_contacts');
		}


		$num_producer_lines = $form_state->get('num_producer_lines');
		$num_producers = $form_state->get('num_producers');
		$removed_producers = $form_state->get('removed_producers');

		if ($num_producer_lines == NULL){
			$form_state->set('num_producer_lines', 1);
			$num_producer_lines = $form_state->get('num_producer_lines');
		}

		if ($num_producers === NULL) {
			$form_state->set('num_producers', 1);
			$num_producers = $form_state->get('num_producers');
		}

		if($removed_producers === NULL ){
			$form_state->set('removed_producers', array());
			$removed_producers = $form_state->get('removed_producers');
		}



		/* Variables declaration end*/

		$this->buildProjectInformationSection($form, $form_state);

		$awardee_options = $this->getAwardeeOptions();
		$contact_name_options = $this->getAwardeeContactNameOptions();
		$contact_type_options = $this->getAwardeeContactTypeOptions();
		$producer_options = $this->getProducerOptions();
		/* Awardee Information */
		$form['subform_2'] = [
			'#markup' => '<div class="subform-title-container"><h2>Awardee Information</h2><h4>Section 2 of 3</h4></div>'
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



		for ($i = 0; $i < $num_contacts; $i++) {//num_contacts: get num of added contacts. (1->n)

			if (in_array($i, $removed_contacts)) {// Check if field was removed
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
				'#prefix' => ($num_contact_lines > 1) ? '<div class="inline-components-short">' : '<div class="inline-components">',
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

		/* Producers Information */
		$form['subform_3'] = [
			'#markup' => '<div class="subform-title-container"><h2>Producers</h2><h4>Section 3 of 3</h4></div>'
		];


		$form['producers_fieldset'] = [
			'#prefix' => '<div id="producers-fieldset-wrapper"',
			'#suffix' => '</div>',
		  ];
		for($i = 0; $i < $num_producers; $i++){
			if(in_array($i,$removed_producers)){
				continue;
			}

			$form['producers_fieldset'][$i]['producer_name'] = [
				'#type' => 'select',
				'#title' => $this->t("Producer Name"),
				'#options' => $producer_options,
				'#prefix' => ($num_producer_lines > 1 && $i != 0) ? '<div class="inline-components-short">' : '<div class="inline-components">',
				'#suffix' => '</div>',
			];


			if($num_producer_lines > 1 && $i != 0){
				$form['producers_fieldset'][$i]['actions'] = [
					'#type' => 'submit',
					'#value' => $this->t('Delete'),
					'#name' => $i,
					'#submit' => ['::removeProducerCallback'],
					'#ajax' => [
					  'callback' => '::addProducerRowCallback',
					  'wrapper' => 'producers-fieldset-wrapper',
					],
					"#limit_validation_errors" => array(),
					'#prefix' => '<div class="remove-button-container">',
					'#suffix' => '</div>',
				];
			}

			$form['producers_fieldset'][$i]['new_line_container'] = [
				'#markup' => '<div class="clear-space"></div>'
			];
		}
		$form['producers_fieldset']['actions']['add_producer'] = [
			'#type' => 'submit',
			'#button_type' => 'button',
			'#name' => 'add_producer_button',
			'#value' => t('Add Another Producer'),
			'#submit' => ['::addProducerRow'],
			'#ajax' => [
				'callback' => '::addProducerRowCallback',
				'wrapper' => 'producers-fieldset-wrapper',
			],
			'#states' => [
				'visible' => [
				  ":input[name='producers_fieldset[0][producer_name]']" => ['!value' => ''],
				],
			],
			"#limit_validation_errors" => array(),
			'#prefix' => '<div id="addmore-button-container">',
			'#suffix' => '</div>',
		];

		/* Producers Information Ends*/


		$form_state->setCached(FALSE);

		$form['actions']['save'] = [
			'#type' => 'submit',
			'#value' => $this->t('Save'),
		];

		$form['actions']['cancel'] = [
			'#type' => 'button',
			'#value' => $this->t('Cancel'),
			'#attributes' => array('onClick' => 'window.location.href="/dashboard"'),
		];
		return $form;
	}


 /**
  * Returns True if all values in array is unique, false otherwise
  */
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
	$num_producers = count($form['producers_fieldset']) - 1; // Minus 1 because there is an entry with key 'actions' for the "Add Another Producer Button"
	$num_contacts = count($form['names_fieldset']) - 1; // Minus 1 as above

	$producers = [];
	for( $i = 0; $i < $num_producers; $i++ ){
		$producer_id = $form['producers_fieldset'][$i]['producer_name']['#value'];
		$producers[$i] = $producer_id;
	}
	// Check $producers array for duplicate values
	if (!$this->arrayValuesAreUnique($producers) ){
		$form_state->setError(
			$form['producers_fieldset'],
			$this->t('Each Producer selection must be unique'),
		);
	}

	$contact_names = [];
	for($i = 0; $i < $num_contacts; $i++){
		$contact_name_id = $form['names_fieldset'][$i]['contact_name']['#value'];
		$contact_names[$i] = $contact_name_id;
	}

	// Check $contact_names array for duplicate values
	if( !$this->arrayValuesAreUnique($contact_names)){
		$form_state->setError(
			$form['names_fieldset'],
			$this->t('Each contact name selection must be unique'),
		);
	}

	return;
  }


  public function getFormEntityMapping(){
	  $mapping = [];

	  $mapping['project_name'] = 'name';
	  $mapping['agreement_number'] = 'field_project_agreement_number';
	  $mapping['funding_amount'] = 'field_funding_amount';
	  $mapping['project_summary'] = 'field_summary';
	  $mapping['organization_name'] = 'field_awardee';

      return $mapping;

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
	$values = $form_state->getValues();

	$mapping = $this->getFormEntityMapping();

	$project_submission = [];

	$project_submission['type'] = 'project';

	// Single value fields can be mapped in
	foreach($mapping as $form_elem_id => $entity_field_id){
		// If mapping not in form or value is empty string
		if($form[$form_elem_id] === NULL || $form[$form_elem_id] === ''){
			continue;
		}
		$project_submission[$entity_field_id] = $form[$form_elem_id]['#value'];
	}
	// Read from multivalued checkbox
	$checked_resource_concerns = Checkboxes::getCheckedCheckboxes($form_state->getValue('resource_concern'));

	$project_submission['field_resource_concerns'] = $checked_resource_concerns;

	$num_producers = count($form['producers_fieldset']) - 1; // Minus 1 because there is an entry with key 'actions'
	$num_contacts = count($form['names_fieldset']) - 1; // As above


	$producers = [];
	for( $i = 0; $i < $num_producers; $i++ ){
		$producers[$i] = $form['producers_fieldset'][$i]['producer_name']['#value'];
	}

	$project_submission['field_producer_contact_name'] = $producers;


	$contact_eauth_ids = [];
	$contact_types = [];
	for( $i = 0; $i < $num_contacts; $i++ ){
		$contact_eauth_ids[$i] = $form['names_fieldset'][$i]['contact_name']['#value'];
		$contact_types[$i] = $form['names_fieldset'][$i]['contact_type']['#value'];
	}

	$project_submission['field_awardee_eauth_id'] = $contact_eauth_ids;
	$project_submission['field_awardee_contact_type'] = $contact_types;


	$project = Asset::create($project_submission);
	$project -> save();
	$form_state->setRedirect('cig_pods.admin_dashboard_form');

	return;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'project_create_form';
  }

  public function addContactRowCallback(array &$form, FormStateInterface $form_state) {
    return $form['names_fieldset'];
  }
  public function addProducerRowCallback(array &$form, FormStateInterface $form_state) {
    return $form['producers_fieldset'];
  }

  public function addProducerRow(array &$form, FormStateInterface $form_state){
	  $num_producers = $form_state->get('num_producers');
	  $num_producer_lines = $form_state->get('num_producer_lines');
	  $form_state->set('num_producers', $num_producers + 1);
	  $form_state->set('num_producer_lines',$num_producer_lines + 1);
	  $form_state->setRebuild();
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

  public function removeProducerCallback(array &$form, FormStateInterface $form_state){
    $trigger = $form_state->getTriggeringElement();
	$num_producer_lines = $form_state->get('num_producer_lines');
	$indexToRemove = $trigger['#name'];

	unset($form['producers_fieldset'][$indexToRemove]);

	$removed_producers = $form_state->get('removed_producers');
	$removed_producers[] = $indexToRemove;

	$form_state->set('removed_producers',$removed_producers);
	$form_state->set('num_producer_lines', $num_producer_lines);

	$form_state->setRebuild();

  }
}