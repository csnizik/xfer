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

       private function pageLookup(string $path) {
        $match = [];
        $path2 =  $path;
        $router = \Drupal::service('router.no_access_checks');

        try {
            $match = $router->match($path2);
        }
        catch (\Exception $e) {
          // The route using that path hasn't been found,
          // or the HTTP method isn't allowed for that route.
        }
        return $match['_route'];
    }

    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state, $id = NULL){

        $labTestProfile = [];

         $is_edit = $id <> NULL;
 
        if($is_edit){
            $form_state->set('operation','edit');
            $form_state->set('lab_test_id',$id);
            $labTestProfile = \Drupal::entityTypeManager()->getStorage('asset')->load($id);
          

        } else {
            $form_state->set('operation','create');
        }


        if($form_state->get('operation') == 'create'){

        }


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
$profile_name = $is_edit ?  $labTestProfile->get('name')->value : "";
    $form['test_profile_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Test Profile Name'),
        '#default_value' => $profile_name,
        '#required' => TRUE
    ]; 

    // $form['laboratory'] = [
	// 		'#type' => 'select',
	// 		'#title' => 'Laboratory',
	// 		'#options' => $lab,
	// 		'#required' => TRUE
	// 	];

    // $form['aggregate_method'] = [
	// 		'#type' => 'select',
	// 		'#title' => 'Aggregate Stability Method',
	// 		'#options' => $agg_stab_method,
	// 		'#required' => TRUE
	// 	];

    // $form['aggregate_unit'] = [
	// 		'#type' => 'select',
	// 		'#title' => 'Aggregate Stability Unit',
	// 		'#options' => $agg_stab_unit,
	// 		'#required' => TRUE
	// 	];

    // $form['respiratory_incubation'] = [
	// 		'#type' => 'select',
	// 		'#title' => 'Respiration Incubation Days',
	// 		'#options' => $sdhe,
	// 		'#required' => TRUE
	// 	];

    // $form['respiratory_detection'] = [
	// 		'#type' => 'select',
	// 		'#title' => 'Respiration Detection Method (unit ppm)',
	// 		'#options' => $resp_detect,
	// 		'#required' => TRUE
	// 	];
   $ph_method_default_value =  $is_edit ?  $labTestProfile->get('ph_method')->value : "";
    $form['ph_method'] = [
			'#type' => 'select',
			'#title' => 'pH Method',
			'#options' => $ph_method,
             '#default_value' => $ph_method_default_value,
			'#required' => TRUE
		];

$electroconductivity_method_default_value =  $is_edit ?  $labTestProfile->get('electroconductivity_method')->value : "";
    $form['electroconductivity_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Electroconductivity Method (EC (Unit dS/m))'),
        '#options' => $ec_method,
        '#default_value' => $electroconductivity_method_default_value,
        '#required' => TRUE
    ]; 

    $form['nitrate_n_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Nitrate-N Method (Unit ppm)'),
        '#options' => $nitrate_method,
        '#required' => TRUE
    ]; 

    $form['phosphorus_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Phosphorus Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#required' => TRUE
    ]; 

    $form['potassium_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Potassium Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#required' => TRUE
    ]; 

    $form['calcium_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Calcium Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#required' => TRUE
    ]; 

    $form['magnesium_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Magnesium Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#required' => TRUE
    ]; 

    $form['sulfur_method'] = [
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

    $form['manganese_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Manganese Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#required' => TRUE
    ]; 

    $form['copper_method'] = [
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

    $form['aluminum_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Aluminum Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#required' => TRUE
    ]; 

    $form['molybdenum_method'] = [
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
   //dpm($form_state);

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
        $profile_submission = [];
    if($form_state->get('operation') == 'create'){
        echo("<script>console.log('create');</script>"); 
        // $profile_submission['laboratory'] = $form_state->getValue('laboratory');
        // $profile_submission['aggregate_method'] = $form_state->getValue('aggregate_method');
        // $profile_submission['aggregate_unit'] = $form_state->getValue('aggregate_unit');
        // $profile_submission['respiratory_incubation'] = $form_state->getValue('respiratory_incubation');
        // $profile_submission['respiratory_detection'] = $form_state->getValue('respiratory_detection');
        $profile_submission['ph_method'] = $form_state->getValue('ph_method');
        $profile_submission['electroconductivity_method'] = $form_state->getValue('electroconductivity_method');
        $profile_submission['nitrate_n_method'] = $form_state->getValue('nitrate_n_method');
        $profile_submission['phosphorus_method'] = $form_state->getValue('phosphorus_method');
        $profile_submission['potassium_method'] = $form_state->getValue('potassium_method');
        $profile_submission['calcium_method'] = $form_state->getValue('calcium_method');
        $profile_submission['magnesium_method'] = $form_state->getValue('magnesium_method');
        $profile_submission['sulfur_method'] = $form_state->getValue('nitrate_method');
        $profile_submission['iron_method'] = $form_state->getValue('iron_method');
        $profile_submission['manganese_method'] = $form_state->getValue('manganese_method');
        $profile_submission['copper_method'] = $form_state->getValue('copper_method');
        $profile_submission['zinc_method'] = $form_state->getValue('zinc_method');
        $profile_submission['boron_method'] = $form_state->getValue('boron_method');
        $profile_submission['aluminum_method'] = $form_state->getValue('aluminum_method');
        $profile_submission['molybdenum_method'] = $form_state->getValue('molybdenum_method');

        $profile_submission['type'] = 'lab_testing_profile';
        $profile_submission['name'] = $form_state->getValue('test_profile_name');

        $profile = Asset::create($profile_submission);
	    $profile -> save();

        $route = $this->pageLookup('/assets/lab_testing_profile');
        $form_state->setRedirect($route);

        }else{
             echo("<script>console.log('edit');</script>"); 
        $id = $form_state->get('lab_test_id');
        $labTestProfile = \Drupal::entityTypeManager()->getStorage('asset')->load($id);

     
        $labTestProfile->set('name', $form_state->getValue('test_profile_name'));

        $labTestProfile->save();
        $route = $this->pageLookup('/assets/lab_testing_profile');
        $form_state->setRedirect($route);

        }
     }
}