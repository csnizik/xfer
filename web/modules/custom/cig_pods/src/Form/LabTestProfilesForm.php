<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;

class LabTestProfilesForm extends FormBase {

    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state, $options = NULL){

    $form['lab_test_title'] = [
        '#markup' => '<h1>Lab Test Profiles</h1>',
    ]; 

    $form['test_profile_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Test Profile Name'),
        '#description' => 'Test Profile Name',
        '#required' => TRUE
    ]; 

    $form['laboratory'] = [
			'#type' => 'select',
			'#title' => 'Laboratory',
			'#options' => $awardee_options,
			'#required' => TRUE
		];

    $form['aggregate'] = [
			'#type' => 'select',
			'#title' => 'Aggregate Stability Method',
			'#options' => $awardee_options,
			'#required' => TRUE
		];

    $form['resp_incubation'] = [
			'#type' => 'select',
			'#title' => 'Respiration Incubation Days',
			'#options' => $awardee_options,
			'#required' => TRUE
		];

    $form['resp_detection'] = [
			'#type' => 'select',
			'#title' => 'Respiration Detection Method (unit ppm)',
			'#options' => $awardee_options,
			'#required' => TRUE
		];

    $form['electr_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Electroconductivity Method (EC (Unit dS/m))'),
        '#options' => '',
        '#required' => TRUE
    ]; 

    $form['nitrate_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Nitrate-N Method (Unit ppm)'),
        '#options' => '',
        '#required' => TRUE
    ]; 

    $form['phos_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Phosphorus Method (Unit ppm)'),
        '#options' => '',
        '#required' => TRUE
    ]; 

    $form['potas_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Potassium Method (Unit ppm'),
        '#options' => '',
        '#required' => TRUE
    ]; 

    $form['calc_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Calcium Method (Unit ppm)'),
        '#options' => '',
        '#required' => TRUE
    ]; 

    $form['magn_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Magnesium Method (Unit ppm)'),
        '#options' => '',
        '#required' => TRUE
    ]; 

    $form['sulf_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Sulfur Method (Unit ppm)'),
        '#options' => '',
        '#required' => TRUE
    ]; 

    $form['iron_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Iron Method (Unit ppm)'),
        '#options' => '',
        '#required' => TRUE
    ]; 

    $form['mang_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Manganese Method (Unit ppm)'),
        '#options' => '',
        '#required' => TRUE
    ]; 

    $form['cop_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Copper Method (Unit ppm)'),
        '#options' => '',
        '#required' => TRUE
    ]; 

    $form['zinc_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Zinc Method (Unit ppm)'),
        '#options' => '',
        '#required' => TRUE
    ]; 

    $form['boron_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Boron Method (Unit ppm)'),
        '#options' => '',
        '#required' => TRUE
    ]; 

    $form['alum_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Aluminum Method (Unit ppm)'),
        '#options' => '',
        '#required' => TRUE
    ]; 

    $form['moly_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Molybdenum Methon (Unit ppm)'),
        '#options' => '',
        '#required' => TRUE
    ]; 

    $form['actions']['save'] = [
        '#type' => 'submit',
        '#value' => $this->t('Save'),
    ]; 

    $form['actions']['cancel'] = [
			'#type' => 'button',
			'#value' => $this->t('Cancel'),
			// '#attributes' => array('onClick' => 'window.location.href="/dashboard"'),
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
        return '';
    }

    /**
    * {@inheritdoc}
    */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $this
            ->messenger()
            ->addStatus($this
            ->t('Form submitted for  @_name', [
            '@_name' => $form['_name']['#value'],
        ]));
    }
}