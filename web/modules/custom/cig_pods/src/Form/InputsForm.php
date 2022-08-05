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


    public function buildForm(array $form, FormStateInterface $form_state, $operation = NULL){

        $form['#attached']['library'][] = 'cig_pods/inputs_form';

    $form['subtitle_1'] = [
			'#markup' => '<div class="subtitle-container"><h2>Input Information</h2><h4>5 Fields | Section 1 of 3</h4></div>'
		];

        $form['field_input_date'] = [
			'#type' => 'date',
			'#title' => $this->t('Date'),
			'#description' => '',
			// '#default_value' => $default_value_shmu_date_land_use_changed, // Default value for "date" field type is a string in form of 'yyyy-MM-dd'
			'#required' => FALSE
		];

        $form['field_input_category'] = [
			'#type' => 'select',
			'#title' => $this->t('Input Category'),
			'#options' => $this->getInputCategoryOptions(),
			//'#default_value' => $field_shmu_current_land_use_value,
			'#required' => FALSE
		];

        $form['field_input'] = [
			'#type' => 'select',
			'#title' => $this->t('Input'),
			'#options' => $this->getInputOptions(),
			//'#default_value' => $field_shmu_current_land_use_value,
			'#required' => FALSE
		];

        $form['field_unit'] = [
			'#type' => 'select',
			'#title' => $this->t('Unit'),
			'#options' => $this->getUnitOptions(),
			//'#default_value' => $field_shmu_current_land_use_value,
			'#required' => FALSE
		];

        $form['field_rate_units'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Rate Units/Ac'),
            '#description' => '',
            '#required' => FALSE
        ]; 

        $form['subtitle_2'] = [
			'#markup' => '<div class="subtitle-container"><h2>Custom Application</h2><h4>2 Fields | Section 2 of 3</h4></div>'
		];

         $form['field_cost_per_unit'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Cost Per Unit'),
            '#description' => '',
            '#required' => FALSE
        ]; 

        $form['field_custom_application_unit'] = [
			'#type' => 'select',
			'#title' => $this->t('Unit'),
			'#options' => $this->getUnitOptions(),
			//'#default_value' => $field_shmu_current_land_use_value,
			'#required' => FALSE
		];

        $form['subtitle_3'] = [
			'#markup' => '<div class="subtitle-container"><h2>Other Costs</h2><h4>Section 3 of 3</h4></div>'
		];

        // $awardee_default_name = $is_edit ? $awardee->get('field_awardee')->target_id : NULL;
		// $form['field_awardee'] = [
		// 	'#type' => 'select',
		// 	'#title' => 'Awardee Organization Name',
		// 	'#options' => $awardee_options,
		// 	'#required' => TRUE,
		// 	'#default_value' => $awardee_default_name,
		// ];


		// $contact_name_options = $this->getAwardeeContactNameOptions();
		// $form['#tree'] = TRUE;
		// $form['names_fieldset'] = [
		//   '#prefix' => '<div id="names-fieldset-wrapper"',
		//   '#suffix' => '</div>',
		// ];

		// $eauth_default_id = $is_edit ? $awardee->get('field_awardee_eauth_id')->getValue() : '';
		// $contactname=array();
		// foreach ($eauth_default_id as $checks) {
		//  $eauth = $checks['value'];
		// 		$contactname[] = $eauth;
		// }

		// $contact_default_name = $is_edit ? $awardee->get('field_awardee_contact_type')->getValue() : '';
		// $contacttype=array();
		// 	foreach ($contact_default_name as $checks) {
		// 	 $detail = $checks['target_id'];
		// 	 $contacttype[] = $detail;
		// 	}

		// for ($i = 0; $i < $num_contacts; $i++) {//num_contacts: get num of added contacts. (1->n)

		// 	if (in_array($i, $removed_contacts)) {// Check if field was removed
		// 		continue;
		// 	}

		// 	$form['names_fieldset'][$i]['contact_name'] = [
		// 		'#type' => 'select',
		// 		'#title' => $this
		// 		  ->t("Contact Name"),
		// 		'#options' => $contact_name_options,
		// 		'#default_value' => $contactname[$i],
		// 		'attributes' => [
		// 			'class' => 'something',
		// 		],
		// 		'#prefix' => ($num_contact_lines > 1) ? '<div class="inline-components-short">' : '<div class="inline-components">',
		//   		'#suffix' => '</div>',
		// 	];

		// 	$form['names_fieldset'][$i]['contact_type'] = [
		// 		'#type' => 'select',
		// 		'#title' => $this
		// 		  ->t('Contact Type'),
		// 		'#options' => $contact_type_options,
		// 		'#default_value' => $contacttype[$i],
		// 		'#prefix' => '<div class="inline-components"',
		//   		'#suffix' => '</div>',
		// 	];

		// 	if($num_contact_lines > 1 && $i!=0){
		// 		$form['names_fieldset'][$i]['actions'] = [
		// 			'#type' => 'submit',
		// 			'#value' => $this->t('Delete'),
		// 			'#name' => $i,
		// 			'#submit' => ['::removeContactCallback'],
		// 			'#ajax' => [
		// 			  'callback' => '::addContactRowCallback',
		// 			  'wrapper' => 'names-fieldset-wrapper',
		// 			],
		// 			"#limit_validation_errors" => array(),
		// 			'#prefix' => '<div class="remove-button-container">',
		// 			'#suffix' => '</div>',
		// 		];
		// 	}

		// 	//css space for a new line due to previous items' float left attr
		// 	$form['names_fieldset'][$i]['new_line_container'] = [
		// 		'#markup' => '<div class="clear-space"></div>'
		// 	];

		// }




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
        return 'inputform';
    }

    /**
    * {@inheritdoc}
    */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $this
            ->messenger()
            ->addStatus($this
            ->t('Form submitted for inputform @inputform_name', [
            '@inputform_name' => $form['inputform_name']['#value'],
        ]));
    }

//     public function addContactRow(array &$form, FormStateInterface $form_state) {
//     $num_contacts = $form_state->get('num_contacts');
// 	$num_contact_lines = $form_state->get('num_contact_lines');
//     $form_state->set('num_contacts', $num_contacts + 1);
// 	$form_state->set('num_contact_lines', $num_contact_lines + 1);
//     $form_state->setRebuild();
//   }

//   public function removeContactCallback(array &$form, FormStateInterface $form_state) {
//     $trigger = $form_state->getTriggeringElement();
// 	$num_line = $form_state->get('num_contact_lines');
//     $indexToRemove = $trigger['#name'];

    // Remove the fieldset from $form (the easy way)
    // unset($form['names_fieldset'][$indexToRemove]);

    // // Keep track of removed fields so we can add new fields at the bottom
    // // Without this they would be added where a value was removed
    // $removed_contacts = $form_state->get('removed_contacts');
    // $removed_contacts[] = $indexToRemove;

	// $form_state->set('removed_contacts', $removed_contacts);
	// $form_state->set('num_contact_lines', $num_line - 1);

    // // Rebuild form_state
    // $form_state->setRebuild();
 // }
}