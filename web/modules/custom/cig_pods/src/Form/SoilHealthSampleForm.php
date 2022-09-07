<?php

namespace Drupal\cig_pods\Form;

use Drupal\asset\Entity\AssetInterface;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;
Use Drupal\Core\Url;

class SoilHealthSampleForm extends PodsFormBase {

    public function getSHMUOptions() {
		$options = $this->entityOptions('asset', 'soil_health_management_unit');
		return ['' => '- Select -'] + $options;
	}

	public function getEquipmentUsedOptions(){
		$options = $this->entityOptions('taxonomy_term', 'd_equipment');
		return ['' => '- Select -'] + $options;
	}

	public function getPlantOptions() {
		$options = $this->entityOptions('taxonomy_term', 'd_plant_stage');
		return ['' => '- Select -'] + $options;
	}

    public function buildSampleInformationSection(array &$form, FormStateInterface &$form_state, $is_edit = NULL, $sample_collection = NULL){
		$form['form_title'] = [
			'#markup' => '<h1 id="form-title">Sample Collection</h1>'
		];

		$form['subform_1'] = [
			'#markup' => '<div class="subform-title-container"><h2>Sample Information</h2><h4>6 Fields | Section 1 of 2</h4></div>'
		];

        $shmu_options = $this->getSHMUOptions();
		$shmu_default_value = $is_edit ?  $sample_collection->get('shmu')->target_id : '';
		$form['shmu'] = [
		  '#type' => 'select',
		  '#title' => t('Select a Soil Health Management Unit (SHMU)'),
		  '#options' => $shmu_options,
		  '#default_value' => $shmu_default_value,
		  '#required' => TRUE,
		];

		$date_default_value = '';
		if($is_edit && $sample_collection){
            // $field_shhmu_date_land_use_changed_value is expected to be a UNIX timestamp
            $prev_date_value = $sample_collection->get('field_soil_sample_collection_dat')[0]->value;
            $date_default_value = date("Y-m-d", $prev_date_value);
        }
        $form['field_soil_sample_collection_dat'] = [
            '#type' => 'date',
            '#title' => t('Sample Collection Date'),
            '#date_label_position' => 'within',
			'#default_value' => $date_default_value,
            '#required' => TRUE,
        ];

        $equipment_used_options = $this->getEquipmentUsedOptions();
		$equipment_used_default_value = $is_edit ?  $sample_collection->get('field_equipment_used')->target_id : '';
        $form['field_equipment_used'] = [
            '#type' => 'select',
            '#title' => t('Equipment Used'),
            '#options' => $equipment_used_options,
			'#default_value' => $equipment_used_default_value,
            '#required' => TRUE,
        ];

    if ($is_edit && isset($sample_collection->get('field_diameter')->numerator)) {
    	$diameter_default_value = $is_edit ?  $this->convertFractionsToDecimal($sample_collection, 'field_diameter') : NULL;
    }
  	$form['field_diameter'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Probe Diameter'),
			'$description' => 'Diameter',
			'#default_value' => $diameter_default_value,
			'#required' => FALSE,
		];

		$soil_sample_default_value = $is_edit ?  $sample_collection->get('name')->value : '';
		$form['soil_sample_id'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Soil Sample ID'),
			'$description' => 'Soil Sample ID',
			'#default_value' => $soil_sample_default_value,
			'#required' => TRUE,
		];

        $plant_options = $this->getPlantOptions();
		$plant_stage_default_value = '';
		if($is_edit && $sample_collection) {
			$plant_stage_default_value = $sample_collection->get('field_plant_stage_at_sampling')->target_id;
		}
        $form['field_plant_stage_at_sampling'] = [
            '#type' => 'select',
            '#title' => t('Plant Stage at Sampling'),
            '#options' => $plant_options,
			'#default_value' => $plant_stage_default_value,
            '#required' => TRUE,
        ];

		$sample_depth_default_value = $is_edit ? $this->convertFractionsToDecimal($sample_collection, 'field_sampling_depth') : NULL;
        $form['field_sampling_depth'] = [
			'#type' => 'number',
			'#title' => $this->t('Sampling Depth (Unit Inches)'),
			'#step' => 1,
			'$description' => 'In feet',
			'#default_value' => $sample_depth_default_value,
			'#required' => TRUE,
		];
    }

	public function dashboardRedirect(array &$form, FormStateInterface $form_state){
		$form_state->setRedirect('cig_pods.awardee_dashboard_form');
	}

