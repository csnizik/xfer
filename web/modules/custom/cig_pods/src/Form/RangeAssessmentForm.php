<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;
Use Drupal\Core\Url;


class RangeAssessmentForm extends FormBase {

    public function getSHMUOptions(){
		$producer_assets = \Drupal::entityTypeManager() -> getStorage('asset') -> loadByProperties(
			['type' => 'soil_health_management_unit']
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

    /**
   * {@inheritdoc}
   */
    public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
		$form['#attached']['library'][] = 'cig_pods/range_assessment_form';
        $form['#attached']['library'][] = 'cig_pods/css_form';

		$severity_options = ['Extreme to Total', 'Moderate to Extreme', 'Moderate', 'Slight to Moderate', 'None to Slight'];

		$is_edit = $id <> NULL;

		if($is_edit){
			$form_state->set('operation', 'edit');
			// $form_state->set('calculate_rcs',True);
			$form_state->set('assessment_id', $id);
			$assessment = \Drupal::entityTypeManager()->getStorage('asset')->load($id);
			
		} else {
			$form_state->set('operation', 'create');
		}
		
		if($form_state->get('calculate_rcs') == NULL ) {
			$form_state->set('calculate_rcs', False);
		}
        
        $form['producer_title'] = [
			'#markup' => '<h1> <b> Assessments </b> </h1>',
		];
		// TOOD: Attach appropriate CSS for this to display correctly
		$form['subform_1'] = [
			'#markup' => '<div class="subform-title-container"><h2>Rangeland In-Field Assessment </h2><h4>18 Fields | Section 1 of 1</h4></div>'
		];

        $range_assessment_shmu_value = $is_edit ? $assessment->get('range_assessment_shmu')->target_id : ''; 
		$form['range_assessment_shmu'] = [
			'#type' => 'select',
			'#title' => 'Select a Soil Health Management Unit (SHMU)',
			'#options' => $this->getSHMUOptions(), 
			'#default_value' => $range_assessment_shmu_value,
			'#required' => FALSE,
		];

        $range_assessment_rills_value = $is_edit ? $assessment->get('range_assessment_rills')->target_id : ''; 

		$form['range_assessment_rills'] = [
			'#type' => 'select',
			'#title' => $this->t('Rills'),
			'#empty_option' => '- Select -',
			'#empty_value' => '- Select -',
			'#options' => $severity_options,
			'#default_value' => $field_assessment_rills_value,
			'#required' => FALSE
		];

		$range_assessment_water_flow_value = $is_edit ? $assessment->get('range_assessment_water_flow')->target_id : ''; 

		$form['range_assessment_water_flow'] = [
			'#type' => 'select',
			'#title' => $this->t('Water Flow'),
			'#empty_option' => '- Select -',
			'#empty_value' => '- Select -',
			'#options' => $severity_options,
			'#default_value' => $field_assessment_water_flow_value,
			'#required' => FALSE
		];

		$range_assessment_pedestals_value = $is_edit ? $assessment->get('range_assessment_pedestals')->target_id : ''; 

		$form['range_assessment_pedestals'] = [
			'#type' => 'select',
			'#title' => $this->t('Pedestals and/or Terracettes'),
			'#empty_option' => '- Select -',
			'#empty_value' => '- Select -',
			'#options' => $severity_options,
			'#default_value' => $field_assessment_Pedestals_value,
			'#required' => FALSE
		];

		$range_assessment_bare_ground_value = $is_edit ? $assessment->get('range_assessment_bare_ground')->target_id : ''; 

		$form['range_assessment_bare_ground'] = [
			'#type' => 'select',
			'#title' => $this->t('Bare Ground'),
			'#empty_option' => '- Select -',
			'#empty_value' => '- Select -',
			'#options' => $severity_options,
			'#default_value' => $field_assessment_bare_ground_value,
			'#required' => FALSE
		];

		$range_assessment_gullies_value = $is_edit ? $assessment->get('range_assessment_gullies')->target_id : ''; 

		$form['range_assessment_gullies'] = [
			'#type' => 'select',
			'#title' => $this->t('Gullies'),
			'#empty_option' => '- Select -',
			'#empty_value' => '- Select -',
			'#options' => $severity_options,
			'#default_value' => $field_assessment_gullies_value,
			'#required' => FALSE
		];

		$range_assessment_wind_scoured_value = $is_edit ? $assessment->get('range_assessment_wind_scoured')->target_id : ''; 

		$form['range_assessment_wind_scoured'] = [
			'#type' => 'select',
			'#title' => $this->t('Wind-Scoured and/or Depositional Areas'),
			'#empty_option' => '- Select -',
			'#empty_value' => '- Select -',
			'#options' => $severity_options,
			'#default_value' => $field_assessment_wind_scoured_value,
			'#required' => FALSE
		];

		$range_assessment_litter_movement_value = $is_edit ? $assessment->get('range_assessment_litter_movement')->target_id : ''; 

		$form['range_assessment_litter_movement'] = [
			'#type' => 'select',
			'#title' => $this->t('Litter Movement (Wind or Water)'),
			'#empty_option' => '- Select -',
			'#empty_value' => '- Select -',
			'#options' => $severity_options,
			'#default_value' => $field_assessment_litter_movement_value,
			'#required' => FALSE
		];

		$range_assessment_soil_surface_resistance_value = $is_edit ? $assessment->get('range_assessment_soil_surface_resistance')->target_id : ''; 

		$form['range_assessment_soil_surface_resistance'] = [
			'#type' => 'select',
			'#title' => $this->t('Soil Surface Resistance to Erosion'),
			'#empty_option' => '- Select -',
			'#empty_value' => '- Select -',
			'#options' => $severity_options,
			'#default_value' => $field_assessment_soil_surface_resistance_value,
			'#required' => FALSE
		];

		$range_assessment_soil_surface_loss_value = $is_edit ? $assessment->get('range_assessment_soil_surface_loss')->target_id : ''; 

		$form['range_assessment_soil_surface_loss'] = [
			'#type' => 'select',
			'#title' => $this->t('Soil Surface Loss and Degradation'),
			'#empty_option' => '- Select -',
			'#empty_value' => '- Select -',
			'#options' => $severity_options,
			'#default_value' => $field_assessment_soil_surface_loss_value,
			'#required' => FALSE
		];

		$range_assessment_effects_of_plants_value = $is_edit ? $assessment->get('range_assessment_effects_of_plants')->target_id : ''; 

		$form['range_assessment_effects_of_plants'] = [
			'#type' => 'select',
			'#title' => $this->t('Effects of Plant Community Composition and Distribution on Infiltration'),
			'#empty_option' => '- Select -',
			'#empty_value' => '- Select -',
			'#options' => $severity_options,
			'#default_value' => $field_assessment_effects_of_plants_value,
			'#required' => FALSE
		];

		$range_assessment_compaction_layer_value = $is_edit ? $assessment->get('range_assessment_compaction_layer')->target_id : ''; 

		$form['range_assessment_compaction_layer'] = [
			'#type' => 'select',
			'#title' => $this->t('Compaction Layer'),
			'#empty_option' => '- Select -',
			'#empty_value' => '- Select -',
			'#options' => $severity_options,
			'#default_value' => $field_assessment_compaction_layer_value,
			'#required' => FALSE
		];

		$range_assessment_functional_structural_value = $is_edit ? $assessment->get('range_assessment_functional_structural')->target_id : ''; 

		$form['range_assessment_functional_structural'] = [
			'#type' => 'select',
			'#title' => $this->t('Functional/Structural Groups'),
			'#empty_option' => '- Select -',
			'#empty_value' => '- Select -',
			'#options' => $severity_options,
			'#default_value' => $field_assessment_functional_structural_value,
			'#required' => FALSE
		];

		$range_assessment_dead_plants_value = $is_edit ? $assessment->get('range_assessment_dead_plants')->target_id : ''; 

		$form['range_assessment_dead_plants'] = [
			'#type' => 'select',
			'#title' => $this->t('Dead or Dying Plants or Plant Parts (dominant, subdominant, and minor functional/structural groups)'),
			'#empty_option' => '- Select -',
			'#empty_value' => '- Select -',
			'#options' => $severity_options,
			'#default_value' => $field_assessment_dead_plants_value,
			'#required' => FALSE
		];

		$range_assessment_litter_cover_value = $is_edit ? $assessment->get('range_assessment_litter_cover')->target_id : ''; 

		$form['range_assessment_litter_cover'] = [
			'#type' => 'select',
			'#title' => $this->t('Litter Cover and Depth'),
			'#empty_option' => '- Select -',
			'#empty_value' => '- Select -',
			'#options' => $severity_options,
			'#default_value' => $field_assessment_litter_cover_value,
			'#required' => FALSE
		];

		$range_assessment_annual_production_value = $is_edit ? $assessment->get('range_assessment_annual_production')->target_id : ''; 

		$form['range_assessment_annual_production'] = [
			'#type' => 'select',
			'#title' => $this->t('Annual Production'),
			'#empty_option' => '- Select -',
			'#empty_value' => '- Select -',
			'#options' => $severity_options,
			'#default_value' => $field_assessment_annual_production_value,
			'#required' => FALSE
		];

		$range_assessment_invasive_plants_value = $is_edit ? $assessment->get('range_assessment_invasive_plants')->target_id : ''; 

		$form['range_assessment_invasive_plants'] = [
			'#type' => 'select',
			'#title' => $this->t('Invasive Plants Vigor with an Emphasis on Reproductive Capability of Perennial Plants (dominant, subdominant, and minor functional/structural groups)'),
			'#empty_option' => '- Select -',
			'#empty_value' => '- Select -',
			'#options' => $severity_options,
			'#default_value' => $field_assessment_invasive_plants_value,
			'#required' => FALSE
		];

		// $form['actions']['identify-resource-concerns'] = [
		// 	'#type' => 'submit',
		// 	'#submit' => ['::calcuateResourceConcerns'],
		// 	'#ajax' => [
		// 		'callback' => '::calcuateResourceConcernsCallback',
		// 	],
		// 	'#value' => $this->t('Identify Resource Concerns'),
		// ];

		// add resource concern

		$form['actions']['save'] = [
			'#type' => 'submit',
			'#value' => 'Save'
		];
		$form['actions']['cancel'] = [
			'#type' => 'submit',
			'#value' => $this->t('Cancel'),
			'#submit' => ['::dashboardRedirect'],
			'#limit_validation_errors' => '',

		];

		$form['actions']['test'] = [
			'#type' => 'submit',
			'#value' => $this->t('test'),
			'#submit' => ['::getBioticIntegrity'],
			'#limit_validation_errors' => '',

		];

        if($is_edit){
            $form['actions']['delete'] = [
                '#type' => 'submit',
                '#value' => $this->t('Delete'),
                '#submit' => ['::deleteFieldAssessment'],
            ];
        }

        return $form;
    }

    /**
   * {@inheritdoc}
   */
    public function validateForm(array &$form, FormStateInterface $form_state){
        return;
    }


    public function dashboardRedirect(array &$form, FormStateInterface $form_state){
        $form_state->setRedirect('cig_pods.awardee_dashboard_form');
    }

	public function deleteFieldAssessment(array &$form, FormStateInterface $form_state){

		$assessment_id = $form_state->get('assessment_id');
		$labTest = \Drupal::entityTypeManager()->getStorage('asset')->load($assessment_id);
		$labTest->delete();
		$form_state->setRedirect('cig_pods.awardee_dashboard_form');
	}

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {


    }

	public function convertSeverities(string $severity) {
		$key['Extreme to Total'] = 5;
		$key['Moderate to Extreme'] = 4;
		$key['Moderate'] = 3;
		$key['Slight to Moderate'] = 2;
		$key['None to Slight'] = 1;
		$key[''] = 0;
		
		return $key[$severity];
	}

	public function getSoilSiteStability(array &$form, FormStateInterface $form_state) {
		$rills = $this->convertSeverities($form_state->getValue('range_assessment_rills'));
		$water_flow = $this->convertSeverities($form_state->getValue('range_assessment_water_flow'));
		$pedestals = $this->convertSeverities($form_state->getValue('range_assessment_pedestals'));
		$bare_ground = $this->convertSeverities($form_state->getValue('range_assessment_bare_ground'));
		$gullies = $this->convertSeverities($form_state->getValue('range_assessment_gullies'));
		$wind_scoured = $this->convertSeverities($form_state->getValue('range_assessment_wind_scoured'));
		$litter_movement = $this->convertSeverities($form_state->getValue('range_assessment_litter_movement'));
		$soil_surface_resistance = $this->convertSeverities($form_state->getValue('range_assessment_soil_surface_resistance'));
		$soil_surface_loss = $this->convertSeverities($form_state->getValue('range_assessment_soil_surface_loss'));
		$compaction_layer = $this->convertSeverities($form_state->getValue('range_assessment_compaction_layer'));

		return ceil(($rills + $water_flow + $pedestals + $bare_ground + $gullies + $wind_scoured + $litter_movement + $soil_surface_loss + $soil_surface_resistance + $compaction_layer) / 10.0);
	}

	public function getHydrologicFunction(array &$form, FormStateInterface $form_state) {
		$rills = $this->convertSeverities($form_state->getValue('range_assessment_rills'));
		$water_flow = $this->convertSeverities($form_state->getValue('range_assessment_water_flow'));
		$pedestals = $this->convertSeverities($form_state->getValue('range_assessment_pedestals'));
		$bare_ground = $this->convertSeverities($form_state->getValue('range_assessment_bare_ground'));
		$gullies = $this->convertSeverities($form_state->getValue('range_assessment_gullies'));
		$soil_surface_resistance = $this->convertSeverities($form_state->getValue('range_assessment_soil_surface_resistance'));
		$soil_surface_loss = $this->convertSeverities($form_state->getValue('range_assessment_soil_surface_loss'));
		$compaction_layer = $this->convertSeverities($form_state->getValue('range_assessment_compaction_layer'));
		$effects_of_plants = $this->convertSeverities($form_state->getValue('range_assessment_effects_of_plants'));
		$litter_cover = $this->convertSeverities($form_state->getValue('range_assessment_litter_cover'));

		return ceil(($rills + $water_flow + $pedestals + $bare_ground + $gullies + $effects_of_plants + $litter_cover + $soil_surface_loss + $soil_surface_resistance + $compaction_layer) / 10.0);
	}

	public function getBioticIntegrity(array &$form, FormStateInterface $form_state) {
		$soil_surface_resistance = $this->convertSeverities($form_state->getValue('range_assessment_soil_surface_resistance'));
		$soil_surface_loss = $this->convertSeverities($form_state->getValue('range_assessment_soil_surface_loss'));
		$compaction_layer = $this->convertSeverities($form_state->getValue('range_assessment_compaction_layer'));
		$functional_structural = $this->convertSeverities($form_state->getValue('range_assessment_functional_structural'));
		$dead_plants = $this->convertSeverities($form_state->getValue('range_assessment_dead_plants'));
		$litter_cover = $this->convertSeverities($form_state->getValue('range_assessment_litter_cover'));
		$annual_production = $this->convertSeverities($form_state->getValue('range_assessment_annual_production'));
		$invasive_plants = $this->convertSeverities($form_state->getValue('range_assessment_invasive_plants'));
	
		//dpm(ceil(($soil_surface_loss + $soil_surface_resistance + $compaction_layer + $functional_structural + $dead_plants + $litter_cover + $annual_production + $invasive_plants) / 8.0));
		dpm("soil_surface_loss: ", $soil_surface_loss, "soil_surface_resistance: ", $soil_surface_resistance);
	}

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'range_assessments_form';
    }
}
