<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;
Use Drupal\Core\Url;



class FieldAssessmentForm extends FormBase {


   /**
   * {@inheritdoc}
   */
	public function buildForm(array $form, FormStateInterface $form_state, $id = NULL){
		$producer = [];

		dpm("up to date 1");

		if($form_state->get('calculated_value') == NULL ) {
			$form_state->set('calculated_value', '');
		} else {
			dpm($form_state->get('calculated_value'));
			dpm("Calculated value is defined");
		}

		$is_edit = $id <> NULL;



		$form['#tree'] = True;

		$form['#attached']['library'][] = 'cig_pods/producer_form';

		$form['producer_title'] = [
			'#markup' => '<h1>Field Assesment </h1>',
		];

		$form['assessment_wrapper'] = [
			'#prefix' => '<div id="assessment_wrapper">',
			'#suffix' => '</div>',
		];



		$form['assessment_wrapper']['field_soil_cover'] = [
			'#type' => 'select',
			'#title' => $this->t('Soil cover'),
			'#options' => ['a','b','c'],
			'#required' => TRUE
			
			// '#submit' = > ['']
			// '#ajax' => [
				// 'wrapper'
				// 'callback'
			// ],
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
			dpm("it goes brrr");
			$default_calculated_field = $form_state->get('calculated_value');
			dpm(gettype($default_calculated_field));
		}
		dpm("calculated field");
		dpm($default_calculated_field);

		if($default_calculated_field <> ''){
			$form['assessment_wrapper']['calculated_field'] = [
				'#type' => 'textfield',
				'#title' => 'Calculated Value',
				'#required' => FALSE,
				'#value' => $form_state->get('calculated_value'),
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
/**
 * Deletes the producer that is currently being viewed.
 */
public function deleteProducer(array &$form, FormStateInterface $form_state){

	// TODO: we probably want a confirm stage on the delete button. Implementations exist online
	$producer_id = $form_state->get('producer_id');
	$producer = \Drupal::entityTypeManager()->getStorage('asset')->load($producer_id);

	$producer->delete();

	$form_state->setRedirect('cig_pods.awardee_dashboard_form');

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
	$form_values = $form_state->getValues();
	$val = $form_values['assessment_wrapper']['field_soil_cover'];
	// $form_state->set('')
	switch($val){
		case 0:
			$form_state->set('calculated_value', 'a');
			break;
		case 1:
			$form_state->set('calculated_value', 'b');
			break;
		case 2:
			$form_state->set('calculated_value', 'c');
			break;
	}
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