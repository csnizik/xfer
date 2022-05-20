<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;


class AwardeeForm extends FormBase {


   /**
   * {@inheritdoc}
   */
	public function buildForm(array $form, FormStateInterface $form_state, $options = NULL){

		// Add textfield as name
		$form['awardee_name'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Awardee Name'),
			'#description' => 'Awaree Name',
			'#required' => TRUE,
		];

		$form['project_name'] = array(
            'type' => 'textfield',
            'label' => 'Project Name',
            'description' => '' ,
            'required' => FALSE ,
            'multiple' => FALSE			
		);

		$form['project_agreement_number'] = array(
			'type' => 'textfield',
            'label' => 'Project Agreement Number',
            'description' => '' ,
            'required' => FALSE ,
            'multiple' => FALSE	
		);

		// $form['project'] = array (
			// '#type' => 'checkboxes',
			// '#options' => $project_options,
			// '#title' => $this->t('Projects'),
		// );

		// Add submit button
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
	
	$this
	  ->messenger()
	  ->addStatus($this
	  ->t('Form submitted for awardee @awardee_name', [
	  '@awardee_name' => $form['awardee_name']['#value'],
	]));

	$project = Asset::create([
		'type' => 'project',
		'name' => $form['project_name']['#value'],
		'field_project_agreement_number' => $form['project_agreement_number']['#value'],
	]);

	print_r($project->id());

	$project->save();

	print_r($project->id());

	$awardee = Asset::create([
		'type' => 'awardee',
		'name' => $form['awardee_name']['#value'],
		'project' => [$project->id()],
	]);

	// $asset = Asset::create([
		// 'type' => 'awardee',
		// 'name' => $form['awardee_name']['#value']
	// ]);

	// $asset->save();
	

  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'awardee_create_form';
  }
}