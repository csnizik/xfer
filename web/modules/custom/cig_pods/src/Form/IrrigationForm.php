<?php

namespace Drupal\cig_pods\Form;

use Drupal\asset\Entity\AssetInterface;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;
Use Drupal\Core\Render\Element\Checkboxes;
Use Drupal\Core\Url;


class IrrigationForm extends PodsFormBase {

	public function getSHMUOptions() {
		$options = $this->entityOptions('asset', 'soil_health_management_unit');
		return ['' => '- Select -'] + $options;
	}

	public function getDecimalFromSHMUFractionFieldType(object $shmu, string $field_name){
		return $shmu->get($field_name)-> numerator / $shmu->get($field_name)->denominator;
	}

	/**
	* {@inheritdoc}
	*/
	public function buildForm(array $form, FormStateInterface $form_state, AssetInterface $asset = NULL){
    $irrigation = $asset;
        $is_edit = $irrigation <> NULL;



        if ($form_state->get('load_done') == NULL){
			$form_state->set('load_done', FALSE);
		}
        $form['#attached']['library'][] = 'cig_pods/irrigation_form';
		$form['#attached']['library'][] = 'cig_pods/css_form';
		$form['#tree'] = TRUE;
		// Determine if it is an edit process. If it is, load irrigation into local variable.
		if($is_edit){
			$form_state->set('operation','edit');
			$form_state->set('irrigation_id', $irrigation->id());
			if(!$form_state->get('load_done')){
                $form_state->set('load_done',TRUE);
			}
		} else {
			if(!$form_state->get('load_done')){
				$form_state->set('load_done',TRUE);
			}
			$form_state->set('operation','create');
		}
		$form['subform_1'] = [
			'#markup' => '<div><h1>Water Testing</h1></div>'
		];

        $form['subform_2'] = [
			'#markup' => '<div class="subform-title-container subform-title-container-top"><h2>Irrigation</h2><h4>Section 1 of 1</h4></div>'
		];
		$shmu_options = $this->getSHMUOptions();
		$shmu_default_value = $is_edit ?  $irrigation->get('shmu')->target_id : '';
		$form['shmu'] = [
		  '#type' => 'select',
		  '#title' => t('Select a Soil Health Management Unit (SHMU)'),
		  '#options' => $shmu_options,
		  '#default_value' => $shmu_default_value,
		  '#required' => TRUE,
		];

		if($is_edit){
			// $ field_shmu_irrigation_sample_date_timestamp is expected to be a UNIX timestamp

			$field_shmu_irrigation_sample_date_timestamp = $irrigation->get('field_shmu_irrigation_sample_date')->value;
			$field_shmu_irrigation_sample_date_timestamp_default_value = date("Y-m-d", $field_shmu_irrigation_sample_date_timestamp);
		} else {
			$field_shmu_irrigation_sample_date_timestamp_default_value = ''; // TODO: Check behavior
		}

		// $field_shmu_irrigation_sample_date_value = $is_edit ? $shmu->get('field_shmu_irrigation_sample_date')->value : '';
		$form['field_shmu_irrigation_sample_date'] = [
			'#type' => 'date',
			'#title' => $this->t('Sample Date'),
			'#description' => '',
			'#default_value' => $field_shmu_irrigation_sample_date_timestamp_default_value,
			'#required' => FALSE
		];

		$field_shmu_irrigation_water_ph_value = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($irrigation, 'field_shmu_irrigation_water_ph'): '';

		$form['field_shmu_irrigation_water_ph'] = [
			'#type' => 'number',
			'#title' => $this->t('Water pH'),
			'#min_value' => 1,
			'#max_value' => 14,
			'#step' => 0.01, // Float
			'#description' => '',
			'#default_value' => $field_shmu_irrigation_water_ph_value,
			'#required' => FALSE
		];

		$field_shmu_irrigation_sodium_adsorption_ratio_value = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($irrigation, 'field_shmu_irrigation_sodium_absorption_ratio'): '';

		$form['field_shmu_irrigation_sodium_absorption_ratio'] = [
			'#type' => 'number',
			'#min_value' => 0,
			'#step' => 0.01, // Float
			'#title' => $this->t('Sodium Absorption Ratio'),
			'#description' => '(Unit meq/L)',
			'#default_value' => $field_shmu_irrigation_sodium_adsorption_ratio_value,
			'#required' => FALSE
		];

		$field_shmu_irrigation_total_dissolved_solids_value = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($irrigation, 'field_shmu_irrigation_total_dissolved_solids'): '';

		$form['field_shmu_irrigation_total_dissolved_solids'] = [
			'#type' => 'number',
			'#min_value' => 0,
			'#max_value' => 1000000, // Capped at 1 million because you can't have more than 1 million parts per million
			'#step' => 0.01, // Float
			'#title' => $this->t('Total Dissolved Solids'),
			'#description' => '(Unit ppm)',
			'#default_value' => $field_shmu_irrigation_total_dissolved_solids_value,
			'#required' => FALSE
		];


		$field_shmu_irrigation_total_alkalinity_value = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($irrigation, 'field_shmu_irrigation_total_alkalinity'): '';

		$form['field_shmu_irrigation_total_alkalinity'] = [
			'#type' => 'number',
			'#min_value' => 0,
			'#max_value' => 1000000, // Capped at 1 million because you can't have more than 1 million parts per million
			'#step' => 0.01, // Float
			'#title' => $this->t('Total Alkalinity'),
			'#description' => '(Unit ppm CaCO3)',
			'#default_value' => $field_shmu_irrigation_total_alkalinity_value,
			'#required' => FALSE
		];

		$field_shmu_irrigation_chlorides_value = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($irrigation, 'field_shmu_irrigation_chlorides'): '';

		$form['field_shmu_irrigation_chlorides'] = [
			'#type' => 'number',
			'#min_value' => 0,
			'#max_value' => 1000000, // Capped at 1 million because you can't have more than 1 million parts per million
			'#step' => 0.01, // Float
			'#title' => $this->t('Chlorides'),
			'#description' => '(Unit ppm)',
			'#default_value' => $field_shmu_irrigation_chlorides_value,
			'#required' => FALSE
		];
		$field_shmu_irrigation_sulfates_value = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($irrigation, 'field_shmu_irrigation_sulfates'): '';
		$form['field_shmu_irrigation_sulfates'] = [
			'#type' => 'number',
			'#min_value' => 0,
			'#max_value' => 1000000, // Capped at 1 million because you can't have more than 1 million parts per million
			'#step' => 0.01, // Float
			'#title' => $this->t('Sulfates'),
			'#description' => '(Unit ppm)',
			'#default_value' => $field_shmu_irrigation_sulfates_value,
			'#required' => FALSE
		];

		$field_shmu_irrigation_nitrates_value = $is_edit ? $this-> getDecimalFromSHMUFractionFieldType($irrigation, 'field_shmu_irrigation_nitrates'): '';

		$form['field_shmu_irrigation_nitrates'] = [
			'#type' => 'number',
			'#min_value' => 0,
			'#max_value' => 1000000, // Capped at 1 million because you can't have more than 1 million parts per million
			'#step' => 0.01, // Float
			'#title' => $this->t('Nitrates'),
			'#description' => '(Unit ppm)',
			'#default_value' => $field_shmu_irrigation_nitrates_value,
			'#required' => FALSE
		];

        $form['actions']['save'] = [
			'#type' => 'submit',
			'#value' => $this->t('Save'),

		];
		$form['cancel'] = [
			'#type' => 'submit',
			'#value' => $this->t('Cancel'),
			'#submit' => [[$this, 'cancelSubmit']],
			'#limit_validation_errors' => array(),
		];

		if($is_edit){
			$form['delete'] = [
				'#type' => 'submit',
				'#value' => $this->t('Delete'),
				'#submit' => [[$this, 'deleteSubmit']],
				'#limit_validation_errors' => array(),
			];
		}

        return $form;
    }

