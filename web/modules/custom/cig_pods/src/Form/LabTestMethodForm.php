<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;
Use Drupal\Core\Url;
// use Drupal\Core\Ajax\AjaxResponse;
// use Drupal\Core\Ajax\ReplaceCommand;

class LabTestMethodForm extends PodsFormBase {

    public function getTaxonomyOptions($bundle){
      $options = $this->entityOptions('taxonomy_term', $bundle);
      foreach ($options as $key => $option) {
        $options[$key] = html_entity_decode($option);
      }
		  return ['' => '- Select -'] + $options;
    }

    private function getAssetOptions($assetType){
      $options = $this->entityOptions('asset', $assetType);
		  return ['' => '- Select -'] + $options;
	  }

    private function convertFractionsToDecimal($labTestMethod, $field){
        $num = $labTestMethod->get($field)[0]->getValue()["numerator"];
        $denom = $labTestMethod->get($field)[0]->getValue()["denominator"];
        return $num / $denom;
    }
    private function createElementNames(){
        return array('field_lab_method_soil_sample', 'field_lab_soil_test_laboratory', 'field_lab_method_lab_test_profile');

    }

    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state, $id = NULL){

        $labTestMethod  = [];

        $labTestProfile = NULL;

        $is_edit = $id <> NULL;

        if($form_state->get('lab_profile') == NULL){

            $form_state->set('lab_profile', array ());
        }

        if($is_edit){
            $form_state->set('operation','edit');
            $form_state->set('lab_test_id',$id);
            $labTestMethod = \Drupal::entityTypeManager()->getStorage('asset')->load($id);
            $form_state->set('loading', NULL);



        } else {
            $form_state->set('operation','create');
        }


        $form['#attached']['library'][] = 'cig_pods/lab_test_method_admin_form';
        $form['#tree'] = TRUE; // Allows getting at the values hierarchy in form state


        $agg_stab_unit = $this->getTaxonomyOptions("d_aggregate_stability_un");
        $agg_stab_method = $this->getTaxonomyOptions("d_aggregate_stability_me");
        $infiltration_method = $this->getTaxonomyOptions("d_infiltration_method");
        $ec_method = $this->getTaxonomyOptions("d_ec_method");
        $nitrate_method = $this->getTaxonomyOptions("d_nitrate_n_method");
        $ph_method = $this->getTaxonomyOptions("d_ph_method");
        $resp_detect = $this->getTaxonomyOptions("d_respiration_detection_");
        $respiration_incubation = $this->getTaxonomyOptions("d_respiration_incubation");
        $s_he_extract = $this->getTaxonomyOptions("d_soil_health_extraction");
        $s_he_test_laboratory = $this->getTaxonomyOptions("d_laboratory");

        $soil_sample = $this->getAssetOptions('soil_health_sample');
        $lab_test_profile = $this->getAssetOptions('lab_testing_profile');

        $form['lab_test_title'] = [
            '#markup' => '<h1>Methods</h1>',
        ];

        $soil_sample_default_id = $is_edit ? $labTestMethod->get('field_lab_method_soil_sample')->target_id : NULL;
        $form['field_lab_method_soil_sample'] = [
			'#type' => 'select',
			'#title' => 'Soil Sample ID',
			'#options' => $soil_sample,
            '#default_value' => $soil_sample_default_id,
			'#required' => TRUE
		];

        $form['lab_form_header'] = [
			'#markup' => '<div class="lab-form-header"><h2>Soil Health Test Method Set</h2><h4>23 Fields | Section 1 of 1</h4></div>'
		];

        $lab_default = $is_edit ? $labTestMethod->get('field_lab_soil_test_laboratory')->target_id : NULL;
        $form['field_lab_soil_test_laboratory'] = [
			'#type' => 'select',
			'#title' => 'Soil Health Test Laboratory',
			'#options' => $s_he_test_laboratory,
            '#default_value' => $lab_default,
			'#required' => TRUE,
		];

        $lab_profile_default = $is_edit ? $labTestMethod->get('field_lab_method_lab_test_profile')->target_id : NULL;
        $form['field_lab_method_lab_test_profile'] = [
			'#type' => 'select',
			'#title' => 'Soil Health Test Methods',
			'#options' => $lab_test_profile,
            '#default_value' => $lab_profile_default,
			'#required' => TRUE,
		];

        $form['autoload_container'] = [
            '#prefix' => '<div id="autoload_container"',
			'#suffix' => '</div>',
        ];

        if(!$is_edit){
            $form['actions']['update_profile'] = [
                '#type' => 'submit',
                '#submit' => ['::loadProfileData'],
                '#limit_validation_errors' => '',
                '#value' => $this->t('Load Selected Profile'),
                '#ajax' => [
                    'callback' => '::updateProfile',
                    'wrapper' => 'autoload_container',
                ],
            '#prefix' => '<div id="autoload_button"',
			'#suffix' => '</div>',
            ];
        }


        $fs_lab_profile = $form_state->get('lab_profile');
        if(count($fs_lab_profile) <> 0 || $is_edit){

            if($form_state->get('loading') <> NULL){
                $molybdenum_method_default_value = $fs_lab_profile['molybdenum_method'];
                $aggregate_method_default_value = $fs_lab_profile['field_profile_aggregate_stability_method'];
                $respiratory_incubation_default_value = $fs_lab_profile['field_profile_respiratory_incubation_days'];
                $respiratory_detection_default_value = $fs_lab_profile['field_profile_respiration_detection_method'];
                $electroconductivity_method_default_value =  $fs_lab_profile['electroconductivity_method'];
                $nitrate_n_method_default_value =  $fs_lab_profile['nitrate_n_method'];
                $phosphorus_method_default_value =  $fs_lab_profile['phosphorus_method'];
                $potassium_method_default_value =  $fs_lab_profile['potassium_method'];
                $calcium_method_default_value =  $fs_lab_profile['calcium_method'];
                $magnesium_method_default_value = $fs_lab_profile['magnesium_method'];
                $sulfur_method_default_value = $fs_lab_profile['sulfur_method'];
                $iron_method_default_value = $fs_lab_profile['iron_method'];
                $manganese_method_default_value =  $fs_lab_profile['manganese_method'];
                $copper_method_default_value = $fs_lab_profile['copper_method'];
                $zinc_method_default_value = $fs_lab_profile['zinc_method'];
                $boron_method_default_value = $fs_lab_profile['boron_method'];
                $aluminum_method_default_value = $fs_lab_profile['aluminum_method'];
                $aggregate_unit_default_value = $fs_lab_profile['field_profile_aggregate_stability_unit'];
                $infiltration_method_default_value = $fs_lab_profile['field_lab_profile_infiltration_method'];

            }else {
                $infiltration_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_infiltration_method')->target_id : NULL;
                $molybdenum_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_molybdenum_method')->target_id : NULL;
                $aggregate_method_default_value = $is_edit ? $labTestMethod->get('field_lab_method_aggregate_stability_method')->target_id : NULL;
                $aggregate_unit_default_value = $is_edit ? $labTestMethod->get('field_lab_method_aggregate_stability_unit')->target_id : NULL;
                $respiratory_incubation_default_value = $is_edit ? $this->convertFractionsToDecimal($labTestMethod, 'field_lab_method_respiration_incubation_days') : NULL;
                $respiratory_detection_default_value = $is_edit ? $labTestMethod->get('field_lab_method_respiration_detection_method')->target_id : NULL;
                $electroconductivity_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_electroconductivity_method')->target_id : NULL;
                $nitrate_n_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_nitrate_n_method')->target_id : NULL;
                $phosphorus_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_phosphorus_method')->target_id : NULL;
                $potassium_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_potassium_method')->target_id : NULL;
                $calcium_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_calcium_method')->target_id : NULL;
                $magnesium_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_magnesium_method')->target_id : NULL;
                $sulfur_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_sulfur_method')->target_id : NULL;
                $iron_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_iron_method')->target_id : NULL;
                $manganese_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_manganese_method')->target_id : NULL;
                $copper_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_copper_method')->target_id : NULL;
                $zinc_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_zinc_method')->target_id : NULL;
                $boron_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_boron_method')->target_id : NULL;
                $aluminum_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_aluminum_method')->target_id : NULL;

            }

            $form['autoload_container']['field_lab_method_aggregate_stability_method'] = [
                '#type' => 'select',
                '#title' => 'Aggregate Stability Method',
                '#options' => $agg_stab_method,
                '#default_value' => $aggregate_method_default_value,
                '#required' => TRUE,
            ];

            $form['autoload_container']['field_lab_method_aggregate_stability_unit'] = [
                '#type' => 'select',
                '#title' => 'Aggregate Stability Unit',
                '#options' => $agg_stab_unit,
                '#default_value' => $aggregate_unit_default_value,
                '#required' => TRUE
            ];

           $form['autoload_container']['field_lab_method_respiration_incubation_days'] = [
                '#type' => 'select',
                '#options' => $respiration_incubation,
                '#title' => 'Respiration Incubation Days',
                '#min' => 0,
                '#default_value' => $respiratory_incubation_default_value,
                '#required' => TRUE,
            ];

            $form['autoload_container']['field_lab_method_respiration_detection_method'] = [
                '#type' => 'select',
                '#title' => 'Respiration Detection Method',
                '#options' => $resp_detect,
                '#default_value' => $respiratory_detection_default_value,
                '#required' => TRUE
            ];

            $bulk_density_core_default =  $is_edit ?  $this->convertFractionsToDecimal($labTestMethod, 'field_lab_method_bulk_density_core_diameter') : NULL;
            $form['autoload_container']['field_lab_method_bulk_density_core_diameter'] = [
                '#type' => 'number',
                '#title' => $this->t('Bulk Density Core Diameter (Unit Inches)'),
                '#step' => 0.01,
                '#min' => 0,
                '#default_value' =>  $bulk_density_core_default,
                '#required' => TRUE
            ];

            $bulk_density_volume_default =  $is_edit ?  $this->convertFractionsToDecimal($labTestMethod, 'field_lab_method_bulk_density_volume') : NULL;
            $form['autoload_container']['field_lab_method_bulk_density_volume'] = [
                '#type' => 'number',
                '#step' => 0.01,
                '#min' => 0,
                '#title' => $this->t('Bulk Density Volume (Cubic Centimeters)'),
                '#default_value' => $bulk_density_volume_default,
                '#required' => TRUE
            ];

            $form['autoload_container']['field_lab_method_infiltration_method'] = [
                '#type' => 'select',
                '#title' => $this->t('Infiltration Method'),
                '#options' => $infiltration_method,
                '#default_value' => $infiltration_method_default_value,
                '#required' => TRUE
            ];


            $form['autoload_container']['field_lab_method_electroconductivity_method'] = [
                '#type' => 'select',
                '#title' => $this->t('Electroconductivity Method'),
                '#options' => $ec_method,
                '#default_value' => $electroconductivity_method_default_value,
                '#required' => TRUE
            ];

            $form['autoload_container']['field_lab_method_nitrate_n_method'] = [
                '#type' => 'select',
                '#title' => $this->t('Nitrate-N Method'),
                '#options' => $nitrate_method,
                '#default_value' => $nitrate_n_method_default_value,
                '#required' => TRUE
            ];

            $form['autoload_container']['field_lab_method_phosphorus_method'] = [
                '#type' => 'select',
                '#title' => $this->t('Phosphorus Method'),
                '#options' => $s_he_extract,
                '#default_value' => $phosphorus_method_default_value,
                '#required' => TRUE
            ];

            $form['autoload_container']['field_lab_method_potassium_method'] = [
                '#type' => 'select',
                '#title' => $this->t('Potassium Method'),
                '#options' => $s_he_extract,
                '#default_value' => $potassium_method_default_value,
                '#required' => TRUE
            ];

            $form['autoload_container']['field_lab_method_calcium_method'] = [
                '#type' => 'select',
                '#title' => $this->t('Calcium Method'),
                '#options' => $s_he_extract,
                '#default_value' => $calcium_method_default_value,
                '#required' => TRUE
            ];


            $form['autoload_container']['field_lab_method_magnesium_method'] = [
                '#type' => 'select',
                '#title' => $this->t('Magnesium Method'),
                '#options' => $s_he_extract,
                '#default_value' => $magnesium_method_default_value,
                '#required' => TRUE
            ];


            $form['autoload_container']['field_lab_method_sulfur_method'] = [
                '#type' => 'select',
                '#title' => $this->t('Sulfur Method'),
                '#options' => $s_he_extract,
                '#default_value' => $sulfur_method_default_value,
                '#required' => TRUE
            ];


            $form['autoload_container']['field_lab_method_iron_method'] = [
                '#type' => 'select',
                '#title' => $this->t('Iron Method'),
                '#options' => $s_he_extract,
                '#default_value' => $iron_method_default_value,
                '#required' => TRUE
            ];


            $form['autoload_container']['field_lab_method_manganese_method'] = [
                '#type' => 'select',
                '#title' => $this->t('Manganese Method'),
                '#options' => $s_he_extract,
                '#default_value' => $manganese_method_default_value,
                '#required' => TRUE
            ];


            $form['autoload_container']['field_lab_method_copper_method'] = [
                '#type' => 'select',
                '#title' => $this->t('Copper Method'),
                '#options' => $s_he_extract,
                '#default_value' =>$copper_method_default_value,
                '#required' => TRUE
            ];


            $form['autoload_container']['field_lab_method_zinc_method'] = [
                '#type' => 'select',
                '#title' => $this->t('Zinc Method'),
                '#options' => $s_he_extract,
                '#default_value' => $zinc_method_default_value,
                '#required' => TRUE
            ];


            $form['autoload_container']['field_lab_method_boron_method'] = [
                '#type' => 'select',
                '#title' => $this->t('Boron Method'),
                '#options' => $s_he_extract,
                '#default_value' => $boron_method_default_value,
                '#required' => TRUE
            ];


            $form['autoload_container']['field_lab_method_aluminum_method'] = [
                '#type' => 'select',
                '#title' => $this->t('Aluminum Method'),
                '#options' => $s_he_extract,
                '#default_value' => $aluminum_method_default_value,
                '#required' => TRUE
            ];

            $form['autoload_container']['field_lab_method_molybdenum_method'] = [
                '#type' => 'select',
                '#title' => $this->t('Molybdenum Method'),
                '#options' => $s_he_extract,
                '#default_value' => $molybdenum_method_default_value,
                '#required' => TRUE,
            ];

        }


            $form['actions']['save'] = [
                '#type' => 'submit',
                '#value' => $this->t('Save'),
            ];

            $form['actions']['cancel'] = [
                '#type' => 'submit',
                '#limit_validation_errors' => '',
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

    public function loadProfileData(array &$form, FormStateInterface $form_state){

        $form_state->set('loading', 1);

        $lab_profile_db = \Drupal::entityTypeManager()->getStorage('asset')->load($form['field_lab_method_lab_test_profile']['#value'])->toArray();

        $lab_profile = []; // Array to be populated into form state

        $ignored_fields = ['id','is_fixed','uuid','revision_id','langcode','type','revision_created','revision_user','revision_log_message','uid','name','status', 'created','changed','archived','default_langcode','revision_default','revision_translation_affected','data','file','image','notes','parent','flag','id_tag','location','geometry','intrinsic_geometry','is_location','is_fixed'];
        foreach($lab_profile_db as $key => $value){
            // skip ignored fields
            if(in_array($key, $ignored_fields)) continue;

            if(count($value) <> 0){
                $lab_profile[$key] = $value[0]['target_id'];
            }

        }

        $form_state->set('lab_profile', $lab_profile);

        $form_state->setRebuild(TRUE);
    }

    public function updateProfile(array &$form, FormStateInterface $form_state){

        return $form['autoload_container'];
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
        }catch(\Exception $e){
            $this
          ->messenger()
          ->addError($this
          ->t($e->getMessage()));
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
        return 'lab_test_methods_admin';
    }

    /**
    * {@inheritdoc}
    */
    private function saveProfileFields(array &$method_submission, FormStateInterface $form_state){
        $method_submission['field_lab_method_molybdenum_method'] = $form_state->getValue('autoload_container')['field_lab_method_molybdenum_method'];
        $method_submission['field_lab_method_aggregate_stability_unit'] = $form_state->getValue('autoload_container')['field_lab_method_aggregate_stability_unit'];
        $method_submission['field_lab_method_aggregate_stability_method'] = $form_state->getValue('autoload_container')['field_lab_method_aggregate_stability_method'];
        $method_submission['field_lab_method_respiration_incubation_days'] = $form_state->getValue('autoload_container')['field_lab_method_respiration_incubation_days'];
        $method_submission['field_lab_method_respiration_detection_method'] = $form_state->getValue('autoload_container')['field_lab_method_respiration_detection_method'];
        $method_submission['field_lab_method_bulk_density_core_diameter'] = $form_state->getValue('autoload_container')['field_lab_method_bulk_density_core_diameter'];
        $method_submission['field_lab_method_bulk_density_volume'] = $form_state->getValue('autoload_container')['field_lab_method_bulk_density_volume'];
        $method_submission['field_lab_method_electroconductivity_method'] = $form_state->getValue('autoload_container')['field_lab_method_electroconductivity_method'];
        $method_submission['field_lab_method_phosphorus_method'] = $form_state->getValue('autoload_container')['field_lab_method_phosphorus_method'];
        $method_submission['field_lab_method_potassium_method'] = $form_state->getValue('autoload_container')['field_lab_method_potassium_method'];
        $method_submission['field_lab_method_calcium_method'] = $form_state->getValue('autoload_container')['field_lab_method_calcium_method'];
        $method_submission['field_lab_method_magnesium_method'] = $form_state->getValue('autoload_container')['field_lab_method_magnesium_method'];
        $method_submission['field_lab_method_sulfur_method'] = $form_state->getValue('autoload_container')['field_lab_method_sulfur_method'];
        $method_submission['field_lab_method_iron_method'] = $form_state->getValue('autoload_container')['field_lab_method_iron_method'];
        $method_submission['field_lab_method_manganese_method'] = $form_state->getValue('autoload_container')['field_lab_method_manganese_method'];
        $method_submission['field_lab_method_copper_method'] = $form_state->getValue('autoload_container')['field_lab_method_copper_method'];
        $method_submission['field_lab_method_zinc_method'] = $form_state->getValue('autoload_container')['field_lab_method_zinc_method'];
        $method_submission['field_lab_method_boron_method'] = $form_state->getValue('autoload_container')['field_lab_method_boron_method'];
        $method_submission['field_lab_method_aluminum_method'] = $form_state->getValue('autoload_container')['field_lab_method_aluminum_method'];
        $method_submission['field_lab_method_infiltration_method'] = $form_state->getValue('autoload_container')['field_lab_method_infiltration_method'];
        $method_submission['field_lab_method_nitrate_n_method'] = $form_state->getValue('autoload_container')['field_lab_method_nitrate_n_method'];
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $method_submission = [];
        if($form_state->get('operation') === 'create'){
            $elementNames = $this->createElementNames();
            foreach($elementNames as $elemName){
                $method_submission[$elemName] = $form_state->getValue($elemName);
            }

            $this->saveProfileFields($method_submission, $form_state);

            $method_submission['name'] = 'Methods';

            $method_submission['type'] = 'lab_testing_method';
            $method = Asset::create($method_submission);
            $method -> save();

            $this->setProjectReference($method, $method->get('field_lab_method_soil_sample')->target_id);

            $form_state->setRedirect('cig_pods.awardee_dashboard_form');

        }else{
            $elementsToUpdate = [];
            $id = $form_state->get('lab_test_id');
            $labTestMethod = \Drupal::entityTypeManager()->getStorage('asset')->load($id);

            $method_assets = \Drupal::entityTypeManager()-> getStorage('asset')-> loadByProperties(['type' => 'lab_testing_method']);
            $elementNames = $this->createElementNames();
		    foreach($elementNames as $elemName){
                $labTestMethod->set($elemName, $form_state->getValue($elemName));
            }

            $this->saveProfileFields($elementsToUpdate, $form_state);

            foreach($elementsToUpdate as $key => $value){
                $labTestMethod->set($key, $value);
            }

            $labTestMethod->set('name', 'Methods');

            $labTestMethod->save();

            $this->setProjectReference($labTestMethod, $labTestMethod->get('field_lab_method_soil_sample')->target_id);

            $form_state->setRedirect('cig_pods.awardee_dashboard_form');
        }
     }

     public function setProjectReference($assetReference, $sampleReference){
		$soilSample = \Drupal::entityTypeManager()->getStorage('asset')->load($sampleReference);
		$project = \Drupal::entityTypeManager()->getStorage('asset')->load($soilSample->get('project')->target_id);
		$assetReference->set('project', $project);
		$assetReference->save();
	}
}