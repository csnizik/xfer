<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;

class AwardeeOrganizationInfoForm extends FormBase {

	private function getStateTerritoryOptions($bundle){
        $state_territory_options = [];
        $state_territory_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
            [
                'vid' => $bundle,
            ]
        );
        $state_territory_keys = array_keys($state_territory_terms);
        foreach($state_territory_keys as $state_territory_key){
            $term = $state_territory_terms[$state_territory_key];
            $state_territory_options[$state_territory_key] = $term -> getName();
        }
        return $state_territory_options;
    }

	private function pageLookup(string $path) {
        $match = [];
        $path2 =  $path;
        $router = \Drupal::service('router.no_access_checks');

        try {
            $match = $router->match($path2);
        }
        catch (\Exception $e) {
        }
        return $match['_route'];
    }

   /**
   * {@inheritdoc}
   */
	public function buildForm(array $form, FormStateInterface $form_state, $id = NULL){
		$awardee = [];
		$organization_state_territory = $this->getStateTerritoryOptions("d_state_territory");
		$is_edit = $id <> NULL;

		if($is_edit){
			$form_state->set('operation','edit');
			$form_state->set('awardee_id',$id);
			$awardee = \Drupal::entityTypeManager()->getStorage('asset')->load($id);
		} else {
			$form_state->set('operation','create');
		}


		$form['#attached']['library'][] = 'cig_pods/project_entry_form';
		// $form['#attributes']['novalidate'] = 'novalidate';

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
		$form_state->setRedirect('cig_pods.awardee_dashboard_form');
	}

	public function deleteAwardee(array &$form, FormStateInterface $form_state){

		// TODO: we probably want a confirm stage on the delete button. Implementations exist online
		$awardee_id = $form_state->get('awardee_id');
		$awardee = \Drupal::entityTypeManager()->getStorage('asset')->load($awardee_id);

		$awardee->delete();

		$form_state->setRedirect('cig_pods.awardee_dashboard_form');

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

		$form_state->setRedirect($this->pageLookup('/assets/awardee'));
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
		$form_state->setRedirect($this->pageLookup('/assets/awardee'));

	}


  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'awardee_org_create_form';
  }
}