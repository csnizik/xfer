<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;

class TaxonomyWinnowingForm extends FormBase {

	


	/**
	* {@inheritdoc}
	*/
	public function buildForm(array $form, FormStateInterface $form_state, $options = NULL){
		// dpm('hello 2');

		// $form['#tree'] = True;

		$form['select_wrapper'] = [
			'#prefix' => '<div id="select_wrapper">',
			'#suffix' => '</div>',
		];
		if( $form_state -> get('valid_values_for_select_2') == NULL ){
			$form_state -> set('valid_values_for_select_2', []);
		}

		// Selecting input category
		$select_1_options = ['a'=> 'a','b' => 'b','c' => 'c'];

		$form['select_wrapper']['select_1'] = [
			'#type' => 'select',
			'#title' => $this->t('Select'),
			'#options' => $select_1_options,
			'#required' => TRUE,

		]; 

		$form['select_wrapper']['actions']['winnow'] = [
			'#type' => 'submit',
			'#submit' => ['::winnowOptions'],
			'#ajax' => [
				'callback' => "::winnowOptionsCallback",
				'wrapper' => 'select_wrapper',
			],
			'#value' => 'Winnow Inputs',
			'#limit_validation_errors' => '',
		]; 

		// Selecting input
		// $select_2_options = ['apple','banana','citrus','apricot','boo'];

		$valid_values_for_select_2 = $form_state->get('valid_values_for_select_2');
		// $valid_values_for_select_2  = ['apple' => 'apple'];
		$form['select_wrapper']['select_2'] = [
			'#type' => 'select',
			'#title' => $this->t('Select'),
			'#options' => $valid_values_for_select_2,
			'#required' => TRUE,
		];
		
		$form['select_wrapper']['actions']['save'] = [
			'#type' => 'submit',
			'#value' => 'Save'
		]; 


		return $form;

	}


	public function winnowOptions(array &$form, FormStateInterface $form_state){
		// dpm('winnowOptions called');


		// Mapping will be a mapping betweeen Input Category Taxonomy IDs and Input Taxonomy IDs.

		$mapping =[
			'a' => [
				1 => 'apple',
				2 => 'appricot',
			],
			'b' => [
				3 => 'banana',
				4 => 'boo',
			],
			'c' => [
				5 => 'citrus',
			],
		];
		// $form_values = $form_
		// dpm('fv');
		// dpm($form_values);
		$select_1_value = $form['select_wrapper']['select_1']['#value'];

		$valid_values_for_select_2 = $mapping[$select_1_value];

		$form_state->set('valid_values_for_select_2', $valid_values_for_select_2);

		$form_state->setRebuild(True);



	}

	public function winnowOptionsCallback(array &$form, FormStateInterface $form_state){
		return $form['select_wrapper'];
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
		return '';
	}

	/**
	* {@inheritdoc}
	*/
	public function submitForm(array &$form, FormStateInterface $form_state) {
		// dpm("brr");
		$this
			->messenger()
			->addStatus($this
			->t('Form submitted for  @_name', [
			'@_name' => $form['_name']['#value'],
		]));
	}
}