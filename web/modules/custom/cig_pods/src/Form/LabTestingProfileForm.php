<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;



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

		
		// print_r(array_keys($laboratory_terms));
		// foreach($laboratory_terms as $lab_term){
			// print_r($laboratory_terms);
		// }		
		// print($laboratory_terms[$lab_keys[0]] -> getName());
		// $lab_options = array();
		// $term = $laboratory_terms[1484];
		// print_r("array keys");
		// $properties = array_keys(get_object_vars($term));
		// print_r(gettype($properties));
		// print_r(array_keys($properties));
		// print_r("array keys end");
		// $lab_options['1484'] = 'Western Agricultural...';
		
		// print_r($laboratory_terms[1484]);
		$lab_keys = array_keys($laboratory_terms);
		foreach($lab_keys as $lab_key){
			$term = $laboratory_terms[$lab_key];
			$lab_options[(string) $lab_key] = $term -> getName();		
		}

		// print_r($lab_options);
		// foreach($laboratory_terms as $term){
		// 	print_r($term);
		// }

		// foreach($laboratory_terms as $term){
		// 	print_r($term-> tid);
		// }


		// foreach($laboratory_terms as $lab_term) {
			// $lab_options[$lab_term -> tid] = $lab_term -> label;
		// }

		$lab_options['option_a'] = '<b> Option A </b>';
		$lab_options['option_b'] = 'Option B';
		$lab_options['option_c'] = 'Option C';


		$form['laboratories'] = array(
			'#type' => 'select',
			'#options' => $lab_options,
			'#title' => $this->t('Laboratories'),
			
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

  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'lab_testing_profile_form';
  }
}