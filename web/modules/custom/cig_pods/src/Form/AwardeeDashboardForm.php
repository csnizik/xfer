<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;

class AwardeeDashboardForm extends FormBase {

    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state, $options = NULL){

    $form['form_title'] = [
        '#markup' => '<h1 id="form-title">Dashboard</h1>'
    ]; 

    $form['entities_fieldset'][$i]['create_new'] = [
				'#type' => 'select',
				// '#title' => $this
				//   ->t("Create New"),
				'#options' => [
				  '' => $this
					->t('Create New'),
				  'pr' => $this
					->t('Producer(s)'),
				  'awo' => $this
					->t('Awardee Org'),
				  'proj' => $this
					->t('Project'),
                  'ltm' => $this
					->t('Lab Test Method'),
                ],
				'attributes' => [
					'class' => 'something',
				],
				'#prefix' => ($num_lines > 1) ? '<div class="inline-components-short">' : '<div class="inline-components">',
		  		'#suffix' => '</div>',
			];

    $form['form_body'] = [
        '#markup' => '<p id="form-body">Let\'s get started, you can create and manage Awardees, Projects, Lab Test Methods and Producers using this tool.</p>'
    ]; 

    $form['form_subtitle'] = [
        '#markup' => '<h2 id="form-subtitle">Manage Assets</h2>'
    ]; 

// $query = \Drupal::entityQuery('asset')
//         ->condition('type', 'project');
// $count = $query->count()->execute();

    $form['awardee_proj'] = [
      '#type' => 'button',
      '#value' => $this->t('Project(s)'),
      '#attributes' => array('onClick' => ''),
    ]; 

    $form['awardee_org'] = [
      '#type' => 'button',
      '#value' => $this->t('Awardee Organization(s)'),
      '#attributes' => array('onClick' => ''),
    ]; 

    $form['awardee_prod'] = [
      '#type' => 'button',
      '#value' => $this->t('Producer(s)'),
      '#attributes' => array('onClick' => ''),
    ]; 

    $form['awardee_lab'] = [
      '#type' => 'button',
      '#value' => $this->t('Lab Test Method(s)'),
      '#attributes' => array('onClick' => ''),
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
    public function getFormId() {
        return 'awardee_dashboard';
    }

    /**
    * {@inheritdoc}
    */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $this
            ->messenger()
            ->addStatus($this
            ->t('Form submitted for  @_name', [
            '@_name' => $form['_name']['#value'],
        ]));
    }
}