<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;

class AwardeeOrganizationInfoForm extends FormBase {

   /**
   * {@inheritdoc}
   */
	public function buildForm(array $form, FormStateInterface $form_state, $options = NULL){

		$form['#attached']['library'][] = 'cig_pods/project_entry_form';

		$form['form_title'] = [
			'#markup' => '<h1 id="form-title">Awardee Organization Information</h1>'
		];

		$form['awardee_org_name'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Awardee Organization Name'),
			'$description' => 'Awardee Organization Name',
			'#required' => TRUE
		];

		$form['awardee_org_short_name'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Awardee Organization Short Name'),
			'$description' => 'Awardee Organization Short Name',
			'#required' => TRUE
		];

		$form['awardee_org_acronym'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Awardee Organization Acronym'),
			'$description' => 'Awardee Organization Acronym',
			'#required' => TRUE
		];

		$form['awardee_stt_directory'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Awardee Organization State or Territory '),
			'$description' => 'Awardee Organization State or Territory ',
			'#required' => TRUE
		];

		$form['actions']['button'] = [
			'#type' => 'button',
			'#value' => $this->t('Save'),
			'#attributes' => array('onClick' => 'window.location.href="project"'),
		];

		$form['actions']['cancel'] = [
			'#type' => 'button',
			'#value' => $this->t('Cancel'),
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

	
	// $this
	// ->messenger()
	// ->addStatus($this
	// ->t('Form submitted for awardee org info @org_name', [
	// '@org_name' => $form['awardee_org_name']['#value'],
	// ]));
	
	
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'awardee_org_create_form';
  }
}