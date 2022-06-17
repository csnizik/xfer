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
				  '' => $this
					->t('Create New'),
				  'pr' => $this
					->t('Producer(s)'),
				  'awo' => $this
					->t('Awardee Org'),
				  'proj' => $this
					->t('Project'),
                  'ltm' => $this
					->t('Methods'),
                ],
				'#prefix' => '<div id="top-form">'
			];

    $form['form_body'] = [
        '#markup' => '<p id="form-body">Let\'s get started, you can create and manage Awardees, Projects, Lab Test Methods and Producers using this tool.</p>',
        '#suffix' => '</div>',
    ];

    $form['form_subtitle'] = [
        '#markup' => '<h2 id="form-subtitle">Manage Assets</h2>',
        	'#prefix' => '<div class="bottom-form">',
    ];

     $awardeeEntities = array('project', 'awardee','producer', 'lab_testing_method' );
       $entityCount = array();

      for($i = 0; $i < count($awardeeEntities); $i++){
        $query = \Drupal::entityQuery('asset')->condition('type',$awardeeEntities[$i]);
        array_push($entityCount, $query->count()->execute());
      }

    $form['awardee_proj'] = [
      '#type' => 'submit',
      '#value' => $this->t('Projects(s): '.$entityCount[0]),
      '#submit' => ['::projectRedirect'],
      '#class="button-container">',
    ];

    $form['awardee_org'] = [
      '#type' => 'submit',
      '#value' => $this->t('Awardee Organization(s): '.$entityCount[1]),
      '#submit' => ['::orgRedirect'],
    ];


    $form['awardee_prod'] = [
      '#type' => 'submit',
      '#value' => $this->t('Producer(s): '.$entityCount[2]),
      '#submit' => ['::producerRedirect'],
    ];

		$form['awardee_lab'] = [
      '#type' => 'submit',
      '#value' => $this->t('Methods: '.$entityCount[3]),
      '#submit' => ['::labRedirect'],
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

public function projectRedirect (array &$form, FormStateInterface $form_state) {
   $this->pageRedirect($form_state, "/assets/project");
}
public function orgRedirect (array &$form, FormStateInterface $form_state) {
  $this->pageRedirect($form_state, "/assets/awardee");
}
public function producerRedirect (array &$form, FormStateInterface $form_state) {
  $this->pageRedirect($form_state, "/assets/producer");
}
public function labRedirect (array &$form, FormStateInterface $form_state) {
  $this->pageRedirect($form_state, "/assets/lab_testing_method");
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