<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;

class AwardeeDashboardForm extends FormBase {


   /**
   * {@inheritdoc}
   */
	public function buildForm(array $form, FormStateInterface $form_state, $options = NULL){

    $form['#attached']['library'][] = 'cig_pods/awardee_dashboard_form';

    $form['h2'] = [
      '#markup' => 'Dashboard',
  	  '#prefix' => '<div id="title">',
      '#suffix' => '</div>',
    ];

    $form['entities_fieldset'][$i]['create_new'] = [
				'#type' => 'select',
				'#options' => [
				  'crn' => $this
					->t('Create New'),
          'pro' => $this
					->t('Producer'),
          'shmu' => $this
					->t('SHMU'),
          'ssa' => $this
					->t('Soil Sample'),
          'ifa' => $this
					->t('Assessment'),
          'ltr' => $this
					->t('Soil Test Result'),
          'ltm' => $this
					->t('Methods'),
          'oper' => $this
					->t('Operation'),
          'irr' => $this
					->t('Irrigation'),
        ],
				'#prefix' => '<div id="top-form">'
		];

    $form['form_body'] = [
        '#markup' => '<p id="form-body">Let\'s get started, you can create and manage Producers, Soil Health Management Units (SHMU), Soil Samples, Lab Test Methods, and Operations using this tool.</p>',
        '#suffix' => '</div>',
    ];

    $form['form_subtitle'] = [
        '#markup' => '<h2 id="form-subtitle">Manage Assets</h2>',
        	'#prefix' => '<div class="bottom-form">',
    ];

    $awardeeEntities = array('project', 'awardee', 'producer', 'soil_health_demo_trial',
     'soil_health_sample', 'lab_result', 'field_assessment', 'soil_health_management_unit', 'lab_testing_method', 'operation', 'irrigation');
    $entityCount = array();

      for($i = 0; $i < count($awardeeEntities); $i++){
        $query = \Drupal::entityQuery('asset')->condition('type',$awardeeEntities[$i]);
        array_push($entityCount, $query->count()->execute());
      }



    $form['awardee_producer'] = [
      '#type' => 'submit',
      '#value' => $this->t('Producer(s): '.$entityCount[2]),
      '#submit' => ['::proRedirect'],
    ];

    $form['awardee_soil_health_management_unit'] = [
      '#type' => 'submit',
      '#value' => $this->t('SHMU(s): '.$entityCount[7]),
      '#submit' => ['::shmuRedirect'],
    ];

    $form['awardee_soil_health_sample'] = [
      '#type' => 'submit',
      '#value' => $this->t('Soil Sample(s): '.$entityCount[4]),
      '#submit' => ['::ssaRedirect'],
    ];

    $form['awardee_in_field_assessment'] = [
      '#type' => 'submit',
      '#value' => $this->t('Assessment(s): '.$entityCount[6]),
      '#submit' => ['::ifaRedirect'],
    ];

		$form['awardee_lab_result'] = [
      '#type' => 'submit',
      '#value' => $this->t('Soil Test Result(s): '.$entityCount[5]),
      '#submit' => ['::labresRedirect'],
    ];

    $form['awardee_lab'] = [
      '#type' => 'submit',
      '#value' => $this->t('Method(s): '.$entityCount[8]),
      '#submit' => ['::labRedirect'],
    ];

    $form['awardee_irrigation'] = [
      '#type' => 'submit',
      '#value' => $this->t('Irrigation(s): '.$entityCount[10]),
      '#submit' => ['::irrRedirect'],
    ];

    $form['awardee_operation'] = [
      '#type' => 'submit',
      '#value' => $this->t('Operation(s): '.$entityCount[9]),
      '#submit' => ['::operRedirect'],
      '#suffix' => '</div>',
    ];




		return $form;
	}

  private function pageRedirect (FormStateInterface $form_state, string $path) {
    $match = [];
    $path2 =  $path;
    $router = \Drupal::service('router.no_access_checks');

    try {
      $match = $router->match($path2);
    }
    catch (\Exception $e) {
      // The route using that path hasn't been found,
      // or the HTTP method isn't allowed for that route.
    }
   $form_state->setRedirect($match["_route"]);
  }


public function labRedirect (array &$form, FormStateInterface $form_state) {
  $this->pageRedirect($form_state, "/assets/lab_testing_method");
}
public function labresRedirect (array &$form, FormStateInterface $form_state) {
  $this->pageRedirect($form_state, "/assets/lab_result");
}
public function proRedirect (array &$form, FormStateInterface $form_state) {
  $this->pageRedirect($form_state, "/assets/producer");
}
public function ifaRedirect (array &$form, FormStateInterface $form_state) {
  $this->pageRedirect($form_state, "/assets/field_assessment");
}
public function ssaRedirect (array &$form, FormStateInterface $form_state) {
  $this->pageRedirect($form_state, "/assets/soil_health_sample");
}
public function shmuRedirect (array &$form, FormStateInterface $form_state) {
  $this->pageRedirect($form_state, "/assets/soil_health_management_unit");
}
public function operRedirect (array &$form, FormStateInterface $form_state) {
  $this->pageRedirect($form_state, "/assets/operation");
}

public function irrRedirect (array &$form, FormStateInterface $form_state) {
  $this->pageRedirect($form_state, "/assets/irrigation");
}

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state){
	return ;
}

  /**
   * {@inheritdoc}
   */
   public function submitForm(array &$form, FormStateInterface $form_state) {
   }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'awardee_dashboard_form';
  }
}