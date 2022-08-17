<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;
Use Drupal\Core\Url;



class FieldAssessmentForm extends FormBase {

	public function getAssessmentEvaluationOptions(){
		$options = [];
		$options[''] = '- Select -';

		// TODO: "vid => d_assessment_..." is spelled incorrectly, but need to
		$taxonomy_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
			['vid' => 'd_assessment_evaluation']);
		$keys = array_keys($taxonomy_terms);
		foreach($keys as $key){
			$term = $taxonomy_terms[$key];
			$options[$key] = $term -> getName();
		}
		return $options;
	}


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
	public function buildForm(array $form, FormStateInterface $form_state, $id = NULL){
		$form['#attached']['library'][] = 'cig_pods/field_assessment_form';

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


		$form['#tree'] = False; // No hierarchy needed for this form.
		$form['#attached']['library'][] = 'cig_pods/css_form';

		$form['producer_title'] = [
			'#markup' => '<h1> <b> Assessments </b> </h1>',
		];
		// TOOD: Attach appropriate CSS for this to display correctly
		$form['subform_1'] = [
			'#markup' => '<div class="subform-title-container"><h2>Cropland In-Field Soil Health Assessment </h2><h4>13 Fields | Section 1 of 1</h4></div>'
		];


		$field_assessment_shmu_value = $is_edit ? $assessment->get('field_assessment_shmu')->target_id : '';
		$form['field_assessment_shmu'] = [
			'#type' => 'select',
			'#title' => 'Select a Soil Health Management Unit',
			'#options' => $this->getSHMUOptions(),
			'#default_value' => $field_assessment_shmu_value,
			'#required' => FALSE,
		];

		// Date field requires some special handling.
		if($is_edit){
			// $field_shhmu_date_land_use_changed_value is expected to be a UNIX timestamp
			$field_assessment_shmu_date_value = $assessment->get('field_assessment_date')[0]->value;
			$field_assessment_shmu_date_form_value = date("Y-m-d", $field_assessment_shmu_date_value);
		} else {
			$field_assessment_shmu_date_form_value = '2022-01-01'; // Use this as default because otherwise it will default to Jan 1 1969
		}

		$form['field_assessment_date'] = [
			'#type' => 'date',
			'#title' => $this->t('Date'),
			'#description' => '',
			'#default_value' => $field_assessment_shmu_date_form_value, // Default value for "date" field type is a string in form of 'yyyy-MM-dd'
			'#required' => FALSE
		];

		$form['assessment_wrapper'] = [
			'#prefix' => '<div id="assessment_wrapper">',
			'#suffix' => '</div>',
		];

		$field_assessment_soil_cover_value = $is_edit ? $assessment->get('field_assessment_soil_cover')->target_id : '';

		$assessment_evaluations_options = $this->getAssessmentEvaluationOptions();
		$form['assessment_wrapper']['field_assessment_soil_cover'] = [
			'#type' => 'select',
			'#title' => $this->t('Soil Cover'),
			'#options' => $assessment_evaluations_options,
			'#default_value' => $field_assessment_soil_cover_value,
			'#required' => FALSE
		];

		$field_assessment_residue_breakdown_value = $is_edit ? $assessment->get('field_assessment_residue_breakdown')->target_id : '';

		$form['assessment_wrapper']['field_assessment_residue_breakdown'] = [
			'#type' => 'select',
			'#title' => $this->t('Residue Breakdown'),
			'#options' => $assessment_evaluations_options,
			'#default_value' => $field_assessment_residue_breakdown_value,
			'#required' => FALSE
		];

		$field_assessment_surface_crusts_value = $is_edit ? $assessment->get('field_assessment_surface_crusts')->target_id : '';

		$form['assessment_wrapper']['field_assessment_surface_crusts'] = [
			'#type' => 'select',
			'#title' => $this->t('Surface Crusts'),
			'#options' => $assessment_evaluations_options,
			'#default_value' => $field_assessment_surface_crusts_value,
			'#required' => FALSE
		];
		$field_assessment_ponding_value = $is_edit ? $assessment->get('field_assessment_ponding')->target_id : '';

		$form['assessment_wrapper']['field_assessment_ponding'] = [
			'#type' => 'select',
			'#title' => $this->t('Ponding'),
			'#options' => $assessment_evaluations_options,
			'#default_value' => $field_assessment_ponding_value,
			'#required' => FALSE
		];

		$field_assessment_penetration_resistance_value = $is_edit ? $assessment->get('field_assessment_penetration_resistance')->target_id : '';

		$form['assessment_wrapper']['field_assessment_penetration_resistance'] = [
			'#type' => 'select',
			'#title' => $this->t('Penetration Resistance'),
			'#options' => $assessment_evaluations_options,
			'#default_value' => $field_assessment_penetration_resistance_value,
			'#required' => FALSE
		];
		$field_assessment_water_stable_aggregates_value = $is_edit ? $assessment->get('field_assessment_water_stable_aggregates')->target_id : '';

		$form['assessment_wrapper']['field_assessment_water_stable_aggregates'] = [
			'#type' => 'select',
			'#title' => $this->t('Water Stable Aggregates'),
			'#options' => $assessment_evaluations_options,
			'#default_value' => $field_assessment_water_stable_aggregates_value,
			'#required' => FALSE
		];

		$field_assessment_soil_structure_value = $is_edit ? $assessment->get('field_assessment_soil_structure')->target_id : '';

		$form['assessment_wrapper']['field_assessment_soil_structure'] = [
			'#type' => 'select',
			'#title' => $this->t('Soil Structure'),
			'#options' => $assessment_evaluations_options,
			'#default_value' => $field_assessment_soil_structure_value,
			'#required' => FALSE
		];

		$field_assessment_soil_color_value = $is_edit ? $assessment->get('field_assessment_soil_color')->target_id : '';

		$form['assessment_wrapper']['field_assessment_soil_color'] = [
			'#type' => 'select',
			'#title' => $this->t('Soil Color'),
			'#options' => $assessment_evaluations_options,
			'#default_value' => $field_assessment_soil_color_value,
			'#required' => FALSE
		];

		$field_assessment_plant_roots_value = $is_edit ? $assessment->get('field_assessment_plant_roots')->target_id : '';

		$form['assessment_wrapper']['field_assessment_plant_roots'] = [
			'#type' => 'select',
			'#title' => $this->t('Plant Roots'),
			'#options' => $assessment_evaluations_options,
			'#default_value' => $field_assessment_plant_roots_value,
			'#required' => FALSE
		];

		$field_assessment_biological_diversity_value = $is_edit ? $assessment->get('field_assessment_biological_diversity')->target_id : '';

		$form['assessment_wrapper']['field_assessment_biological_diversity'] = [
			'#type' => 'select',
			'#title' => $this->t('Biological Diversity'),
			'#options' => $assessment_evaluations_options,
			'#default_value' => $field_assessment_biological_diversity_value,
			'#required' => FALSE
		];
		$field_assessment_biopores_value = $is_edit ? $assessment->get('field_assessment_biopores')->target_id : '';


		$form['assessment_wrapper']['field_assessment_biopores'] = [
			'#type' => 'select',
			'#title' => $this->t('Biopores'),
			'#options' => $assessment_evaluations_options,
			'#default_value' => $field_assessment_biopores_value,
			'#required' => FALSE
		];


		$form['assessment_wrapper']['actions']['identify-resource-concerns'] = [
			'#type' => 'submit',
			'#submit' => ['::calcuateResourceConcerns'],
			'#ajax' => [
				'callback' => '::calcuateResourceConcernsCallback',
				'wrapper' => 'assessment_wrapper'
			],
			'#attributes' => [
				'class' => ['identify-resource-concerns-button']
			],
			'#value' => $this->t('Identify Resource Concerns'),
		];

		if( $form_state -> get( 'calculate_rcs' )){

			// Invariant: If calculate RCS is True, then all ***_rc_present vars will have a value
			$soil_organic_matter_rc = $form_state -> get('soil_organic_matter_rc_present') ? 'Present' : 'Not Present';
			$form['assessment_wrapper']['field_assessment_rc_soil_organic_matter'] = [
				'#type' => 'textfield',
				'#title' => 'Soil Organic Matter Depletion Resource Concern',
				'#required' => FALSE,
				'#disabled' => TRUE,
				'#value' => $soil_organic_matter_rc,
				'#prefix' => '<div class="calculated_field_container">',
				'#suffix' => '</div>',
			];

			$agg_instability_val = $form_state -> get('aggregate_instability_rc_present')  ? 'Present' : 'Not Present';

			$form['assessment_wrapper']['field_assessment_rc_aggregate_instability'] = [
				'#type' => 'textfield',
				'#title' => 'Aggregate Instability Resource Concern',
				'#required' => FALSE,
				'#disabled' => TRUE,
				'#value' => $agg_instability_val,
				'#prefix' => '<div class="calculated_field_container">',
				'#suffix' => '</div>',
			];
			$compaction_val = $form_state -> get('compaction_rc_present')  ? 'Present' : 'Not Present';

			$form['assessment_wrapper']['field_assessment_rc_compaction'] = [
				'#type' => 'textfield',
				'#title' => 'Compaction Resource Concern',
				'#required' => FALSE,
				'#disabled' => TRUE,
				'#value' => $compaction_val,
				'#prefix' => '<div class="calculated_field_container">',
				'#suffix' => '</div>',
			];
			$cfsoh_val = $form_state -> get('soil_organism_habitat_rc_present')  ? 'Present' : 'Not Present';
			$form['assessment_wrapper']['field_assessment_rc_soil_organism_habitat'] = [
				'#type' => 'textfield',
				'#title' => 'Soil Organism Habitat Resource Concern',
				'#required' => FALSE,
				'#disabled' => TRUE,
				'#value' => $cfsoh_val,
				'#prefix' => '<div class="calculated_field_container">',
				'#suffix' => '</div>',
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
public function submitForm(array &$form, FormStateInterface $form_state) {
	$ignored_fields = ['send',
						'form_build_id',
						'form_token',
						'form_id',
						'op',
						'actions',
						'assessment_wrapper',
						'save',
						'cancel',
						'identify-resource-concerns',
						'delete'];

	$calculated_fields = [
						'field_assessment_rc_soil_organic_matter',
						'field_assessment_rc_aggregate_instability',
						'field_assessment_rc_compaction',
						'field_assessment_rc_soil_organism_habitat'
	];

	$date_fields = ['field_assessment_date'];

	$is_edit = $form_state->get('operation') == 'edit';

	if($is_edit){
		$id = $form_state->get('assessment_id');
		$assessment = Asset::load($id);
	} else {
		$assessment_template = [];
		$assessment_template['type'] = 'field_assessment';
		$assessment = Asset::create($assessment_template);
	}

	$form_values = $form_state -> getValues();

	// TODO: fix naming
	$related_shmu_id = $form_values['field_assessment_shmu'];
	$date = $form_values['field_assessment_date'];
	$related_shmu = Asset::load($related_shmu_id);
	if($related_shmu <> NULL) {
		$related_shmu_name = $related_shmu -> getName();
	} else {
		$related_shmu_name = '';
	}
	$assessment->set('name', "CIFSH Assessment");

	foreach( $form_values as $key => $value ){
		if(in_array($key,$ignored_fields)) continue;
		if(in_array($key,$date_fields)){
			// $value is expected to be a string of format yyyy-mm-dd
			$assessment -> set( $key, strtotime( $value ) );
			continue;
		}
		// Handled outside of loop
		if(in_array($key, $calculated_fields)){
			continue;
		}

		$assessment->set($key,$value);
	}

	// Calculated fields
	$assessment->set('field_assessment_rc_soil_organic_matter',$form_state->get('soil_organic_matter_rc_present'));
	$assessment->set('field_assessment_rc_aggregate_instability',$form_state->get('aggregate_instability_rc_present'));
	$assessment->set('field_assessment_rc_compaction',$form_state->get('compaction_rc_present'));
	$assessment->set('field_assessment_rc_soil_organism_habitat',$form_state->get('soil_organism_habitat_rc_present'));


	$assessment->save();
	$form_state->setRedirect('cig_pods.awardee_dashboard_form');


  }


  public function calcuateResourceConcerns(array &$form, FormStateInterface $form_state){

	$form_values = $form_state->getValues(); //

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
						'field_assessment_biological_diversity',
						'field_assessment_biopores',
	];
	// List of fields in consideration for calculating the presence of Soil Organism Habitat Loss Or Degradation
	$soil_organism_habitat_keys = [
						'field_soil_cover',
						'field_residue_breakdown',
						'field_assessment_surface_crusts',
						'field_assessment_water_stable_aggregates',
						'field_assessment_soil_structure',
						'field_assessment_plant_roots',
						'field_assessment_biological_diversity',
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
						'field_assessment_biological_diversity',
						'field_assessment_biopores',
	];
	$assessment_options = $this->getAssessmentEvaluationOptions();


	// Identify the options
	$yes_taxonomy_id = NULL;
	$no_taxonomy_id = NULL;
	$n_a_taxonomy_id = NULL;

	// Important: If the dropdowns change value in the db, this code needs to be changed to reflect the new values.
	foreach($assessment_options as $key => $value){
		if($value == 'Yes') $yes_taxonomy_id = $key;
		if($value == 'No') $no_taxonomy_id = $key;
		if($value == 'N/A') $n_a_taxonomy_id = $key;
	}

	// Start: Compaction
	$compaction_rc_present = NULL;

	$compaction_keys_false_count = 0;
	foreach($compaction_keys as $key){
		if($form_values[$key] == $no_taxonomy_id){
				$compaction_keys_false_count += 1;
		}
	}
	$compaction_rc_present = $compaction_keys_false_count >= 2 || $form_values['field_soil_structure'] == $no_taxonomy_id;

	// End: Compaction

	// Start: Soil Organic Matter Deplete Resource Concern calculation

	// tracks the number of fields with keys in "soil_organic_keys" that have "No" as their response
	$soil_organic_matter_false_count = 0;

	foreach($soil_organic_keys as $key){
		if($form_values[$key] == $no_taxonomy_id){
			$soil_organic_matter_false_count += 1;
		}
	}

	$soil_organic_matter_rc_present  = $soil_organic_matter_false_count >= 3;
	// End: Soil Organic Matter Deplete Resource Concern calculation

	// Begin: Aggregate Instability Resource concern calculation

	$aggregate_instability_rc_present = NULL;

	$aggregate_instability_false_count = 0;

	foreach($aggregate_instability_keys as $key){
		if($form_values[$key] == $no_taxonomy_id){
			$aggregate_instability_false_count += 1;
		}
	}

	$aggregate_instability_rc_present = $aggregate_instability_false_count >= 2 || $form_values['field_assessment_water_stable_aggregates'] == $no_taxonomy_id;

	// End: Aggregate Instability Resource concern calculation

	// Begin: Soil Organism Habitat Resource Concern calculation

	$soil_organism_habitat_rc_present = NULL;

	$soil_organism_habitat_false_count = 0;

	foreach($soil_organic_keys as $key){
		if($form_values[$key] == $no_taxonomy_id){
			$soil_organism_habitat_false_count += 1;
		}
	}
	$soil_organism_habitat_rc_present = $soil_organic_matter_false_count >= 2;

	// End: Soil Organism Habitat Resource Concern calculation

	// Start: Save calculated values into form state.
	$form_state -> set('compaction_rc_present', $compaction_rc_present);
	$form_state -> set('aggregate_instability_rc_present', $aggregate_instability_rc_present);
	$form_state -> set('soil_organic_matter_rc_present', $soil_organic_matter_rc_present);
	$form_state -> set('soil_organism_habitat_rc_present', $soil_organism_habitat_rc_present);

	$form_state -> set('calculate_rcs', True);
	// End: Save calculated values into form state.



	$form_state->setRebuild(True);
  }

  public function calcuateResourceConcernsCallback(array &$form, FormStateInterface $form_state){
	return $form['assessment_wrapper'];
  }
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'field_assessment_form';
  }
}