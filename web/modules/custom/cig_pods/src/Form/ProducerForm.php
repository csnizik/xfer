<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;

class ProducerForm extends FormBase {


   /**
   * {@inheritdoc}
   */
	public function buildForm(array $form, FormStateInterface $form_state, $options = NULL){
		$form['producer_name'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Producer Name'),
			'$description' => 'Producer Name',
			'#required' => TRUE
		];
		
		$form['actions']['send'] = [
			'#type' => 'submit',
			'#value' => $this->t('Send'),
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
  public function submitForm(array &$form, FormStateInterface $form_state) {
	$this
	  ->messenger()
	  ->addStatus($this
	  ->t('Form submitted for producer @producer_name', [
	  '@producer_name' => $form['producer_name']['#value'],
	]));
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'producer_create_form';
  }
}