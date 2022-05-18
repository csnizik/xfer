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
		
		$lab_keys = array_keys($laboratory_terms);
		foreach($lab_keys as $lab_key){
			$term = $laboratory_terms[$lab_key];
			$lab_options[$lab_key] = $term -> getName();		
		}

		$form['laboratory'] = array(
			'#type' => 'checkboxes',
			'#options' => $lab_options,
			'#title' => $this->t('Laboratory'),
		);

		
		$form['actions']['send'] = array([
			'#type' => 'submit',
			'#value' => $this->t('Send'),
		  ]);

		
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

	
	  $checked = Checkboxes::getCheckedCheckboxes($form_state -> getValue('laboratory'));
	  foreach($checked as $elem){
		  print_r($elem);
		}
		
	$asset = Asset::create([
		'type' => 'lab_testing_profile',
		'name' => $form['profile_name']['#value'],
		'laboratory' =>  $checked[0],
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