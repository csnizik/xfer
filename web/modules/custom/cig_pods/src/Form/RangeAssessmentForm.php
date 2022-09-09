<?php

namespace Drupal\cig_pods\Form;

use Drupal\asset\Entity\AssetInterface;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;
Use Drupal\Core\Url;


class RangeAssessmentForm extends PodsFormBase {

    public function getSHMUOptions(){
		$options = $this->entityOptions('asset', 'soil_health_management_unit');
		return ['' => '- Select -'] + $options;
		
	}

	public function getLandUseOptions(){
		$options = $this->entityOptions('taxonomy_term', 'd_land_use');
		return ['' => '- Select -'] + $options;
	}

    /**
   * {@inheritdoc}
   */
    public function buildForm(array $form, FormStateInterface $form_state, AssetInterface $asset = NULL) {
      $assessment = $asset;

		$form['#attached']['library'][] = 'cig_pods/range_assessment_form';
        $form['#attached']['library'][] = 'cig_pods/css_form';
		$form['#tree'] = TRUE;

		if($form_state->get('rc_display') == NULL){
			$form_state->set('rc_display', array());
		}

		$severity_options = ['' => '- Select -', 5 => 'Extreme to Total', 4 => 'Moderate to Extreme', 3 => 'Moderate', 2 => 'Slight to Moderate', 1 => 'None to Slight'];

		$is_edit = $assessment <> NULL;

		if($is_edit){
			$form_state->set('operation', 'edit');
			$form_state->set('assessment_id', $assessment->id());

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
			'#markup' => '<div class="subform-title-container"><h2>Interpreting Indicators of Range Health Assessment </h2><h4>18 Fields | Section 1 of 1</h4></div>'
		];

        $shmu_value = $is_edit ? $assessment->get('shmu')->target_id : '';
		$form['shmu'] = [
			'#type' => 'select',
			'#title' => 'Select a Soil Health Management Unit (SHMU)',
			'#options' => $this->getSHMUOptions(),
			'#default_value' => $shmu_value,
			'#required' => TRUE,
		];

		if($is_edit){
			$date_value = $assessment->get('range_assessment_date')->value;
			$rangeland_timestamp_default_value = date("Y-m-d", $date_value);
		} else {
			$rangeland_timestamp_default_value = ''; // TODO: Check behavior
		}
		$form['range_assessment_date'] = [
			'#type' => 'date',
			'#title' => $this->t('Date'),
			'#description' => '',
			'#default_value' => $rangeland_timestamp_default_value,
			'#required' => TRUE
		];

		$range_assessment_land_use_value = $is_edit ? $assessment->get('range_assessment_land_use')->target_id : '';
		$form['range_assessment_land_use'] = [
			'#type' => 'select',
			'#title' => 'Land Use',
			'#options' => $this->getLandUseOptions(),
			'#default_value' => $range_assessment_land_use_value,
			'#required' => TRUE,
		];

        $range_assessment_rills_value = $is_edit ? $assessment->get('range_assessment_rills')->value : '';

		$form['range_assessment_rills'] = [
			'#type' => 'select',
			'#title' => $this->t('Rills'),
			'#options' => $severity_options,
			'#default_value' => $range_assessment_rills_value,
			'#required' => FALSE,
		];

		$range_assessment_water_flow_value = $is_edit ? $assessment->get('range_assessment_water_flow')->value : '';

		$form['range_assessment_water_flow'] = [
			'#type' => 'select',
			'#title' => $this->t('Water Flow'),
			'#options' => $severity_options,
			'#default_value' => $range_assessment_water_flow_value,
			'#required' => FALSE,
		];

		$range_assessment_pedestals_value = $is_edit ? $assessment->get('range_assessment_pedestals')->value : '';

		$form['range_assessment_pedestals'] = [
			'#type' => 'select',
			'#title' => $this->t('Pedestals and/or Terracettes'),
			'#options' => $severity_options,
			'#default_value' => $range_assessment_pedestals_value,
			'#required' => FALSE,
		];

		$range_assessment_bare_ground_value = $is_edit ? $assessment->get('range_assessment_bare_ground')->value : '';

		$form['range_assessment_bare_ground'] = [
			'#type' => 'select',
			'#title' => $this->t('Bare Ground'),
			'#options' => $severity_options,
			'#default_value' => $range_assessment_bare_ground_value,
			'#required' => FALSE,
		];

		$range_assessment_gullies_value = $is_edit ? $assessment->get('range_assessment_gullies')->value : '';

		$form['range_assessment_gullies'] = [
			'#type' => 'select',
			'#title' => $this->t('Gullies'),
			'#options' => $severity_options,
			'#default_value' => $range_assessment_gullies_value,
			'#required' => FALSE,
		];

		$range_assessment_wind_scoured_value = $is_edit ? $assessment->get('range_assessment_wind_scoured')->value : '';

		$form['range_assessment_wind_scoured'] = [
			'#type' => 'select',
			'#title' => $this->t('Wind-Scoured and/or Depositional Areas'),
			'#options' => $severity_options,
			'#default_value' => $range_assessment_wind_scoured_value,
			'#required' => FALSE,
		];

		$range_assessment_litter_movement_value = $is_edit ? $assessment->get('range_assessment_litter_movement')->value : '';

		$form['range_assessment_litter_movement'] = [
			'#type' => 'select',
			'#title' => $this->t('Litter Movement (Wind or Water)'),
			'#options' => $severity_options,
			'#default_value' => $range_assessment_litter_movement_value,
			'#required' => FALSE,
		];

		$range_assessment_soil_surface_resistance_value = $is_edit ? $assessment->get('range_assessment_soil_surface_resistance')->value : '';

		$form['range_assessment_soil_surface_resistance'] = [
			'#type' => 'select',
			'#title' => $this->t('Soil Surface Resistance to Erosion'),
			'#options' => $severity_options,
			'#default_value' => $range_assessment_soil_surface_resistance_value,
			'#required' => FALSE,
		];

		$range_assessment_soil_surface_loss_value = $is_edit ? $assessment->get('range_assessment_soil_surface_loss')->value : '';

		$form['range_assessment_soil_surface_loss'] = [
			'#type' => 'select',
			'#title' => $this->t('Soil Surface Loss and Degradation'),
			'#options' => $severity_options,
			'#default_value' => $range_assessment_soil_surface_loss_value,
			'#required' => FALSE,
		];

		$range_assessment_effects_of_plants_value = $is_edit ? $assessment->get('range_assessment_effects_of_plants')->value : '';

		$form['range_assessment_effects_of_plants'] = [
			'#type' => 'select',
			'#title' => $this->t('Effects of Plant Community Composition and Distribution on Infiltration'),
			'#options' => $severity_options,
			'#default_value' => $range_assessment_effects_of_plants_value,
			'#required' => FALSE,
		];

		$range_assessment_compaction_layer_value = $is_edit ? $assessment->get('range_assessment_compaction_layer')->value : '';

		$form['range_assessment_compaction_layer'] = [
			'#type' => 'select',
			'#title' => $this->t('Compaction Layer'),
			'#options' => $severity_options,
			'#default_value' => $range_assessment_compaction_layer_value,
			'#required' => FALSE,
		];

		$range_assessment_functional_structural_value = $is_edit ? $assessment->get('range_assessment_functional_structural')->value : '';

		$form['range_assessment_functional_structural'] = [
			'#type' => 'select',
			'#title' => $this->t('Functional/Structural Groups'),
			'#options' => $severity_options,
			'#default_value' => $range_assessment_functional_structural_value,
			'#required' => FALSE,
		];

		$range_assessment_dead_plants_value = $is_edit ? $assessment->get('range_assessment_dead_plants')->value : '';

		$form['range_assessment_dead_plants'] = [
			'#type' => 'select',
			'#title' => $this->t('Dead or Dying Plants or Plant Parts (dominant, subdominant, and minor functional/structural groups)'),
			'#options' => $severity_options,
			'#default_value' => $range_assessment_dead_plants_value,
			'#required' => FALSE,
		];

		$range_assessment_litter_cover_value = $is_edit ? $assessment->get('range_assessment_litter_cover')->value : '';

		$form['range_assessment_litter_cover'] = [
			'#type' => 'select',
			'#title' => $this->t('Litter Cover and Depth'),
			'#options' => $severity_options,
			'#default_value' => $range_assessment_litter_cover_value,
			'#required' => FALSE,
		];

		$range_assessment_annual_production_value = $is_edit ? $assessment->get('range_assessment_annual_production')->value : '';

		$form['range_assessment_annual_production'] = [
			'#type' => 'select',
			'#title' => $this->t('Annual Production'),
			'#options' => $severity_options,
			'#default_value' => $range_assessment_annual_production_value,
			'#required' => FALSE,
		];

		$range_assessment_invasive_plants_value = $is_edit ? $assessment->get('range_assessment_invasive_plants')->value : '';

		$form['range_assessment_invasive_plants'] = [
			'#type' => 'select',
			'#title' => $this->t('Invasive Plants'),
			'#options' => $severity_options,
			'#default_value' => $range_assessment_invasive_plants_value,
			'#required' => FALSE,
		];

			
		$range_assessment_vigor_plants_value = $is_edit ? $assessment->get('range_assessment_vigor_plants')->value : '';
		$form['range_assessment_vigor_plants'] = [
			'#type' => 'select',
			'#title' => $this->t('Vigor with an Emphasis on Reproductive Capability of Perennial Plants (dominant, subdominant, and minor functional/structural groups)'),
			'#options' => $severity_options,
			'#default_value' => $range_assessment_vigor_plants_value,
			'#required' => FALSE,
		];

		$form['subform_2'] = [
			'#markup' => '<div class="subform-title-container"><h2>Resource Concerns Identified from In-Field Assessment </h2></div>'
		];

		$range_assessment_rc_soil_site_stability_value = $is_edit ? $assessment->get('range_assessment_rc_soil_site_stability')->value : '';

		$form['range_assessment_rc_soil_site_stability'] = [
			'#type' => 'select',
			'#title' => $this->t('Soil and Site Stability Attribute Rating'),
			'#options' => $severity_options,
			'#default_value' => $range_assessment_rc_soil_site_stability_value,
			'#required' => FALSE,
		];

		 $range_assessment_rc_soil_site_stability_justification_value = $is_edit ? $assessment->get('range_assessment_rc_soil_site_stability_justification')->getValue()[0]['value'] : '';
			$form['range_assessment_rc_soil_site_stability_justification'] = [
				'#type' => 'textarea',
				'#title' => $this->t('Soil and Site Stability Assessment Justification'),
				'$description' => 'Soil and Site Stability Assessment Justification',
				'#required' => FALSE,
				'#default_value' => $range_assessment_rc_soil_site_stability_justification_value,
			];

			$range_assessment_rc_hydrologic_function_value = $is_edit ? $assessment->get('range_assessment_rc_hydrologic_function')->value : '';

		$form['range_assessment_rc_hydrologic_function'] = [
			'#type' => 'select',
			'#title' => $this->t('Hydrologic Function Attribute Rating'),
			'#options' => $severity_options,
			'#default_value' => $range_assessment_rc_hydrologic_function_value,
			'#required' => FALSE,
		];

			 $range_assessment_rc_hydrologic_function_justification_value = $is_edit ? $assessment->get('range_assessment_rc_hydrologic_function_justification')->getValue()[0]['value'] : '';	
			$form['range_assessment_rc_hydrologic_function_justification'] = [
				'#type' => 'textarea',
				'#title' => $this->t('Hydrological Function Assessment Justification'),
				'$description' => 'Hydrological Function Assessment Justification',
				'#required' => FALSE,
				'#default_value' => $range_assessment_rc_hydrologic_function_justification_value,
			];

			$range_assessment_rc_biotic_integrity_value = $is_edit ? $assessment->get('range_assessment_rc_biotic_integrity')->value : '';

			$form['range_assessment_rc_biotic_integrity'] = [
				'#type' => 'select',
				'#title' => $this->t('Biotic Integrity Attribute Rating'),
				'#options' => $severity_options,
				'#default_value' => $range_assessment_rc_biotic_integrity_value,
				'#required' => FALSE,
			];

			 $range_assessment_rc_biotic_integrity_justification_value = $is_edit ? $assessment->get('range_assessment_rc_biotic_integrity_justification')->getValue()[0]['value'] : '';	
			$form['range_assessment_rc_biotic_integrity_justification'] = [
				'#type' => 'textarea',
				'#title' => $this->t('Biotic Integrity Assessment Justification'),
				'$description' => 'Biotic Integrity Assessment Justification',
				'#required' => FALSE,
				'#default_value' => $range_assessment_rc_biotic_integrity_justification_value,
			];

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
		$date_timestamp = strtotime($form_state->getValue('range_assessment_date'));
		$current_timestamp = strtotime(date('Y-m-d', \Drupal::time()->getCurrentTime()));
		if ($date_timestamp > $current_timestamp) {
			$form_state->setError($form, 'Error: Invalid Date');
		}
        return;
    }


