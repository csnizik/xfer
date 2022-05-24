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
			'#title' => t('Awardee Name'),
			'#required' => TRUE,
			'#muliple' => FALSE,
		];

		$form['project_details'] = [
			'#type' => 'details',
			'#title' => 'My details',
			'#open' => TRUE,
		];
		// print_r("brr");

		$proj_form = ProjectForm::buildForm([], $form_state, $options);

		// print_r(array_keys($form));

		foreach($proj_form as $key => $element){
			if ($key == 'actions'){
				continue;
			} else {
				$form['project_details'][$key] = $element;
				// Add each element in the form 
				// print_r($key);
			}
		}


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
	


	$project = Asset::create([
		'type' => 'project',
		'name' => $form['project_details']['project_name']['#value'],
		'field_project_agreement_number' => $form['project_details']['project_agreement_number']['#value'],
	]);

	$project->save();


	$awardee = Asset::create([
		'type' => 'awardee',
		'name' => $form['awardee_name']['#value'],
		'field_project' => [$project->id()],
	]);

	$awardee->save();

	$this
	  ->messenger()
	  ->addStatus($this
	  ->t('Form submitted for awardee @awardee_name', [
	  '@awardee_name' => $form['awardee_name']['#value'],
	]));

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