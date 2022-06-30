<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;
Use Drupal\Core\Url;
// use Drupal\Core\Ajax\AjaxResponse;
// use Drupal\Core\Ajax\ReplaceCommand;

class LabTestMethodForm extends FormBase {

    public function getTaxonomyOptions($bundle){
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

    private function getAssetOptions($assetType){
        $soil_health_sample_assets = \Drupal::entityTypeManager() -> getStorage('asset') -> loadByProperties(
			['type' => $assetType]
		);
		$soil_health_sample_options = array();
		$soil_health_sample_keys = array_keys($soil_health_sample_assets);
		foreach($soil_health_sample_keys as $soil_health_sample_key) {
		  $asset = $soil_health_sample_assets[$soil_health_sample_key];
		  $soil_health_sample_options[$soil_health_sample_key] = $asset->getName();
		}

		return $soil_health_sample_options;
	}

    private function convertFractionsToDecimal($labTestMethod, $field){
        $num = $labTestMethod->get($field)[0]->getValue()["numerator"];
        $denom = $labTestMethod->get($field)[0]->getValue()["denominator"];
        return $num / $denom;
    }

    private function createElementNames(){
        return array('field_lab_method_soil_sample','field_lab_method_aggregate_stability_unit', 'field_lab_method_aggregate_stability_method', 'field_lab_method_aggregate_stability_method',
        'field_lab_method_respiration_incubation_days', 'field_lab_method_respiration_detection_method', 'field_lab_method_bulk_density_core_diameter', 'field_lab_method_bulk_density_volume',
        'field_lab_method_infiltration_method', 'field_lab_method_electroconductivity_method', 'field_lab_method_nitrate_n_method','field_lab_method_phosphorus_method','field_lab_method_potassium_method',
        'field_lab_method_calcium_method', 'field_lab_method_magnesium_method', 'field_lab_method_sulfur_method', 'field_lab_method_iron_method', 'field_lab_method_manganese_method', 'field_lab_method_copper_method',
        'field_lab_method_zinc_method', 'field_lab_method_boron_method', 'field_lab_method_aluminum_method', 'field_lab_method_molybdenum_method', 'field_lab_soil_test_laboratory', 'field_lab_method_lab_test_profile');

    }



    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state, $id = NULL){
        dpm("Changes Made 1");

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
        $s_he_extract = $this->getTaxonomyOptions("d_soil_health_extraction");
        $s_he_test_laboratory = $this->getTaxonomyOptions("d_laboratory");

        $soil_sample = $this->getAssetOptions('soil_health_sample');
        $lab_test_profile = $this->getAssetOptions('lab_testing_profile');

        $form['lab_test_title'] = [
            '#markup' => '<h1>Methods</h1>',
        ];

        // $soil_sample_default_id = $is_edit ? $labTestMethod->get('field_lab_method_soil_sample')->target_id : NULL;
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

        // $lab_default = $is_edit ? $labTestMethod->get('field_lab_soil_test_laboratory')->target_id : NULL;
        $form['field_lab_soil_test_laboratory'] = [
			'#type' => 'select',
			'#title' => 'Soil Health Test Laboratory',
			'#options' => $s_he_test_laboratory,
            '#default_value' => $lab_default,
			'#required' => TRUE,
		];

        // $lab_profile_default = $is_edit ? $labTestMethod->get('field_lab_method_lab_test_profile')->target_id : NULL;
        $form['field_lab_method_lab_test_profile'] = [
			'#type' => 'select',
			'#title' => 'Soil Health Test Methods',
			'#options' => $lab_test_profile,
            '#default_value' => $soil_sample_default_id,
			'#required' => TRUE,
		];




        $form['test_wrapper'] = [
            '#prefix' => '<div id="test_wrapper"',
			'#suffix' => '</div>',
        ];

        $form['test_wrapper']['actions']['update_profile'] = [
            '#type' => 'submit',
            '#submit' => ['::loadProfileData'],
            '#value' => $this->t('Load Selected Profile'),
            '#ajax' => [
                'callback' => '::updateProfile',
                'wrapper' => 'test_wrapper',
            ],
        ];

        // $aggregate_method_default_value = $is_edit ? $form_state->get('lab_profile')->get('field_profile_aggregate_stability_method')->target_id : NULL;


        // if($form_state->get('lab_profile') <> array()){

        $fs_lab_profile = $form_state->get('lab_profile');
        if(count($fs_lab_profile) <> 0){
            dpm($fs_lab_profile);
            $aggregate_method_default_value = $is_edit ? $labTestMethod->get('field_lab_method_aggregate_stability_method')->target_id : NULL;
            $form['test_wrapper']['field_lab_method_aggregate_stability_method'] = [
                '#type' => 'select',
                '#empty_option' => '- Select -',
                '#empty_value' => '- Select -',
                '#title' => 'Aggregate Stability Method',
                '#options' => $agg_stab_method,
                '#value' => $fs_lab_profile['field_profile_aggregate_stability_method'],
                '#default_value' => $aggregate_method_default_value,
                '#required' => FALSE,
            ];

            $respiratory_incubation_default_value = $is_edit ? $this->convertFractionsToDecimal($labTestMethod, 'field_lab_method_respiration_incubation_days') : NULL;
            $form['test_wrapper']['field_lab_method_respiration_incubation_days'] = [
                '#type' => 'number',
                '#title' => 'Respiration Incubation Days',
                '#min' => 0,
                // '#value' => $fs_lab_profile['field_profile_respiratory_incubation_days'],
                '#default_value' => $respiratory_incubation_default_value,
                '#required' => FALSE
            ];

            $respiratory_detection_default_value = $is_edit ? $labTestMethod->get('field_lab_method_respiration_detection_method')->target_id : NULL;
            $form['test_wrapper']['field_lab_method_respiration_detection_method'] = [
                '#type' => 'select',
                '#empty_option' => '- Select -',
                '#empty_value' => '- Select -',
                '#title' => 'Respiration Detection Method',
                '#options' => $resp_detect,
                '#value' => $fs_lab_profile['field_profile_respiration_detection_method'],
                '#default_value' => $respiratory_detection_default_value,
                 '#required' => FALSE
            ];

            $bulk_density_core_default =  $is_edit ?  $this->convertFractionsToDecimal($labTestMethod, 'field_lab_method_bulk_density_core_diameter') : NULL;
            $form['test_wrapper']['field_lab_method_bulk_density_core_diameter'] = [
                '#type' => 'number',
                '#title' => $this->t('Bulk Density Core Diameter (Unit Inches)'),
                '#step' => 0.01,
                '#min' => 0,
                // '#value' => $fs_lab_profile['field_profile_respiratory_incubation_days'],
                '#default_value' => $bulk_density_core_default,
                '#required' => FALSE
            ];

            $bulk_density_volume_default =  $is_edit ?  $this->convertFractionsToDecimal($labTestMethod, 'field_lab_method_bulk_density_volume') : NULL;
            $form['test_wrapper']['field_lab_method_bulk_density_volume'] = [
                '#type' => 'number',
                '#step' => 0.01,
                '#min' => 0,
                '#title' => $this->t('Bulk Density Volume (Cubic Centimeters)'),
                // '#value' => $fs_lab_profile['field_profile_respiratory_incubation_days'],
                '#default_value' => $bulk_density_volume_default,
                '#required' => FALSE
            ];

            $infiltration_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_infiltration_method')->target_id : NULL;
            $form['test_wrapper']['field_lab_method_infiltration_method'] = [
                '#type' => 'select',
                '#empty_option' => '- Select -',
                '#empty_value' => '- Select -',
                '#title' => $this->t('Infiltration Method'),
                '#options' => $infiltration_method,
                // '#value' => $fs_lab_profile['field_profile_respiratory_incubation_days'],
                '#default_value' => $infiltration_method_default_value,
                '#required' => FALSE
            ];

            $electroconductivity_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_electroconductivity_method')->target_id : NULL;
            $form['test_wrapper']['field_lab_method_electroconductivity_method'] = [
                '#type' => 'select',
                '#empty_option' => '- Select -',
                '#empty_value' => '- Select -',
                '#title' => $this->t('Electroconductivity Method'),
                '#options' => $ec_method,
                '#value' => $fs_lab_profile['electroconductivity_method'],
                '#default_value' => $electroconductivity_method_default_value,
                '#required' => FALSE
            ];

            $nitrate_n_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_nitrate_n_method')->target_id : NULL;
            $form['test_wrapper']['field_lab_method_nitrate_n_method'] = [
                '#type' => 'select',
                '#empty_option' => '- Select -',
                '#empty_value' => '- Select -',
                '#title' => $this->t('Nitrate-N Method'),
                '#options' => $nitrate_method,
                '#value' => $fs_lab_profile['nitrate_n_method'],
                '#default_value' => $nitrate_n_method_default_value,
                '#required' => FALSE
            ];

            $phosphorus_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_phosphorus_method')->target_id : NULL;
            $form['test_wrapper']['field_lab_method_phosphorus_method'] = [
                '#type' => 'select',
                '#empty_option' => '- Select -',
                '#empty_value' => '- Select -',
                '#title' => $this->t('Phosphorus Method'),
                '#options' => $s_he_extract,
                '#value' => $fs_lab_profile['phosphorus_method'],
                '#default_value' => $phosphorus_method_default_value,
                '#required' => FALSE
            ];

            $potassium_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_potassium_method')->target_id : NULL;
            $form['test_wrapper']['field_lab_method_potassium_method'] = [
                '#type' => 'select',
                '#empty_option' => '- Select -',
                '#empty_value' => '- Select -',
                '#title' => $this->t('Potassium Method'),
                '#options' => $s_he_extract,
                '#value' => $fs_lab_profile['potassium_method'],
                '#default_value' => $potassium_method_default_value,
                '#required' => FALSE
            ];

            $calcium_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_calcium_method')->target_id : NULL;
            $form['test_wrapper']['field_lab_method_calcium_method'] = [
                '#type' => 'select',
                '#empty_option' => '- Select -',
                '#empty_value' => '- Select -',
                '#title' => $this->t('Calcium Method'),
                '#options' => $s_he_extract,
                '#value' => $fs_lab_profile['calcium_method'],
                '#default_value' => $calcium_method_default_value,
                '#required' => FALSE
            ];

            $magnesium_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_magnesium_method')->target_id : NULL;
            $form['test_wrapper']['field_lab_method_magnesium_method'] = [
                '#type' => 'select',
                '#empty_option' => '- Select -',
                '#empty_value' => '- Select -',
                '#title' => $this->t('Magnesium Method'),
                '#options' => $s_he_extract,
                '#value' => $fs_lab_profile['magnesium_method'],
                '#default_value' => $magnesium_method_default_value,
                '#required' => FALSE
            ];

            $sulfur_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_sulfur_method')->target_id : NULL;
            $form['test_wrapper']['field_lab_method_sulfur_method'] = [
                '#type' => 'select',
                '#empty_option' => '- Select -',
                '#empty_value' => '- Select -',
                '#title' => $this->t('Sulfur Method'),
                '#options' => $s_he_extract,
                '#value' => $fs_lab_profile['sulfur_method'],
                '#default_value' => $sulfur_method_default_value,
                '#required' => FALSE
            ];

            $iron_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_iron_method')->target_id : NULL;
            $form['test_wrapper']['field_lab_method_iron_method'] = [
                '#type' => 'select',
                '#empty_option' => '- Select -',
                '#empty_value' => '- Select -',
                '#title' => $this->t('Iron Method'),
                '#options' => $s_he_extract,
                '#value' => $fs_lab_profile['iron_method'],
                '#default_value' => $iron_method_default_value,
                '#required' => FALSE
            ];

            $manganese_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_manganese_method')->target_id : NULL;
            $form['test_wrapper']['field_lab_method_manganese_method'] = [
                '#type' => 'select',
                '#empty_option' => '- Select -',
                '#empty_value' => '- Select -',
                '#title' => $this->t('Manganese Method'),
                '#options' => $s_he_extract,
                '#value' => $fs_lab_profile['manganese_method'],
                '#default_value' => $manganese_method_default_value,
                '#required' => FALSE
            ];

            $copper_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_copper_method')->target_id : NULL;
            $form['test_wrapper']['field_lab_method_copper_method'] = [
                '#type' => 'select',
                '#empty_option' => '- Select -',
                '#empty_value' => '- Select -',
                '#title' => $this->t('Copper Method'),
                '#options' => $s_he_extract,
                '#value' => $fs_lab_profile['copper_method'],
                '#default_value' => $copper_method_default_value,
                '#required' => FALSE
            ];

            $zinc_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_zinc_method')->target_id : NULL;
            $form['test_wrapper']['field_lab_method_zinc_method'] = [
                '#type' => 'select',
                '#empty_option' => '- Select -',
                '#empty_value' => '- Select -',
                '#title' => $this->t('Zinc Method'),
                '#options' => $s_he_extract,
                '#value' => $fs_lab_profile['zinc_method'],
                '#default_value' => $zinc_method_default_value,
                '#required' => FALSE
            ];

            $boron_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_boron_method')->target_id : NULL;
            $form['test_wrapper']['field_lab_method_boron_method'] = [
                '#type' => 'select',
                '#empty_option' => '- Select -',
                '#empty_value' => '- Select -',
                '#title' => $this->t('Boron Method'),
                '#options' => $s_he_extract,
                '#value' => $fs_lab_profile['boron_method'],
                '#default_value' => $boron_method_default_value,
                '#required' => FALSE
            ];

            $aluminum_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_aluminum_method')->target_id : NULL;
            $form['test_wrapper']['field_lab_method_aluminum_method'] = [
                '#type' => 'select',
                '#empty_option' => '- Select -',
                '#empty_value' => '- Select -',
                '#title' => $this->t('Aluminum Method'),
                '#options' => $s_he_extract,
                '#value' => $fs_lab_profile['aluminum_method'],
                '#default_value' => $aluminum_method_default_value,
                '#required' => FALSE
            ];

            $molybdenum_method_default_value =  $is_edit ?  $labTestMethod->get('field_lab_method_molybdenum_method')->target_id : '- Select -';
            $form['test_wrapper']['field_lab_method_molybdenum_method'] = [
                '#type' => 'select',
                '#empty_option' => '- Select -',
                '#empty_value' => '- Select -',
                '#title' => $this->t('Molybdenum Method'),
                '#options' => $s_he_extract,
                '#value' => $fs_lab_profile['molybdenum_method'],
                '#default_value' => $molybdenum_method_default_value,
                '#required' => FALSE
            ];

            } else {
                dpm('Lab Profile is an empty array, not rendering the metods section');
                dpm($fs_lab_profile);
        }

            // $aggregate_unit_default_value = $is_edit ? $form_state->get('lab_profile')->get('field_profile_aggregate_stability_unit')->target_id : NULL;
            // $form['test-wrapper']['field_lab_method_aggregate_stability_unit'] = [
            //     '#type' => 'select',
            //     '#title' => 'Aggregate Stability Unit',
            //     '#options' => $agg_stab_unit,
            //     '#default_value' => $aggregate_unit_default_value,
            //     '#required' => TRUE
            // ];


            $form['actions']['save'] = [
                '#type' => 'submit',
                '#submit' => ['::testFormStateSubmit'],
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

    public function loadProfileData(array &$form, FormStateInterface $form_state){

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

        return $form['test_wrapper'];
    }

    public function redirectAfterCancel(array $form, FormStateInterface $form_state){
        $form_state->setRedirect('cig_pods.awardee_dashboard_form');
    }

    public function deleteLabTest(array &$form, FormStateInterface $form_state){

        // TODO: we probably want a confirm stage on the delete button. Implementations exist online
        $lab_test_id = $form_state->get('lab_test_id');
        $labTest = \Drupal::entityTypeManager()->getStorage('asset')->load($lab_test_id);

        $labTest->delete();
        $form_state->setRedirect('cig_pods.awardee_dashboard_form');
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

    public function testFormStateSubmit(array &$form, FormStateInterface $form_state){
        // $test = $form_state->get('test_wrapper')['field_lab_method_molybdenum_method']->value;
        dpm($form_state->getValue('test_wrapper'));
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $method_submission = [];
        if($form_state->get('operation') === 'create'){
            $elementNames = $this->createElementNames();
            foreach($elementNames as $elemName){
                $method_submission[$elemName] = $form_state->getValue($elemName);
            }
            $method_submission['name'] = 'Methods';

            $method_submission['type'] = 'lab_testing_method';
            $method = Asset::create($method_submission);
            $method -> save();

            $form_state->setRedirect('cig_pods.awardee_dashboard_form');

        }else{
            $id = $form_state->get('lab_test_id');
            $labTestMethod = \Drupal::entityTypeManager()->getStorage('asset')->load($id);

            $method_assets = \Drupal::entityTypeManager()-> getStorage('asset')-> loadByProperties(['type' => 'lab_testing_method']);
            $elementNames = $this->createElementNames();
		    foreach($elementNames as $elemName){
                $labTestMethod->set($elemName, $form_state->getValue($elemName));
            }
            $labTestMethod->set('name', 'Methods');

            $labTestMethod->save();
            $form_state->setRedirect('cig_pods.awardee_dashboard_form');
        }
     }
}