    public function dashboardRedirect(array &$form, FormStateInterface $form_state){
        $form_state->setRedirect('cig_pods.awardee_dashboard_form');
    }

	public function deleteFieldAssessment(array &$form, FormStateInterface $form_state){

		$assessment_id = $form_state->get('assessment_id');
		$rangeland = \Drupal::entityTypeManager()->getStorage('asset')->load($assessment_id);

		try{
            $rangeland->delete();
			$form_state->setRedirect('cig_pods.awardee_dashboard_form');
        }catch(\Exception $e){
            $this
          ->messenger()
          ->addError($this
          ->t($e->getMessage()));
        }
	}

	public function createElementNames(){
		return array('shmu', 'range_assessment_date', 'range_assessment_land_use', 'range_assessment_rills', 'range_assessment_water_flow', 'range_assessment_pedestals', 'range_assessment_bare_ground', 'range_assessment_gullies',
		'range_assessment_wind_scoured', 'range_assessment_litter_movement', 'range_assessment_soil_surface_resistance', 'range_assessment_soil_surface_loss', 'range_assessment_effects_of_plants',
		'range_assessment_compaction_layer', 'range_assessment_functional_structural', 'range_assessment_dead_plants', 'range_assessment_litter_cover', 'range_assessment_annual_production',
		'range_assessment_vigor_plants', 'range_assessment_invasive_plants', 
		'range_assessment_rc_soil_site_stability','range_assessment_rc_soil_site_stability_justification', 'range_assessment_rc_hydrologic_function', 'range_assessment_rc_hydrologic_function_justification', 'range_assessment_rc_biotic_integrity', 'range_assessment_rc_biotic_integrity_justification'
);
	}
    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {

		$rangeland_submission = [];
        if($form_state->get('operation') === 'create'){
            $elementNames = $this->createElementNames();
            foreach($elementNames as $elemName){
                $rangeland_submission[$elemName] = $form_state->getValue($elemName);
            }

            $rangeland_submission['type'] = 'range_assessment';
            $rangelandAssessment = Asset::create($rangeland_submission);
			$rangelandAssessment->set('name', 'IIRH Assessment');
			$rangelandAssessment->set('range_assessment_date', strtotime($form['range_assessment_date']['#value']));
            $rangelandAssessment -> save();

			$this->setProjectReference($rangelandAssessment, $rangelandAssessment->get('shmu')->target_id);

            $form_state->setRedirect('cig_pods.awardee_dashboard_form');

        }else{
            $id = $form_state->get('assessment_id');
            $rangelandAssessment = \Drupal::entityTypeManager()->getStorage('asset')->load($id);

            $elementNames = $this->createElementNames();
		    foreach($elementNames as $elemName){
                $rangelandAssessment->set($elemName, $form_state->getValue($elemName));
            }
			$rangelandAssessment->set('name', 'IIRH Assessment');
			$rangelandAssessment->set('range_assessment_date', strtotime($form['range_assessment_date']['#value']));
            $rangelandAssessment->save();

			$this->setProjectReference($rangelandAssessment, $rangelandAssessment->get('shmu')->target_id);

            $form_state->setRedirect('cig_pods.awardee_dashboard_form');
        }

    }

