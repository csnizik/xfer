<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
Use Drupal\asset\Entity\Asset;
Use Drupal\Core\Url;



class ProducerForm extends FormBase {


   /**
   * {@inheritdoc}
   */
	public function buildForm(array $form, FormStateInterface $form_state, $id = NULL){
		$producer = [];

		$is_edit = $id <> NULL;

		if($is_edit){
			$form_state->set('operation','edit');
			$form_state->set('producer_id',$id);
			$producer = \Drupal::entityTypeManager()->getStorage('asset')->load($id);
		} else {
			$form_state->set('operation','create');
		}




		$form['#attached']['library'][] = 'cig_pods/producer_form';

		$form['producer_title'] = [
            '#markup' => '<h1>Producer Information</h1>',
        ];

		$producer_first_name_default_value = $is_edit ?  $producer->get('field_producer_first_name')->value : '';
		$form['field_producer_first_name'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Producer First Name'),
			'#required' => TRUE, // Do Not Change
			'#default_value' => $producer_first_name_default_value,
		];

		$producer_last_name_default_value = $is_edit ?  $producer->get('field_producer_last_name')->value : '';
		$form['field_producer_last_name'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Producer Last Name'),
			'#required' => TRUE, // Do Not Change
			'#default_value' => $producer_last_name_default_value,
		];

		$button_save_label = $is_edit ? $this->t('Save Changes') : $this->t('Save');
		$form['actions']['save'] = [
			'#type' => 'submit',
			'#value' => $button_save_label,
		  ];

		$form['actions']['cancel'] = [
			'#type' => 'submit',
			'#value' => $this->t('Cancel'),
			// '#attributes' => array('onClick' => 'window.location.href="/dashboard"'),
			'#submit' => ['::dashboardRedirect'],

		];


		if($is_edit){
			$form['actions']['delete'] = [
				'#type' => 'submit',
				'#value' => $this->t('Delete'),
				'#submit' => ['::deleteProducer'],
				// '#prefix' => '<div class="remove-button-container">',
				// '#suffix' => '</div>',
			];
		}


		return $form;
	}

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state){
	return;
}
/**
 * Deletes the producer that is currently being viewed.
 */
public function deleteProducer(array &$form, FormStateInterface $form_state){

	// TODO: we probably want a confirm stage on the delete button. Implementations exist online
	$producer_id = $form_state->get('producer_id');
	$producer = \Drupal::entityTypeManager()->getStorage('asset')->load($producer_id);

	$producer->delete();

	$form_state->setRedirect('cig_pods.awardee_dashboard_form');

}

public function dashboardRedirect(array &$form, FormStateInterface $form_state){
	$form_state->setRedirect('cig_pods.awardee_dashboard_form');
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

	$is_create = $form_state->get('operation') === 'create';
	// dpm(gettype($operation));

	// PHP: '1' == 1 is True but '1' === 1 is False

	if($is_create){
		$producer_submission = [];
		$producer_submission['field_producer_first_name'] = $form_state -> getValue('field_producer_first_name');
		$producer_submission['field_producer_last_name'] = $form_state -> getValue('field_producer_last_name');
		$producer_submission['type'] = 'producer';
		$producer_submission['name'] = $producer_submission['field_producer_first_name']." ".$producer_submission['field_producer_last_name'];

		$producer = Asset::create($producer_submission);
		$producer->save();

		$form_state->setRedirect('cig_pods.awardee_dashboard_form');
	} else {
		$id = $form_state->get('producer_id');
		$producer = \Drupal::entityTypeManager()->getStorage('asset')->load($id);

		$fn = $form_state->getValue('field_producer_first_name');
		$ln = $form_state->getValue('field_producer_last_name');
		$full_n = $fn." ".$ln;

		$producer->set('field_producer_first_name', $fn);
		$producer->set('field_producer_last_name', $ln);
		$producer->set('name', $full_n);

		$producer->save();
		$form_state->setRedirect('cig_pods.awardee_dashboard_form');

	}
	// Have to identify whether it is an edit or it is a create new

	// $asset = Asset::create([
	// 	'type' => 'producer',
	// 	'name' => $form['producer_name']['#value']
	// ]);

	// $asset->save();


  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'producer_create_form';
  }
}