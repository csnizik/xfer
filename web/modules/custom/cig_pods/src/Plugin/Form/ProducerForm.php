<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;

class ProducerForm extends FormBase {


	public function __construct() {

	}
   /**
   * {@inheritdoc}
   */
	public function buildForm(array $form, FormStateInterface $form_state, $options = NULL){
		$form['name'] = [
			'#type' => 'text',
			'#title' => $this->t(Name),
			'$description' => NULL,
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
  public function getFormId() {
    return 'producer_create_form';
  }

}