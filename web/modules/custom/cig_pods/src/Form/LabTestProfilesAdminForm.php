<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;

class LabTestProfilesAdminForm extends FormBase {

    public function getSoilHealthExtractionOptions($bundle){
        $shde_options = [];
        $shde_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
            [
                'vid' => $bundle,
            ]
        );
        $shde_keys = array_keys($shde_terms);
        foreach($shde_keys as $shde_key){
            $term = $shde_terms[$shde_key];
            $sdhe_options[$shde_key] = $term -> getName();        
        }
        return $sdhe_options;
    }

    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state, $options = NULL){

    $form['#attached']['library'][] = 'cig_pods/lab_test_profiles_admin_form';

   
    $agg_stab_method = $this->getSoilHealthExtractionOptions("d_aggregate_stability_me");
    $agg_stab_unit = $this->getSoilHealthExtractionOptions("d_aggregate_stability_un");
    $ec_method = $this->getSoilHealthExtractionOptions("d_ec_method");
    $lab = $this->getSoilHealthExtractionOptions("d_laboratory");
    $nitrate_method = $this->getSoilHealthExtractionOptions("d_nitrate_n_method");
    $ph_method = $this->getSoilHealthExtractionOptions("d_ph_method");
    $resp_detect = $this->getSoilHealthExtractionOptions("d_respiration_detection_");
    $s_he_extract = $this->getSoilHealthExtractionOptions("d_soil_health_extraction");
    
    
    $form['lab_test_title'] = [
        '#markup' => '<h1>Lab Test Profiles</h1>',
    ]; 

    $form['test_profile_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Test Profile Name'),
        '#required' => TRUE
    ]; 

    $form['laboratory'] = [
			'#type' => 'select',
			'#title' => 'Laboratory',
			'#options' => $lab,
			'#required' => TRUE
		];

    $form['aggregate'] = [
			'#type' => 'select',
			'#title' => 'Aggregate Stability Method',
			'#options' => $agg_stab_method,
			'#required' => TRUE
		];

    $form['aggregate_unit'] = [
			'#type' => 'select',
			'#title' => 'Aggregate Stability Unit',
			'#options' => $agg_stab_unit,
			'#required' => TRUE
		];

    // $form['resp_incubation'] = [
	// 		'#type' => 'select',
	// 		'#title' => 'Respiration Incubation Days',
	// 		'#options' => $sdhe,
	// 		'#required' => TRUE
	// 	];

    $form['resp_detection'] = [
			'#type' => 'select',
			'#title' => 'Respiration Detection Method (unit ppm)',
			'#options' => $resp_detect,
			'#required' => TRUE
		];

    $form['pH_Method'] = [
			'#type' => 'select',
			'#title' => 'pH Method',
			'#options' => $ph_method,
			'#required' => TRUE
		];

    $form['electr_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Electroconductivity Method (EC (Unit dS/m))'),
        '#options' => $ec_method,
        '#required' => TRUE
    ]; 

    $form['nitrate_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Nitrate-N Method (Unit ppm)'),
        '#options' => $nitrate_method,
        '#required' => TRUE
    ]; 

    $form['phos_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Phosphorus Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#required' => TRUE
    ]; 

    $form['potas_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Potassium Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#required' => TRUE
    ]; 

    $form['calc_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Calcium Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#required' => TRUE
    ]; 

    $form['magn_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Magnesium Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#required' => TRUE
    ]; 

    $form['sulf_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Sulfur Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#required' => TRUE
    ]; 

    $form['iron_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Iron Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#required' => TRUE
    ]; 

    $form['mang_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Manganese Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#required' => TRUE
    ]; 

    $form['cop_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Copper Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#required' => TRUE
    ]; 

    $form['zinc_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Zinc Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#required' => TRUE
    ]; 

    $form['boron_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Boron Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#required' => TRUE
    ]; 

    $form['alum_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Aluminum Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#required' => TRUE
    ]; 

    $form['moly_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Molybdenum Methon (Unit ppm)'),
        '#options' => $s_he_extract,
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
        return 'lab_test_profiles_admin';
    }

    /**
    * {@inheritdoc}
    */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        // $this
        //     ->messenger()
        //     ->addStatus($this
        //     ->t('Form submitted for  @_name', [
        //     '@_name' => $form['nitrate_method']['#value'],
        // ]));
        // echo("<script>console.log('submit: " . $form['nitrate_method']['#value'] . "');</script>"); 
//          $profile = []
//  $profile['key_name'] = $form_state->getValue('form_elem_id')
// ... Repeat for all fields

// $profile['type'] = 'lab_testing_profile'
// $profile['name'] = $form->getValue('form_name_elem,_id')

// $asset = Asset::create($profile);
// $asset->save();
     }
}
//put inside submit function, fill in name
