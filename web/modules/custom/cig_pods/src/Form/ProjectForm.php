<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;

class ProjectForm extends FormBase {

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
			'#required' => TRUE
		];
		
		$form['agreement_number'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Agreement Number'),
			'$description' => 'Agreement Number',
			'#required' => TRUE
		];

		$form['grant_type'] = [
			'#type' => 'select',
			'#title' => $this
			  ->t('Grant Type'),
			'#options' => [
			  '1' => $this
				->t('Type 1'),
			  '2' => $this
				->t('Type 2'),
			  '3' => $this
				->t('Type 3'),
			],
			'#required' => TRUE
		];

		$form['funding_amount'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Funding Amount'),
			'$description' => 'Funding Amount',
			'#required' => TRUE
		];

		$form['resource_concerns'] = [
			'#type' => 'checkboxes',
			'#title' => $this
			  ->t('Possible Resource Concerns'),
			'#options' => [
			  '1' => $this
				->t('Air - Emissions of airborne reactive nitrogen'),
			  '2' => $this
				->t('Air - Emissions of Greenhouse Gases (GHGs)'),
			  '3' => $this
				->t('Air - Emissions Of Ozone Precursors'),
			  '4' => $this
				->t('Air - Emissions Of Particulate Matter (PM) And PM Precursors'),
			  '5' => $this
				->t('Air - Objectionable Odor'),
			  '6' => $this
				->t('Animals - Aquatic habitat for fish and other organisms'),
			  '7' => $this
				->t('Animals - Elevated water temperature'),
			  '8' => $this
				->t('Animals - Feed and forage balance'),
			  '9' => $this
				->t('Animals - Habitat Degradation'),
			  '10' => $this
				->t('Animals - Inadequate Feed and Forage'),
			  '11' => $this
				->t('Animals - Inadequate livestock shelter'),
			  '12' => $this
				->t('Animals - Inadequate livestock water quantity, quality and distribution'),
			  '13' => $this
				->t('Animals - Inadequate Water'),
			],
			'#multiple' => TRUE,
			'#required' => TRUE
		];

		$form['project_summary'] = [
			'#type' => 'textarea',
			'#title' => $this->t('Project Summary'),
			'$description' => 'Project Summary',
			'#required' => TRUE
		];

		$form['management_tags'] = [
			'#type' => 'checkboxes',
			'#title' => $this
			  ->t('Management Tags'),
			'#options' => [
			  'ct' => $this
				->t('Conventional Tillage'),
			  'ncc' => $this
				->t('No Cover Crop'),
			  'ccm' => $this
				->t('Cover Crop Mix'),
			  'nt' => $this
				->t('No-Till'),
			  'cm' => $this
				->t('Crop Mix'),
			],
			'#multiple' => TRUE,
			'#required' => TRUE
		];

		/* Awardee Information */
		$form['subform_2'] = [
			'#markup' => '<div class="subform-title-container"><h2>Awardee Information</h2><h4>Section 2 of 3</h4></div>'
		];

		$form['organization_name'] = [
			'#type' => 'select',
			'#title' => $this
			  ->t('Awardee Organization Name'),
			'#options' => [
			  '1' => $this
				->t('Name 1'),
			  '2' => $this
				->t('Name 2'),
			  '3' => $this
				->t('Name 3'),
			],
			'#required' => TRUE
		];

		$form['create_organization'] = [
			'#type' => 'button',
			'#value' => $this->t('Create New Awardee Organization(s)'),
			'#attributes' => array('onClick' => 'window.location.href="awardee_org"'),
		];

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
				'#options' => [
				  '' => $this
					->t(' - Select - '),
				  'aw' => $this
					->t('Agatha Wallace'),
				  'po' => $this
					->t('Prescott Olehui'),
				  'rr' => $this
					->t('Rachel Rutherford'),
				],
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
				'#options' => [
				  '' => $this
					->t(' - Select - '),
				  'apc' => $this
					->t('Awardee Principal Contact'),
				  'atc' => $this
					->t('Awardee Technical Contact'),
				  'aac' => $this
					->t('Awardee Administrative Contact'),
				],
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

		$num_last_index = $num_indexes -1;
		$form['names_fieldset']['actions']['add_name'] = [
			'#type' => 'button',
			'#value' => $this->t('Add Another Contact'),
			'#submit' => ['::addOne'],
			'#ajax' => [
				'callback' => '::addmoreCallback',
				'wrapper' => 'names-fieldset-wrapper',
			],
			'#states' => [
				'visible' => [
				  ":input[name='names_fieldset[$num_last_index][contact_name]']" => ['!value' => ''],
				  "and",
				  ":input[name='names_fieldset[$num_last_index][contact_type]']" => ['!value' => ''],
				],
			],
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