	public function setProjectReference($assetReference, $shmuReference){
		$shmu = \Drupal::entityTypeManager()->getStorage('asset')->load($shmuReference);
		$project = \Drupal::entityTypeManager()->getStorage('asset')->load($shmu->get('project')->target_id);
		$assetReference->set('project', $project);
		$assetReference->save();
	}

	public function getSoilSiteStability(array &$form, FormStateInterface $form_state, $severity_options) {
		$rills = $form_state->getValue('range_assessment_rills');
		$water_flow = $form_state->getValue('range_assessment_water_flow');
		$pedestals = $form_state->getValue('range_assessment_pedestals');
		$bare_ground = $form_state->getValue('range_assessment_bare_ground');
		$gullies = $form_state->getValue('range_assessment_gullies');
		$wind_scoured = $form_state->getValue('range_assessment_wind_scoured');
		$litter_movement = $form_state->getValue('range_assessment_litter_movement');
		$soil_surface_resistance = $form_state->getValue('range_assessment_soil_surface_resistance');
		$soil_surface_loss = $form_state->getValue('range_assessment_soil_surface_loss');
		$compaction_layer = $form_state->getValue('range_assessment_compaction_layer');

		$score = ceil(($rills + $water_flow + $pedestals + $bare_ground + $gullies + $wind_scoured + $litter_movement + $soil_surface_loss + $soil_surface_resistance + $compaction_layer) / 10.0);
		return $severity_options[$score];
	}

