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

private function createElementNames(){
  return array('name', 'field_profile_laboratory','field_profile_aggregate_stability_method', 'field_profile_respiratory_incubation_days', 'field_profile_respiration_detection_method', 'electroconductivity_method', 'nitrate_n_method', 'phosphorus_method', 'potassium_method', 'calcium_method', 'magnesium_method', 'sulfur_method','iron_method','manganese_method', 'copper_method', 'zinc_method', 'boron_method', 'aluminum_method', 'molybdenum_method');

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
    $ec_method = $this->getSoilHealthExtractionOptions("d_ec_method");
    $lab = $this->getSoilHealthExtractionOptions("d_laboratory");
    $nitrate_method = $this->getSoilHealthExtractionOptions("d_nitrate_n_method");
    $ph_method = $this->getSoilHealthExtractionOptions("d_ph_method");
    $resp_detect = $this->getSoilHealthExtractionOptions("d_respiration_detection_");
     $resp_incub = $this->getSoilHealthExtractionOptions("d_respiration_incubation");
    $s_he_extract = $this->getSoilHealthExtractionOptions("d_soil_health_extraction");
    
    $form['lab_test_title'] = [
        '#markup' => '<h1>Lab Test Profiles</h1>',
    ]; 
$profile_name = $is_edit ?  $labTestProfile->get('name')->value : "";
    $form['name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Test Profile Name'),
        '#default_value' => $profile_name,
        '#required' => TRUE
    ]; 

    $laboratory_default_value = $is_edit ? $labTestProfile->get('field_profile_laboratory')->target_id : NULL;
    $form['field_profile_laboratory'] = [
			'#type' => 'select',
			'#title' => 'Laboratory',
			'#options' => $lab,
            '#default_value' => $laboratory_default_value,
			'#required' => TRUE
		];

    $aggregate_method_default_value = $is_edit ? $labTestProfile->get('field_profile_aggregate_stability_method')->target_id : NULL;
    $form['field_profile_aggregate_stability_method'] = [
			'#type' => 'select',
			'#title' => 'Aggregate Stability Method',
			'#options' => $agg_stab_method,
            '#default_value' => $aggregate_method_default_value,
			'#required' => TRUE
		];

    $respiratory_incubation_default_value = $is_edit ? $labTestProfile->get('field_profile_respiratory_incubation_days')->target_id : NULL;
    $form['field_profile_respiratory_incubation_days'] = [
			'#type' => 'select',
			'#title' => 'Respiration Incubation Days',
			'#options' => $resp_incub,
            '#default_value' => $respiratory_incubation_default_value,
			'#required' => TRUE
		];

    $respiratory_detection_default_value = $is_edit ? $labTestProfile->get('field_profile_respiration_detection_method')->target_id : NULL;
     $form['field_profile_respiration_detection_method'] = [
			'#type' => 'select',
			'#title' => 'Respiration Detection Method (unit ppm)',
	 		'#options' => $resp_detect,
            '#default_value' => $respiratory_detection_default_value,
	 		'#required' => TRUE
	 	];
 
    $electroconductivity_method_default_value =  $is_edit ?  $labTestProfile->get('electroconductivity_method')->target_id : NULL;
    $form['electroconductivity_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Electroconductivity Method (EC (Unit dS/m))'),
        '#options' => $ec_method,
        '#default_value' => $electroconductivity_method_default_value,
        '#required' => TRUE
    ]; 
 
    $nitrate_n_method_default_value =  $is_edit ?  $labTestProfile->get('nitrate_n_method')->target_id : NULL;
    $form['nitrate_n_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Nitrate-N Method (Unit ppm)'),
        '#options' => $nitrate_method,
        '#default_value' => $nitrate_n_method_default_value,
        '#required' => TRUE
    ]; 

    $phosphorus_method_default_value =  $is_edit ?  $labTestProfile->get('phosphorus_method')->target_id : NULL;
    $form['phosphorus_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Phosphorus Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#default_value' => $phosphorus_method_default_value,
        '#required' => TRUE
    ]; 

    $potassium_method_default_value =  $is_edit ?  $labTestProfile->get('potassium_method')->target_id : NULL;
    $form['potassium_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Potassium Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#default_value' => $potassium_method_default_value,
        '#required' => TRUE
    ]; 

    $calcium_method_default_value =  $is_edit ?  $labTestProfile->get('calcium_method')->target_id : NULL;
    $form['calcium_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Calcium Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#default_value' => $calcium_method_default_value,
        '#required' => TRUE
    ]; 

    $magnesium_method_default_value =  $is_edit ?  $labTestProfile->get('magnesium_method')->target_id : NULL;
    $form['magnesium_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Magnesium Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#default_value' => $magnesium_method_default_value,
        '#required' => TRUE
    ]; 

    $sulfur_method_default_value =  $is_edit ?  $labTestProfile->get('sulfur_method')->target_id : NULL;
    $form['sulfur_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Sulfur Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#default_value' => $sulfur_method_default_value,
        '#required' => TRUE
    ]; 

    $iron_method_default_value =  $is_edit ?  $labTestProfile->get('iron_method')->target_id : NULL;
    $form['iron_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Iron Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#default_value' => $iron_method_default_value,
        '#required' => TRUE
    ]; 

     $manganese_method_default_value =  $is_edit ?  $labTestProfile->get('manganese_method')->target_id : NULL;
    $form['manganese_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Manganese Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#default_value' => $manganese_method_default_value,
        '#required' => TRUE
    ]; 

    $copper_method_default_value =  $is_edit ?  $labTestProfile->get('copper_method')->target_id : NULL;
    $form['copper_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Copper Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#default_value' => $copper_method_default_value,
        '#required' => TRUE
    ]; 

    $zinc_method_default_value =  $is_edit ?  $labTestProfile->get('zinc_method')->target_id : NULL;
    $form['zinc_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Zinc Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#default_value' => $zinc_method_default_value,
        '#required' => TRUE
    ]; 

    $boron_method_default_value =  $is_edit ?  $labTestProfile->get('boron_method')->target_id : NULL;
    $form['boron_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Boron Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#default_value' => $boron_method_default_value,
        '#required' => TRUE
    ]; 

    $aluminum_method_default_value =  $is_edit ?  $labTestProfile->get('aluminum_method')->target_id : NULL;
    $form['aluminum_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Aluminum Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#default_value' => $aluminum_method_default_value,
        '#required' => TRUE
    ]; 

    $molybdenum_method_default_value =  $is_edit ?  $labTestProfile->get('molybdenum_method')->target_id : NULL;
    $form['molybdenum_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Molybdenum Method (Unit ppm)'),
        '#options' => $s_he_extract,
        '#default_value' => $molybdenum_method_default_value,
        '#required' => TRUE
    ]; 

    $form['actions']['save'] = [
        '#type' => 'submit',
        '#value' => $this->t('Save'),
    ]; 

    $form['actions']['cancel'] = [
        '#type' => 'submit',
        '#value' => $this->t('Cancel'),
        '#submit' => ['::redirectAfterCancel'],
    ];
    if($is_edit){
        $form['actions']['delete'] = [
            '#type' => 'submit',
            '#value' => $this->t('Delete'),
            '#submit' => ['::deleteLabTest'],
        ];
    }
        return $form;
    }

    public function redirectAfterCancel(array $form, FormStateInterface $form_state){
        $form_state->setRedirect('cig_pods.awardee_dashboard_form');
    }

    public function deleteLabTest(array &$form, FormStateInterface $form_state){

    // TODO: we probably want a confirm stage on the delete button. Implementations exist online
    $lab_test_id = $form_state->get('lab_test_id');
    $labTest = \Drupal::entityTypeManager()->getStorage('asset')->load($lab_test_id);

    try{
        $labTest->delete();
        $form_state->setRedirect('cig_pods.awardee_dashboard_form');
    }catch(Exception $e){
        $this
        ->messenger()
        ->addStatus($this
        ->t('Some Message about the Asset being Referenced by another @delete_error', [
        '@delete_error' => $form['awardee_org_name']['#value'],
        ]));
    }
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
        if($form_state->get('operation') === 'create'){
            $elementNames = $this->createElementNames();
            foreach($elementNames as $elemName){
                $profile_submission[$elemName] = $form_state->getValue($elemName);
            }

            $profile_submission['type'] = 'lab_testing_profile';
            $profile = Asset::create($profile_submission);
            $profile -> save();

            $route = $this->pageLookup('/create/awardee_dashboard');
            $form_state->setRedirect($route);

        }else{
            $id = $form_state->get('lab_test_id');
            $labTestProfile = \Drupal::entityTypeManager()->getStorage('asset')->load($id);

            $profile_assets = \Drupal::entityTypeManager()-> getStorage('asset')-> loadByProperties(['type' => 'lab_testing_profile']);
            $elementNames = $this->createElementNames();
		    foreach($elementNames as $elemName){
                $labTestProfile->set($elemName, $form_state->getValue($elemName));
            }
	
            $labTestProfile->save();
            $route = $this->pageLookup('/create/awardee_dashboard');
            $form_state->setRedirect($route);
        }
     }
}