	/**
	* Deletes the sample_collection that is currently being viewed.
	*/
	public function deleteSampleCollection(array &$form, FormStateInterface $form_state){

		$sample_collection_id = $form_state->get('sample_id');
		$sample_collection = \Drupal::entityTypeManager()->getStorage('asset')->load($sample_collection_id);

		try{
			$sample_collection->delete();
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
	public function buildForm(array $form, FormStateInterface $form_state, AssetInterface $asset = NULL){
    $sample_collection = $asset;
		$form['#attached']['library'][] = 'cig_pods/soil_health_sample_form';
		$is_edit = $sample_collection <> NULL;
        $form['#attached']['library'][] = 'cig_pods/css_form';

		if($is_edit){
			$form_state->set('operation','edit');
			$form_state->set('sample_id',$sample_collection->id());
		} else {
			$form_state->set('operation','create');
		}

        $this->buildSampleInformationSection($form, $form_state, $is_edit, $sample_collection);

		$form['subform_2'] = [
			'#markup' => '<div class="subform-title-container" id="subform2"><h2>GPS Points</h2><h4>6 Fields | Section 2 of 2</h4></div>'
		];

		$form['lat_long_div'] = [
			'#prefix' => '<div id="lat-long"',
			'#suffix' => '</div>',
		];

		$form['lat_long_div']['lat'] = [
			'#type' => 'number',
			'#title' => $this->t('Latitude'),
			'#min' => -90,
			'#max' => 90,
			'#step' => 0.00000000000001,
		];

		$form['lat_long_div']['lon'] = [
			'#type' => 'number',
			'#title' => $this->t('Longitude'),
			'#min' => -180,
			'#max' => 180,
			'#step' => 0.00000000000001,
		];

		$form['lat_long_div']['add_point'] = [
			'#type' => 'button',
			'#value' => $this->t('Add point to map'),
			'#attributes' => [
				'onclick' => 'event.preventDefault();',
			],
		];

		$form['field_map'] = [
			'#type' => 'farm_map_input',
			'#map_type' => 'pods',
			 '#behaviors' => [
				'latlon_add',
				'zoom_us',
       		 	'wkt_refresh_soil_sample',
      		],
			'#display_raw_geometry' => TRUE,
			'#default_value' => $is_edit ? $sample_collection->get('field_soil_sample_geofield')->value : '',
		];

		// Add submit button
		$button_save_label = $is_edit ? $this->t('Save Changes') : $this->t('Save');
		$form['actions']['save'] = array(
			'#type' => 'submit',
			'#value' => $button_save_label,
		);

		$form['actions']['cancel'] = [
			'#type' => 'submit',
			'#value' => $this->t('Cancel'),
			"#limit_validation_errors" => '',
			'#submit' => ['::dashboardRedirect'],
		];

		if($is_edit){
			$form['actions']['delete'] = [
				'#type' => 'submit',
				'#value' => $this->t('Delete'),
				'#submit' => ['::deleteSampleCollection'],
				"#limit_validation_errors" => array(),
				'#prefix' => '<div class="remove-button-container">',
				'#suffix' => '</div>',
			];
		}
		// commented out because not part of mvp
		// but will be used latter

		// $form['actions']['add_assessment'] = array(
		// 	'#type' => 'submit',
		// 	'#value' => $this->t('Next: Add Assessment'),
		// 	'#submit' => ['::dashboardRedirect'],
		// );

		return $form;
	}

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state){
	return;
}

private function convertFractionsToDecimal($soilSample, $field){
	$num = $soilSample->get($field)[0]->getValue()["numerator"];
	$denom = $soilSample->get($field)[0]->getValue()["denominator"];
	return $num / $denom;
}

public function entityfields(){
	return array('field_diameter','field_plant_stage_at_sampling','field_sampling_depth','shmu','field_soil_sample_collection_dat', 'field_equipment_used');
}

/**
 * {@inheritdoc}
 */
  public function submitForm(array &$form, FormStateInterface $form_state) {

	$form_values = $this->entityfields();

	$is_create = $form_state->get('operation') === 'create';

	if($is_create){
		$sample_collection_submission = [];
		$sample_collection_submission['type'] = 'soil_health_sample';

		foreach($form_values as $value){
			$sample_collection_submission[$value] = $form_state->getValue($value);
		}
		$sample_collection_submission['name'] = $form_state->getValue('soil_sample_id');

		$sample_collection = Asset::create($sample_collection_submission);
		$sample_collection->set('field_soil_sample_geofield', $form_state->getValue('field_map'));
		$sample_collection->save();

		$this->setProjectReference($sample_collection, $sample_collection->get('shmu')->target_id);

		$form_state->setRedirect('cig_pods.awardee_dashboard_form');
	} else {
		$id = $form_state->get('sample_id');
		$sample_collection = \Drupal::entityTypeManager()->getStorage('asset')->load($id);

		foreach($form_values as $value){
			$sample_collection->set($value, $form_state->getValue($value));
		}
		$sample_collection->set('field_soil_sample_geofield', $form_state->getValue('field_map'));
		$sample_collection->set('name', $form_state->getValue('soil_sample_id'));

		$sample_collection->save();

		$this->setProjectReference($sample_collection, $sample_collection->get('shmu')->target_id);

		$form_state->setRedirect('cig_pods.awardee_dashboard_form');
	}

	return;
  }

  public function setProjectReference($assetReference, $shmuReference){
	$shmu = \Drupal::entityTypeManager()->getStorage('asset')->load($shmuReference);
	$project = \Drupal::entityTypeManager()->getStorage('asset')->load($shmu->get('project')->target_id);
	$assetReference->set('project', $project);
	$assetReference->save();
}

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sample_collection_form';
  }
}
