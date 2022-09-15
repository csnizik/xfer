<?php

namespace Drupal\cig_pods\Form;

use Drupal\asset\Entity\AssetInterface;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;

class AwardeeOrganizationInfoForm extends PodsFormBase {

	private function getStateTerritoryOptions(){
    $options = $this->entityOptions('taxonomy_term', 'd_state_territory');
    return ['' => '- Select -'] + $options;
  }

   /**
   * {@inheritdoc}
   */
	public function buildForm(array $form, FormStateInterface $form_state, AssetInterface $asset = NULL){
    $awardee = $asset;
		$organization_state_territory = $this->getStateTerritoryOptions();
		$is_edit = $awardee <> NULL;

		if($is_edit){
			$form_state->set('operation','edit');
			$form_state->set('awardee_id',$awardee->id());
		} else {
			$form_state->set('operation','create');
		}


		$form['#attached']['library'][] = 'cig_pods/awardee_organization_form';

		$form['form_title'] = [
			'#markup' => '<h1 id="form-title">Awardee Organization Information</h1>'
		];

		$awardee_org_default_name = $is_edit ? $awardee->get('name')->value : '';
		$form['name'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Awardee Organization Name'),
			'#required' => TRUE,
			'#default_value' => $awardee_org_default_name,
		];

		$awardee_org_deault_short_name = $is_edit ? $awardee->get('organization_short_name')->value : '';
		$form['organization_short_name'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Awardee Organization Short Name'),
			'#required' => TRUE,
			'#default_value' => $awardee_org_deault_short_name,
		];

		$awardee_org_deault_acronym = $is_edit ? $awardee->get('organization_acronym')->value : '';
		$form['organization_acronym'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Awardee Organization Acronym'),
			'#required' => TRUE,
			'#default_value' =>  $awardee_org_deault_acronym,
		];

		$awardee_org_default_state_territory = $is_edit ? $awardee->get('organization_state_territory')->target_id : NULL;
		$form['organization_state_territory'] = [
			'#type' => 'select',
			'#title' => 'Awardee Organization State Or Territory',
			'#options' => $organization_state_territory,
			'#required' => TRUE,
            '#default_value' => $awardee_org_default_state_territory,
		];

		$form['actions']['save'] = [
			'#type' => 'submit',
			'#value' => $this->t('Save'),
		];

		$form['actions']['cancel'] = [
			'#type' => 'submit',
			'#value' => $this->t('Cancel'),
			'#limit_validation_errors' => '',
			'#submit' => ['::dashboardRedirect'],
		];

		if($is_edit){
			$form['actions']['delete'] = [
				'#type' => 'submit',
				'#value' => $this->t('Delete'),
				'#submit' => ['::deleteAwardee'],
			];
		}


		return $form;
	}

	public function dashboardRedirect(array &$form, FormStateInterface $form_state){
		$form_state->setRedirect('cig_pods.admin_dashboard_form');
	}

	public function deleteAwardee(array &$form, FormStateInterface $form_state){

		// TODO: we probably want a confirm stage on the delete button. Implementations exist online
		$awardee_id = $form_state->get('awardee_id');
		$awardee = \Drupal::entityTypeManager()->getStorage('asset')->load($awardee_id);

		try{
			$awardee->delete();
			$form_state->setRedirect('cig_pods.admin_dashboard_form');
		}catch(\Exception $e){
			$this
		  ->messenger()
		  ->addError($this
		  ->t($e->getMessage()));
		}

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

	$is_create = $form_state->get('operation') === 'create';

	if($is_create){
		$awardee_submission = [];
		$awardee_submission['name'] = $form_state -> getValue('name');
		$awardee_submission['organization_acronym'] = $form_state -> getValue('organization_acronym');
		$awardee_submission['organization_short_name'] = $form_state -> getValue('organization_short_name');
		$awardee_submission['organization_state_territory'] = $form_state -> getValue('organization_state_territory');
		$awardee_submission['type'] = 'awardee';

		$awardee = Asset::create($awardee_submission);
		$awardee->save();

		$form_state->setRedirect('cig_pods.admin_dashboard_form');
	} else {
		$awardee_id = $form_state->get('awardee_id');
		$awardee = \Drupal::entityTypeManager()->getStorage('asset')->load($awardee_id);

		$awardee_name = $form_state->getValue('name');
		$awardee_short_name = $form_state->getValue('organization_short_name');
		$awardee_state_territory = $form_state->getValue('organization_state_territory');
		$awardee_acronym = $form_state->getValue('organization_acronym');

		$awardee->set('name', $awardee_name);
		$awardee->set('organization_short_name', $awardee_short_name);
		$awardee->set('organization_state_territory', $awardee_state_territory);
		$awardee->set('organization_acronym', $awardee_acronym);

		$awardee->save();
		$form_state->setRedirect('cig_pods.admin_dashboard_form');

	}


  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'awardee_org_create_form';
  }
}