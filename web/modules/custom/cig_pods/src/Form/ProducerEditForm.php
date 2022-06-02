<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
use Drupal\farm_entity\Plugin\Asset;

class ProducerEditForm extends FormBase {


   /**
   * {@inheritdoc}
   */
	public function buildForm(array $form, FormStateInterface $form_state, $options = NULL, $id = NULL){

		$asset = \Drupal::entityTypeManager()->getStorage('asset')->load($id);
		// echo "ID is: ", $asset->get('id')->value;
		// echo "asset: ", $asset->get('name')->value;
		$form['producer_name'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Producer Name'),
			'#description' => 'Producer Name',
			'#default_value' => $this->t($asset->get('name')->value),
			'#required' => TRUE
		];

		$form['actions']['save'] = [
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
    return 'producer_edit_form';
  }
}