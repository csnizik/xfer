<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;
Use Drupal\Core\Render\Element\Checkboxes;



class LabTestingProfileForm extends FormBase {


   /**
   * {@inheritdoc}
   */
	public function buildForm(array $form, FormStateInterface $form_state, $options = NULL){
		
		$form['profile_name'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Profile Name'),
			'#description' => 'Profile Name',
			'#required' => TRUE,
		];


		# EntityInterface []
		$laboratory_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
			[
				'vid' => 'd_laboratory',
			]
		);
		
		$producer_assets = \Drupal::entityTypeManager() -> getStorage('asset')-> loadByProperties(
			[
				'type' => 'producer',
			]
		);


		$lab_options = array();
		$lab_keys = array_keys($laboratory_terms);
		foreach($lab_keys as $lab_key){
			$term = $laboratory_terms[$lab_key];
			$lab_options[$lab_key] = $term -> getName();		
		}

		$producer_options = array();
		$producer_keys = array_keys($producer_assets);

		foreach($producer_keys as $producer_key){
			$producer = $producer_assets[$producer_key];
			$producer_options[$producer_key] = $producer -> getName();		
		}

		$form['laboratory'] = array(
			'#type' => 'checkboxes',
			'#options' => $lab_options,
			'#title' => $this->t('Laboratory'),
		);

		$form['producer'] = array (
			'#type' => 'checkboxes',
			'#options' => $producer_options,
			'#title' => $this->t('Producer'),
		);

		
		$form['actions']['send'] = array(
			'#type' => 'submit',
			'#value' => $this->t('Send'),
		);

		
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
  public function submitForm(array &$form, FormStateInterface $form_state) {

	
	$checked_labs = Checkboxes::getCheckedCheckboxes($form_state -> getValue('laboratory'));
	$checked_producers = Checkboxes::getCheckedCheckboxes($form_state -> getValue('producer'));

	  
	$asset = Asset::create([
		'type' => 'lab_testing_profile',
		'name' => $form['profile_name']['#value'],
		'laboratory' =>  $checked_labs,
		'field_producer' => $checked_producers,
	]);
	// $asset = Asset::create([
		// 'type' => 'lab_testing_profile',
		// 'name' => $form['profile_name']['#value'],
	// ]);

	$asset->save();
	
	$this
	  ->messenger()
	  ->addStatus($this
	  ->t('Form submitted for Profile @profile_name', [
	  '@profile_name' => $form['profile_name']['#value'],
	]));
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'lab_testing_profile_form';
  }
}