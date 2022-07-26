<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;


class ExampleMapForm extends FormBase {

	/**
	* {@inheritdoc}
	*/
	public function buildForm(array $form, FormStateInterface $form_state, $id = NULL){

		dpm("up to date");
		$is_edit = $id <> NULL;

		if($is_edit){
			$shmu = Asset::load($id);
			$form_state->set('operation','edit');
		} else {
			$form_state->set('operation','create');
		}


		$map_value = '';
		if($is_edit) $map_value = $shmu->get('field_geofield')->value;
		if($form_state->get('map_reset') == TRUE) {
			$map_value = '';
			$form_state->set('map_reset', FALSE);
		}
		$form['mymap'] = [
			'#type' => 'farm_map_input',
			'#map_type' => 'default',
			// '#value' => $map_value
			// '#map_settings' => [
			//   'mysetting' => 'myvalue',
			// ],
			// '#behaviors' => [
			//   'mybehavior',
			// ],
		  ];

		$form['actions']['reset_map'] = [
			'#type' => 'submit',
			'#value' => $this->t('Reset Map'),
			'#submit' => '::resetMap',
			'#ajax' => [
				'callback' => ['::resetMapCallback'],
				'wrapper' => 'example-map-form',
			]
		]; 
		$form['actions']['submit'] = [
			'#type' => 'submit',
			'#value' => $this->t('Submit'),
		]; 


		return $form;

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
		return 'example_map_form';
	}

	/**
	* {@inheritdoc}
	*/
	public function submitForm(array &$form, FormStateInterface $form_state) {
		
		$form_values = $form_state->getValues();

		$wkt_value = $form_values['mymap'];

		$shmu_template = [];
		$shmu_template['name'] = 'Test SHMU with geodata';
		$shmu_template['type'] = 'soil_health_management_unit';
		$shmu = Asset::create($shmu_template);

		$shmu->set('field_geofield', $wkt_value);
		

		$shmu->save();
		$this
			->messenger()
			->addStatus($this
			->t('Form submitted for  @_name', [
			'@_name' => $form['_name']['#value'],
		]));
	}

	public function resetMap(array &$form, FormStateInterface $form_state) {
		dpm("resetMap called");
		$form_state->set('map_reset', True);
		$form_state->setRebuild(True);

	}

	public function resetMapCallback(array &$form, FormStateInterface $form_state) {
		return $form;
	}

}

