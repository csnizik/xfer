<?php

namespace Drupal\cig_pods\Form;

use Drupal\asset\Entity\AssetInterface;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;
Use Drupal\Core\Url;

class PastureAssessmentForm extends PodsFormBase {
    public function getSHMUOptions(){
		$options = $this->entityOptions('asset', 'soil_health_management_unit');
		return ['' => '- Select -'] + $options;
	}
    /**
   * {@inheritdoc}
   */
    public function buildForm(array $form, FormStateInterface $form_state, AssetInterface $asset = NULL) {
      $assessment = $asset;
		// Attach proper CSS to form
		$form['#attached']['library'][] = 'cig_pods/pasture_assessment_form';
        $form['#attached']['library'][] = 'cig_pods/css_form';
		$form['#tree'] = TRUE;

		if($form_state->get('rc_display') == NULL){
			$form_state->set('rc_display', array());
		}

		$severity_options = [5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1];

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
		$form['subform_1'] = [
			'#markup' => '<div class="subform-title-container"><h2>Pasture Condition Score Assessment </h2><h4>10 Fields | Section 1 of 1</h4></div>'
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
			$date_value = $assessment->get('pasture_assessment_date')->value;
			$pasture_timestamp_default_value = date("Y-m-d", $date_value);
		} else {
			$pasture_timestamp_default_value = '';
		}
		$form['pasture_assessment_date'] = [
			'#type' => 'date',
			'#title' => $this->t('Date'),
			'#description' => '',
			'#default_value' => $pasture_timestamp_default_value,
			'#required' => TRUE
		];

        $pasture_assessment_rills_value = $is_edit ? $assessment->get('pasture_assessment_desirable_plants')->value : '';

		$form['pasture_assessment_desirable_plants'] = [
			'#type' => 'select',
			'#title' => $this->t('Percent Desirable Plants'),
			'#options' => $severity_options,
			'#default_value' => $pasture_assessment_rills_value,
			'#required' => TRUE,
			'#empty_option' => '- Select -',

		];

		$pasture_assessment_water_flow_value = $is_edit ? $assessment->get('pasture_assessment_Legume_dry_weight')->value : '';

		$form['pasture_assessment_Legume_dry_weight'] = [
			'#type' => 'select',
			'#title' => $this->t('Percent Legume by Dry Weight'),
			'#options' => $severity_options,
			'#default_value' => $pasture_assessment_water_flow_value,
			'#required' => TRUE,
			'#empty_option' => '- Select -',

		];

		$pasture_assessment_pedestals_value = $is_edit ? $assessment->get('pasture_assessment_live_plant_cover')->value : '';

		$form['pasture_assessment_live_plant_cover'] = [
			'#type' => 'select',
			'#title' => $this->t('Live (includes dormant) Plant Cover'),
			'#options' => $severity_options,
			'#default_value' => $pasture_assessment_pedestals_value,
			'#required' => TRUE,
			'#empty_option' => '- Select -',

		];

		$pasture_assessment_effects_of_plants_value = $is_edit ? $assessment->get('pasture_assessment_diversity_dry_weight')->value : '';

		$form['pasture_assessment_diversity_dry_weight'] = [
			'#type' => 'select',
			'#title' => $this->t('Plant Diversity by Dry Weight'),
			'#options' => $severity_options,
			'#default_value' => $pasture_assessment_effects_of_plants_value,
			'#required' => TRUE,
			'#empty_option' => '- Select -',

		];

		$pasture_assessment_bare_ground_value = $is_edit ? $assessment->get('pasture_assessment_litter_soil_cover')->value : '';

		$form['pasture_assessment_litter_soil_cover'] = [
			'#type' => 'select',
			'#title' => $this->t('Plant Residue and Litter as Soil Cover'),
			'#options' => $severity_options,
			'#default_value' => $pasture_assessment_bare_ground_value,
			'#required' => TRUE,
			'#empty_option' => '- Select -',

		];

		$pasture_assessment_gullies_value = $is_edit ? $assessment->get('pasture_assessment_grazing_utilization_severity')->value : '';

		$form['pasture_assessment_grazing_utilization_severity'] = [
			'#type' => 'select',
			'#title' => $this->t('Grazing Utilization and Severity'),
			'#options' => $severity_options,
			'#default_value' => $pasture_assessment_gullies_value,
			'#required' => TRUE,
			'#empty_option' => '- Select -',
		];

		$pasture_assessment_wind_scoured_value = $is_edit ? $assessment->get('pasture_assessment_livestock_concentration')->value : '';

		$form['pasture_assessment_livestock_concentration'] = [
			'#type' => 'select',
			'#title' => $this->t('Livestock Concentration'),
			'#options' => $severity_options,
			'#default_value' => $pasture_assessment_wind_scoured_value,
			'#required' => TRUE,
			'#empty_option' => '- Select -',

		];

		$pasture_assessment_wind_scoured_value = $is_edit ? $assessment->get('pasture_assessment_soil_compaction')->value : '';

		$form['pasture_assessment_soil_compaction'] = [
			'#type' => 'select',
			'#title' => $this->t('Soil Compaction and Soil Regenerative Features'),
			'#options' => $severity_options,
			'#default_value' => $pasture_assessment_wind_scoured_value,
			'#required' => TRUE,
			'#empty_option' => '- Select -',

		];

		$pasture_assessment_litter_movement_value = $is_edit ? $assessment->get('pasture_assessment_plant_rigor')->value : '';

		$form['pasture_assessment_plant_rigor'] = [
			'#type' => 'select',
			'#title' => $this->t('Plant Rigor'),
			'#options' => $severity_options,
			'#default_value' => $pasture_assessment_litter_movement_value,
			'#required' => TRUE,
			'#empty_option' => '- Select -',

		];

		$pasture_assessment_soil_surface_resistance_value = $is_edit ? $assessment->get('pasture_assessment_erosion')->value : '';

		$form['pasture_assessment_erosion'] = [
			'#type' => 'select',
			'#title' => $this->t('Erosion'),
			'#options' => $severity_options,
			'#default_value' => $pasture_assessment_soil_surface_resistance_value,
			'#required' => TRUE,
			'#empty_option' => '- Select -',

		];



		$form['rc_container'] = [
            '#prefix' => '<div id="rc_container"',
			'#suffix' => '</div>',
        ];

		$form['actions']['identify-resource-concerns'] = [
			'#type' => 'submit',
			'#value' => $this->t('Calculate Score'),
			'#submit' => ['::displayRcScores'],
			'#ajax' => [
				'callback' => '::updateScores',
				'wrapper' => 'rc_container',
			],
			'#prefix' => '<div id="score_button"',
			'#suffix' => '</div>',

		];

			$toDisplay = $form_state->get('rc_display');
		if (count($toDisplay) <> 0) {
			$form['rc_container']['rc_header'] = [
				'#markup' => '<h5> Resource Concerns Identified from In-Field Assessment. </h5>'
			];
			$form['rc_container']['rc_soil'] = [
				'#markup' => $this->t('<p classname="Soil"> <b> Calculated from in-field assessments</b></p>
				<p><b>Pasture Condition Score: @soil_score</b></p>', ['@soil_score' => $this->getPastureCondition($form, $form_state, $severity_options)])
			];

		}


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
		$date_timestamp = strtotime($form_state->getValue('pasture_assessment_date'));
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
		$PastureAssessment = \Drupal::entityTypeManager()->getStorage('asset')->load($assessment_id);


    try{
      $PastureAssessment->delete();
  		$form_state->setRedirect('cig_pods.awardee_dashboard_form');
    }catch(\Exception $e){
              $this
            ->messenger()
            ->addError($this
            ->t($e->getMessage()));
    }



	}

	public function createElementNames(){
		return array('shmu', 'pasture_assessment_date', 'pasture_assessment_desirable_plants', 'pasture_assessment_Legume_dry_weight', 'pasture_assessment_live_plant_cover', 'pasture_assessment_diversity_dry_weight', 'pasture_assessment_litter_soil_cover',
		'pasture_assessment_grazing_utilization_severity', 'pasture_assessment_livestock_concentration', 'pasture_assessment_plant_rigor', 'pasture_assessment_erosion','pasture_assessment_soil_compaction');
	}

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {

		$pasture_submission = [];
        if($form_state->get('operation') === 'create'){
            $elementNames = $this->createElementNames();
            foreach($elementNames as $elemName){
                $pasture_submission[$elemName] = $form_state->getValue($elemName);
            }

            $pasture_submission['type'] = 'pasture_assessment';
            $pastureAssessment = Asset::create($pasture_submission);
			$pastureAssessment->set('name', 'PCS Assessment');
			$pastureAssessment->set('pasture_assessment_date', strtotime($form['pasture_assessment_date']['#value']));
            $pastureAssessment -> save();

			$this->setProjectReference($pastureAssessment, $pastureAssessment->get('shmu')->target_id);

            $form_state->setRedirect('cig_pods.awardee_dashboard_form');

        }else{
            $id = $form_state->get('assessment_id');
            $pastureAssessment = \Drupal::entityTypeManager()->getStorage('asset')->load($id);

            $elementNames = $this->createElementNames();
		    foreach($elementNames as $elemName){
                $pastureAssessment->set($elemName, $form_state->getValue($elemName));
            }
			$pastureAssessment->set('name', 'PCS Assessment');
			$pastureAssessment->set('pasture_assessment_date', strtotime($form['pasture_assessment_date']['#value']));
            $pastureAssessment->save();

			$this->setProjectReference($pastureAssessment, $pastureAssessment->get('shmu')->target_id);

            $form_state->setRedirect('cig_pods.awardee_dashboard_form');
        }

    }

	public function setProjectReference($assetReference, $shmuReference){
		$shmu = \Drupal::entityTypeManager()->getStorage('asset')->load($shmuReference);
		$project = \Drupal::entityTypeManager()->getStorage('asset')->load($shmu->get('project')->target_id);
		$assetReference->set('project', $project);
		$assetReference->save();
	}


    	public function getPastureCondition(array &$form, FormStateInterface $form_state, $severity_options) {
		$desirable_plants = $form_state->getValue('pasture_assessment_desirable_plants');
		$Legume_dry_weight = $form_state->getValue('pasture_assessment_Legume_dry_weight');
		$live_plant_cover = $form_state->getValue('pasture_assessment_live_plant_cover');
		$diversity_dry_weight = $form_state->getValue('pasture_assessment_diversity_dry_weight');
		$litter_soil_cover = $form_state->getValue('pasture_assessment_litter_soil_cover');
		$grazing_utilization_severity = $form_state->getValue('pasture_assessment_grazing_utilization_severity');
		$livestock_concentration = $form_state->getValue('pasture_assessment_livestock_concentration');
		$pasture_assessment_soil_compaction = $form_state->getValue('pasture_assessment_soil_compaction');
		$plant_rigor = $form_state->getValue('pasture_assessment_plant_rigor');
		$erosion = $form_state->getValue('pasture_assessment_erosion');

		$score = $desirable_plants + $Legume_dry_weight + $live_plant_cover + $diversity_dry_weight + $litter_soil_cover + $grazing_utilization_severity + $livestock_concentration + $pasture_assessment_soil_compaction + $erosion + $plant_rigor;
		return $score;
	}

	public function displayRcScores(array &$form, FormStateInterface $form_state ){
		$form_state->set('rc_display', array(1,2,3,4));
		$form_state->setRebuild(TRUE);
	}

	public function updateScores(array &$form, FormStateInterface $form_state){

        return $form['rc_container'];
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'pasture_assessments_form';
    }
}
