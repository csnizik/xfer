<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;
Use Drupal\Core\Url;



class FieldAssessmentForm extends FormBase {

	public function getAssessmentEvaluationOptions(){
		$options = [];
		$options[''] = '-- Select --';

		// TODO: "vid => d_assesment_..." is spelled incorrectly, but that is the machine name in the system.
		$taxonomy_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
			['vid' => 'd_assesment_evaluation']);
		$keys = array_keys($taxonomy_terms);
		foreach($keys as $key){
			$term = $taxonomy_terms[$key];
			$options[$key] = $term -> getName();
		}
		return $options;
	}
   /**
   * {@inheritdoc}
   */
	public function buildForm(array $form, FormStateInterface $form_state, $id = NULL){
		$producer = [];

		dpm("up to date 4");

		if($form_state->get('calculated_value') == NULL ) {
			$form_state->set('calculated_value', '');
		}

		$is_edit = $id <> NULL;

		$form['#tree'] = True;

		$form['#attached']['library'][] = 'cig_pods/producer_form';

		$form['producer_title'] = [
			'#markup' => '<h1>Field Assesment </h1>',
		];

		$form['field_assessment_shmu'] = [
			'#type' => 'select',
			'#title' => 'Select a Soil Health Management Unit',
			'#options' => [], // TODO: Populate
			'#required' => FALSE,
		];

		$form['field_assessment_date'] = [
			'#type' => 'date',
			'#title' => $this->t('Date'),
			'#description' => '',
			'#default_value' => '2022-01-01', // Default value for "date" field type is a string in form of 'yyyy-MM-dd'
			'#required' => FALSE
		];

		$form['assessment_wrapper'] = [
			'#prefix' => '<div id="assessment_wrapper">',
			'#suffix' => '</div>',
		];


		$assesment_evaluations_options = $this->getAssessmentEvaluationOptions();
		$form['assessment_wrapper']['field_soil_cover'] = [
			'#type' => 'select',
			'#title' => $this->t('Soil Cover'),
			'#options' => $assesment_evaluations_options,
			'#required' => FALSE
		];
		
		$form['assessment_wrapper']['field_residue_breakdown'] = [
			'#type' => 'select',
			'#title' => $this->t('Residue Breakdown'),
			'#options' => $assesment_evaluations_options,
			'#required' => FALSE
		];

		$form['assessment_wrapper']['field_assessment_surface_crusts'] = [
			'#type' => 'select',
			'#title' => $this->t('Surface Crusts'),
			'#options' => $assesment_evaluations_options,
			'#required' => FALSE
		];

		$form['assessment_wrapper']['field_assessment_ponding'] = [
			'#type' => 'select',
			'#title' => $this->t('Ponding'),
			'#options' => $assesment_evaluations_options,
			'#required' => FALSE
		];

		$form['assessment_wrapper']['field_assessment_penetration_resistance'] = [
			'#type' => 'select',
			'#title' => $this->t('Penetration Resistance'),
			'#options' => $assesment_evaluations_options,
			'#required' => FALSE
		];

		$form['assessment_wrapper']['field_assessment_water_stable_aggregates'] = [
			'#type' => 'select',
			'#title' => $this->t('Water Stable Aggregates'),
			'#options' => $assesment_evaluations_options,
			'#required' => FALSE
		];

		$form['assessment_wrapper']['field_assessment_soil_color'] = [
			'#type' => 'select',
			'#title' => $this->t('Soil Color'),
			'#options' => $assesment_evaluations_options,
			'#required' => FALSE
		];
		$form['assessment_wrapper']['field_assessment_plant_roots'] = [
			'#type' => 'select',
			'#title' => $this->t('Plant Roots'),
			'#options' => $assesment_evaluations_options,
			'#required' => FALSE
		];

		$form['assessment_wrapper']['field_assessment_bioligical_diversity'] = [
			'#type' => 'select',
			'#title' => $this->t('Biological Diversity'),
			'#options' => $assesment_evaluations_options,
			'#required' => FALSE
		];

		$form['assessment_wrapper']['field_assessment_biopores'] = [
			'#type' => 'select',
			'#title' => $this->t('Biopores'),
			'#options' => $assesment_evaluations_options,
			'#required' => FALSE
		];


		$form['assessment_wrapper']['actions']['send'] = [
			'#type' => 'submit',
			'#submit' => ['::calcuateResourceConcerns'],
			'#ajax' => [
				'callback' => '::calcuateResourceConcernsCallback',
				'wrapper' => 'assessment_wrapper'
			],
			'#value' => $this->t('Calculate Resource Concerns'),
		];

		$default_calculated_field = '';
		if($form_state->get('calculated_value') <> NULL){
			// dpm("it goes brrr");
			$default_calculated_field = $form_state -> get('calculated_value');
			dpm(gettype($default_calculated_field));
		}
		// dpm("calculated field");
		// dpm($default_calculated_field);

		if($default_calculated_field <> ''){
			$form['assessment_wrapper']['calculated_field_soil_organic_matter'] = [
				'#type' => 'textfield',
				'#title' => 'Soil Organic Matter',
				'#required' => FALSE,
				'#disabled' => TRUE,
				'#value' => $form_state -> get('calculated_value'),
				'#prefix' => '<div class="calculated_field_container">',
				'#suffix' => '</div>',
			];
			$form['assessment_wrapper']['calculated_field_aggregate_instability'] = [
				'#type' => 'textfield',
				'#title' => 'Aggregate Instability',
				'#required' => FALSE,
				'#disabled' => TRUE,
				'#value' => $form_state -> get('calculated_value'),
				'#prefix' => '<div class="calculated_field_container">',
				'#suffix' => '</div>',
			];
			$form['assessment_wrapper']['calculated_field_compaction'] = [
				'#type' => 'textfield',
				'#title' => 'Compaction',
				'#required' => FALSE,
				'#disabled' => TRUE,
				'#value' => $form_state -> get('calculated_value'),
				'#prefix' => '<div class="calculated_field_container">',
				'#suffix' => '</div>',
			];
			$form['assessment_wrapper']['calculated_field_soil_organism_habitat'] = [
				'#type' => 'textfield',
				'#title' => 'Soil Organism Habitat',
				'#required' => FALSE,
				'#disabled' => TRUE,
				'#value' => $form_state -> get('calculated_value'),
				'#prefix' => '<div class="calculated_field_container">',
				'#suffix' => '</div>',
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

// public function dashboardRedirect(array &$form, FormStateInterface $form_state){
// 	$form_state->setRedirect('cig_pods.awardee_dashboard_form');
// }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

  public function calcuateResourceConcerns(array &$form, FormStateInterface $form_state){
	dpm("calculateResourceConcners triggered");
	
	
	$form_values = $form_state->getValues(); //
	// $val = $form_values['lab_test_profile']; 
	$val = $form_values['assessment_wrapper']['field_assessment_soil_cover'];

	// List of fields in consideration for calculating the presence of compaction
	$compaction_keys = [
						'field_assessment_ponding', 
					    'field_assessment_penetration_resistance',
						'field_assessment_water_stable_aggregates',
						'field_assessment_soil_structure',
						'field_assessment_plant_roots',
	];
	// List of fields in consideration for calculating the presence of Soil organic matter depletion
	$soil_organic_keys = [
						'field_soil_cover',
						'field_residue_breakdown',
						'field_assessment_water_stable_aggregates',
						'field_assessment_soil_structure',
						'field_assessment_soil_color',
						'field_assessment_plant_roots',
						'field_assessment_bioligical_diversity',
						'field_assessment_biopores',
	];
	// List of fields in consideration for calculating the presence of Soil Organism Habiottat Loss Or Degradation
	$soil_organism_habitat_keys = [
						'field_soil_cover',
						'field_residue_breakdown',
						'field_assessment_surface_crusts',
						'field_assessment_water_stable_aggregates',
						'field_assessment_soil_structure',
						'field_assessment_plant_roots',
						'field_assessment_bioligical_diversity',
						'field_assessment_biopores',
	];

	// List of Fields in consideration for calcuating the presence of Aggregate Instability.
	$aggregate_instability_keys = [
		'field_soil_cover',
		'field_assessment_surface_crusts',
		'field_assessment_ponding', 
		'field_assessment_water_stable_aggregates',
		'field_assessment_soil_structure',
		'field_assessment_plant_roots',
		'field_assessment_bioligical_diversity',
		'field_assessment_biopores',
		
	];
	$assessment_options = $this->getAssessmentEvaluationOptions();


	// Identify the options
	$yes_taxonomy_id = NULL;
	$no_taxonomy_id = NULL;
	$n_a_taxonomy_id = NULL;
	foreach($assessment_options as $key => $value){
		if($value == 'Yes') $yes_taxonomy_id = $key;
		if($value == 'No') $no_taxonomy_id = $key;
		if($value == 'N/A') $n_a_taxonomy_id = $key;
	}

	// Start: Compaction
	$compaction_rc_present = NULL;

	$compaction_keys_false_count = 0;
	foreach($compaction_keys as $key){
		if($form_values['assessment_wrapper'][$key] == $no_taxonomy_id){
				$compaction_keys_false_count += 1;
		}
	}
	$compaction_rc_present = $compaction_keys_false_count >= 2 || $form_values['assessment_wrapper']['field_soil_structure'] == $no_taxonomy_id;

	// End: Compaction

	// Start: Soil Organic Matter Deplete Resource Concern calculation

	// tracks the number of fields with keys in "soil_organic_keys" that have "No" as their response
	$soil_organic_matter_false_count = 0;

	foreach($soil_organic_keys as $key){
		if($form_values['assessment_wrapper'][$key] == $no_taxonomy_id){
			$soil_organic_matter_false_count += 1;
		}
	}

	$soil_organic_matter_rc_present  = $soil_organic_matter_false_count >= 3;
	// End: Soil Organic Matter Deplete Resource Concern calculation

	// Begin: Aggregate Instability Resource concern calculation

	$aggregate_instability_rc_present = NULL;

	$aggregate_instability_false_count = 0;

	foreach($aggregate_instability_keys as $key){
		if($form_values['assessment_wrapper'][$key] == $no_taxonomy_id){
			$aggregate_instability_false_count += 1;
		}		
	}

	$aggregate_instability_rc_present = $aggregate_instability_false_count >= 2 || $form_values['assessment_wrapper']['field_assessment_water_stable_aggregates'] == $no_taxonomy_id;

	// End: Aggregate Instability Resource concern calculation

	// Begin: Soil Organism Habitat Resource Concern calculation

	$soil_organism_habitat_rc_present = NULL;

	$soil_organism_habitat_false_count = 0;

	foreach($soil_organic_keys as $key){
		if($form_values['assessment_wrapper'][$key] == $no_taxonomy_id){
			$soil_organism_habitat_false_count += 1;
		}
	}
	$soil_organism_habitat_rc_present = $soil_organic_matter_false_count >= 2;

	// End: Soil Organism Habitat Resource Concern calculation

	// Start: Save calculated values into form state.
	$form_state -> set('compaction_rc_present', $compaction_rc_present);
	$form_state -> set('aggregate_instability_rc_present', $aggregate_instability_rc_present);
	$form_state -> set('soil_organic_matter_rc_present', $soil_organic_matter_rc_present);

	// End: Save calculated values into form state.


	// $form_state->set('')
	// switch($val){
	// 	case 0:
	// 		$form_state->set('calculated_value', 'a');
	// 		break;
	// 	case 1:
	// 		$form_state->set('calculated_value', 'b');
	// 		break;
	// 	case 2:
	// 		$form_state->set('calculated_value', 'c');
	// 		break;
	// }
	// $form_state->set('calculated_value', 'brrrr2');
	// $form['assessment_wrapper']['calculated_field']['#value'] = 'brrrrr585858';
	$form_state->setRebuild(True);
  }

  public function calcuateResourceConcernsCallback(array &$form, FormStateInterface $form_state){
	return $form['assessment_wrapper'];
  }
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'producer_create_form';
  }
}