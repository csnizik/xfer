<?php


namespace Drupal\cig_pods\Form;

use Drupal\asset\Entity\Asset;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * {@inheritdoc}
 */
class ProjectForm extends Formbase {

	/**
	* {@inheritdoc}
	*/
	public function buildForm(array $form, FormStateInterface $form_state, $options = NULL){
		// print_r("brr2");


		$form['project_name'] = [
            '#type' => 'textfield',
            '#title' => t('Project Name'),
            '#required' => TRUE,
            '#multiple' => FALSE,			
		];

		$form['project_agreement_number'] = [
			'#type' => 'textfield',
			'#title' => t('Project Agreement Number'),
			'#required' => FALSE,
			'#multiple' => FALSE,
		];
		
	
		$form['actions']['send'] = array(
			'#type' => 'submit',
			'#value' => t('Send'),
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
	public function getFormId() {
		return 'project_form';
	}

	/**
	* {@inheritdoc}
	*/
	public function submitForm(array &$form, FormStateInterface $form_state) {
		$this
			->messenger()
			->addStatus($this
			->t('Form submitted for project @project_name', [
			'@project_name' => $form['project_name']['#value'],
		]));
	}
}