	public function getHydrologicFunction(array &$form, FormStateInterface $form_state, $severity_options) {
		$rills = $form_state->getValue('range_assessment_rills');
		$water_flow = $form_state->getValue('range_assessment_water_flow');
		$pedestals = $form_state->getValue('range_assessment_pedestals');
		$bare_ground = $form_state->getValue('range_assessment_bare_ground');
		$gullies = $form_state->getValue('range_assessment_gullies');
		$soil_surface_resistance = $form_state->getValue('range_assessment_soil_surface_resistance');
		$soil_surface_loss = $form_state->getValue('range_assessment_soil_surface_loss');
		$compaction_layer = $form_state->getValue('range_assessment_compaction_layer');
		$effects_of_plants = $form_state->getValue('range_assessment_effects_of_plants');
		$litter_cover = $form_state->getValue('range_assessment_litter_cover');

		$score = ceil(($rills + $water_flow + $pedestals + $bare_ground + $gullies + $effects_of_plants + $litter_cover + $soil_surface_loss + $soil_surface_resistance + $compaction_layer) / 10.0);
		return $severity_options[$score];
	}

	public function getBioticIntegrity(array &$form, FormStateInterface $form_state, $severity_options) {
		$soil_surface_resistance = $form_state->getValue('range_assessment_soil_surface_resistance');
		$soil_surface_loss = $form_state->getValue('range_assessment_soil_surface_loss');
		$compaction_layer = $form_state->getValue('range_assessment_compaction_layer');
		$functional_structural = $form_state->getValue('range_assessment_functional_structural');
		$dead_plants = $form_state->getValue('range_assessment_dead_plants');
		$litter_cover = $form_state->getValue('range_assessment_litter_cover');
		$annual_production = $form_state->getValue('range_assessment_annual_production');
		$invasive_plants = $form_state->getValue('range_assessment_invasive_plants');
		$vigor_plants = $form_state->getValue('range_assessment_vigor_plants');


		$score = ceil(($soil_surface_loss + $soil_surface_resistance + $compaction_layer + $functional_structural + $dead_plants + $litter_cover + $annual_production + $invasive_plants + $vigor_plants) / 9.0);
		return $severity_options[$score];
	}

	// public function displayRcScores(array &$form, FormStateInterface $form_state ){
	// 	$form_state->set('rc_display', array(1,2,3,4));
	// 	$form_state->setRebuild(TRUE);
	// }

	// public function updateScores(array &$form, FormStateInterface $form_state){

    //     return $form['rc_container'];
    // }


    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'range_assessments_form';
    }
}
