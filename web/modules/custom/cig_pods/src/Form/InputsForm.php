<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;

class InputsForm extends FormBase {

    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state, $operation = NULL){

        $form['#attached']['library'][] = 'cig_pods/inputs_form';

    $form['subtitle_1'] = [
			'#markup' => '<div class="subtitle-container"><h2>Input Information</h2><h4>5 Fields | Section 1 of 3</h4></div>'
		];

        $form['field_inputs_date'] = [
			'#type' => 'date',
			'#title' => $this->t('Date'),
			'#description' => '',
			// '#default_value' => $default_value_shmu_date_land_use_changed, // Default value for "date" field type is a string in form of 'yyyy-MM-dd'
			'#required' => FALSE
		];

        $form['field_inputs_input_category'] = [
			'#type' => 'select',
			'#title' => $this->t('Input Category'),
			//'#options' => $land_use_options,
			//'#default_value' => $field_shmu_current_land_use_value,
			'#required' => FALSE
		];

        $form['field_inputs_input'] = [
			'#type' => 'select',
			'#title' => $this->t('Input'),
			//'#options' => $land_use_options,
			//'#default_value' => $field_shmu_current_land_use_value,
			'#required' => FALSE
		];

        $form['field_inputs_unit'] = [
			'#type' => 'select',
			'#title' => $this->t('Unit'),
			//'#options' => $land_use_options,
			//'#default_value' => $field_shmu_current_land_use_value,
			'#required' => FALSE
		];

        $form['field_inputs_rate_units'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Rate Units/Ac'),
            '#description' => '',
            '#required' => FALSE
        ]; 

        $form['subtitle_2'] = [
			'#markup' => '<div class="subtitle-container"><h2>Custom Application</h2><h4>2 Fields | Section 2 of 3</h4></div>'
		];

         $form['field_inputs_cost_units'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Cost Per Unit'),
            '#description' => '',
            '#required' => FALSE
        ]; 

        $form['field_inputs_custom_unit'] = [
			'#type' => 'select',
			'#title' => $this->t('Unit'),
			//'#options' => $land_use_options,
			//'#default_value' => $field_shmu_current_land_use_value,
			'#required' => FALSE
		];

        $form['subtitle_3'] = [
			'#markup' => '<div class="subtitle-container"><h2>Other Costs</h2><h4>Section 3 of 3</h4></div>'
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
}