	public function cancelSubmit(array &$form, FormStateInterface $form_state) {
		$form_state->setRedirect('cig_pods.awardee_dashboard_form');
		return;
	}

	public function deleteSubmit(array &$form, FormStateInterface $form_state) {
		$id = $form_state->get('irrigation_id'); // TODO: Standardize access
		$irrigation = \Drupal::entityTypeManager()->getStorage('asset')->load($id);

		try{
			$irrigation->delete();
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
	public function getFormId() {

		return 'irrigation_form';
	}

    /**
	* {@inheritdoc}
	*/
	public function submitForm(array &$form, FormStateInterface $form_state) {

        $is_edit = $form_state->get('operation') == 'edit';
		$ignored_fields = ['send','form_build_id','form_token','form_id','op','actions', 'delete', 'cancel'];
		$date_fields = ['field_shmu_irrigation_sample_date'];
		$form_values = $form_state->getValues();

        if(!$is_edit){
			$irrigation_template = [];
			$irrigation_template['type'] = 'irrigation';
			// dpm($irrigation_template);
			// dpm("------------");
			$irrigation = Asset::create($irrigation_template);
		} else {
			// Operation is of type Edit
			$id = $form_state->get('irrigation_id'); // TODO: Standardize access
			$irrigation = \Drupal::entityTypeManager()->getStorage('asset')->load($id);
		}

    // Set the irrigation asset name to "Irrigation {{asset-id}}".
    $irrigation->set('name', 'Irrigation ' . $id);

        foreach($form_values as $key => $value){
			// If it is an ignored field, skip the loop
			if(in_array($key, $ignored_fields)){ continue; }
			if(in_array($key,$date_fields)){
				// $value is expected to be a string of format yyyy-mm-dd
				$irrigation->set( $key, strtotime( $value ) ); //Set directly on SHMU object
				continue;
			}

            $irrigation->set( $key, $value );
        }

		$irrigation->save();

		$this->setProjectReference($irrigation, $irrigation->get('shmu')->target_id);
		// Success message done

		$form_state->setRedirect('cig_pods.awardee_dashboard_form');
    }

	public function setProjectReference($assetReference, $shmuReference){
		$shmu = \Drupal::entityTypeManager()->getStorage('asset')->load($shmuReference);
		$project = \Drupal::entityTypeManager()->getStorage('asset')->load($shmu->get('project')->target_id);
		$assetReference->set('project', $project);
		$assetReference->save();
	}
}