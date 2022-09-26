<?php

namespace Drupal\cig_pods\Form;

use Drupal\cig_pods\ProjectAccessControlHandler;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormStateInterface;

/**
 * PODS Dashboard form for admins and awardees.
 */
class PodsDashboardForm extends PodsFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'pods_dashboard_form';
  }

  /**
   * Check access for the form based on zRole.
   */
  public function access() {
    $is_admin = ProjectAccessControlHandler::isAdmin();
    $is_awadee = ProjectAccessControlHandler::isAwardee();
    return AccessResult::allowedIf($is_admin || $is_awadee);
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Attach proper CSS to form.
    $form['#attached']['library'][] = 'cig_pods/dashboard';

    // Add title.
    $form['title'] = [
      '#markup' => '<div id="title">Dashboard</div>',
    ];

    // Build the form based on zRole.
    if (ProjectAccessControlHandler::isAdmin()) {
      return $this->buildAdminForm($form, $form_state);
    }
    elseif (ProjectAccessControlHandler::isAwardee()) {
      return $this->buildAwardeeForm($form, $form_state);
    }
    return [];
  }

  /**
   * Build the dashboard form for admins.
   */
  protected function buildAdminForm(array $form, FormStateInterface $form_state) {

    $form['entities_fieldset']['create_new'] = [
      '#type' => 'select',
      '#options' => [
        '' => $this->t('Create New'),
        'awo' => $this->t('Awardee Org'),
        'proj' => $this->t('Project'),
        'ltp' => $this->t('Lab Test Profile'),
      ],
      '#prefix' => '<div id="top-form">',
    ];

    $form['form_body'] = [
      '#markup' => '<p id="form-body">Let\'s get started, you can create and manage Awardees, Projects, Lab Test Methods using this tool.</p>',
      '#suffix' => '</div>',
    ];

    $form['form_subtitle'] = [
      '#markup' => '<h2 id="form-subtitle">Manage Assets</h2>',
      '#prefix' => '<div class="bottom-form">',
    ];

    $awardeeEntities = ['project', 'awardee', 'lab_testing_profile'];
    $entityCount = [];

    foreach ($awardeeEntities as $bundle) {
      $entities = $this->entityOptions('asset', $bundle);
      $entityCount[] = count($entities);
    }

    $form['awardee_proj'] = [
      '#type' => 'submit',
      '#value' => $this->t('Projects(s): ' . $entityCount[0]),
      '#submit' => ['::projectRedirect'],
      '#class="button-container">',
    ];

    $form['awardee_org'] = [
      '#type' => 'submit',
      '#value' => $this->t('Awardee Organization(s): ' . $entityCount[1]),
      '#submit' => ['::orgRedirect'],
    ];

    $form['awardee_lab'] = [
      '#type' => 'submit',
      '#value' => $this->t('Lab Test Profile(s): ' . $entityCount[2]),
      '#submit' => ['::profileRedirect'],
      '#suffix' => '</div>',
    ];

    return $form;
  }

  /**
   * Build the dashboard form for awardees.
   */
  public function buildAwardeeForm(array $form, FormStateInterface $form_state) {

    $form['entities_fieldset']['create_new'] = [
      '#type' => 'select',
      '#options' => [
        '' => $this->t('Create New'),
        'pro' => $this->t('Producer'),
        'shmu' => $this->t('SHMU'),
        'ssa' => $this->t('Soil Sample'),
        'ifa' => $this->t('CIFSH Assessment'),
        'rla' => $this->t('IIRH Assessment'),
        'pst' => $this->t('PCS Assessment'),
        'phst' => $this->t('DIPH Assessment'),
        'ltr' => $this->t('Soil Test Result'),
        'ltm' => $this->t('Methods'),
        'oper' => $this->t('Operation'),
        'irr' => $this->t('Irrigation'),
      ],
      '#prefix' => '<div id="top-form">',
    ];

    $form['form_body'] = [
      '#markup' => '<p id="form-body">Let\'s get started, you can create and manage Producers, Soil Health Management Units (SHMU), Soil Samples, Lab Test Methods, and Operations using this tool.</p>',
      '#suffix' => '</div>',
    ];

    $form['form_subtitle'] = [
      '#markup' => '<h2 id="form-subtitle">Manage Assets</h2>',
      '#prefix' => '<div class="bottom-form">',
    ];

    $awardeeEntities = [
      'project',
      'producer',
      'soil_health_sample',
      'lab_result',
      'field_assessment',
      'soil_health_management_unit',
      'lab_testing_method',
      'operation',
      'irrigation',
      'range_assessment',
      'pasture_assessment',
      'soil_health_management_unit',
      'pasture_health_assessment',
    ];

    $entityCount = [];

    foreach ($awardeeEntities as $bundle) {
      $entities = $this->entityOptions('asset', $bundle);
      $entityCount[$bundle] = count($entities);
    }

    // If no projects are assigned, display a warning.
    if (empty($entityCount['project'])) {
      $this->messenger()->addWarning($this->t('You are not currently assigned to any projects. You must be assigned as a project contact in order to create or edit records.'));
    }

    $form['awardee_producer'] = [
      '#type' => 'submit',
      '#value' => $this->t('Producer(s): ' . $entityCount['producer']),
      '#submit' => ['::proRedirect'],
    ];

    $form['awardee_soil_health_management_unit'] = [
      '#type' => 'submit',
      '#value' => $this->t('SHMU(s): ' . $entityCount['soil_health_management_unit']),
      '#submit' => ['::shmuRedirect'],
    ];

    $form['awardee_soil_health_sample'] = [
      '#type' => 'submit',
      '#value' => $this->t('Soil Sample(s): ' . $entityCount['soil_health_sample']),
      '#submit' => ['::ssaRedirect'],
    ];

    $form['awardee_in_field_assessment'] = [
      '#type' => 'submit',
      '#value' => $this->t('CIFSH Assessment(s): ' . $entityCount['field_assessment']),
      '#submit' => ['::ifaRedirect'],
    ];

    $form['awardee_rangeland_assessment'] = [
      '#type' => 'submit',
      '#value' => $this->t('IIRH Assessment(s): ' . $entityCount['range_assessment']),
      '#submit' => ['::rlaRedirect'],
    ];

    $form['awardee_pasture_assessment'] = [
      '#type' => 'submit',
      '#value' => $this->t('PCS Assessment(s): ' . $entityCount['pasture_assessment']),
      '#submit' => ['::pstRedirect'],
    ];

    $form['awardee_pasture_health_assessment'] = [
      '#type' => 'submit',
      '#value' => $this->t('DIPH Assessment(s): ' . $entityCount['pasture_health_assessment']),
      '#submit' => ['::phstRedirect'],
    ];

    $form['awardee_lab_result'] = [
      '#type' => 'submit',
      '#value' => $this->t('Soil Test Result(s): ' . $entityCount['lab_result']),
      '#submit' => ['::labresRedirect'],
    ];

    $form['awardee_lab'] = [
      '#type' => 'submit',
      '#value' => $this->t('Method(s): ' . $entityCount['lab_testing_method']),
      '#submit' => ['::labRedirect'],
    ];

    $form['awardee_irrigation'] = [
      '#type' => 'submit',
      '#value' => $this->t('Irrigation(s): ' . $entityCount['irrigation']),
      '#submit' => ['::irrRedirect'],
    ];

    $form['awardee_operation'] = [
      '#type' => 'submit',
      '#value' => $this->t('Operation(s): ' . $entityCount['operation']),
      '#submit' => ['::operRedirect'],
      '#suffix' => '</div>',
    ];

    return $form;
  }

  /**
   *
   */
  private function pageRedirect(FormStateInterface $form_state, string $path) {
    $match = [];
    $path2 = $path;
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

  /**
   * Set the appropriate place where created entities are managed from.
   */
  public function projectRedirect(array &$form, FormStateInterface $form_state) {
    $this->pageRedirect($form_state, "/assets/project");
  }

  /**
   *
   */
  public function orgRedirect(array &$form, FormStateInterface $form_state) {
    $this->pageRedirect($form_state, "/assets/awardee");
  }

  /**
   *
   */
  public function profileRedirect(array &$form, FormStateInterface $form_state) {
    $this->pageRedirect($form_state, "/assets/lab_testing_profile");
  }

  /**
   * Set the appropriate place where created entities are managed from.
   */
  public function labRedirect(array &$form, FormStateInterface $form_state) {
    $this->pageRedirect($form_state, "/assets/lab_testing_method");
  }

  /**
   *
   */
  public function labresRedirect(array &$form, FormStateInterface $form_state) {
    $this->pageRedirect($form_state, "/assets/lab_result");
  }

  /**
   *
   */
  public function proRedirect(array &$form, FormStateInterface $form_state) {
    $this->pageRedirect($form_state, "/assets/producer");
  }

  /**
   *
   */
  public function ifaRedirect(array &$form, FormStateInterface $form_state) {
    $this->pageRedirect($form_state, "/assets/field_assessment");
  }

  /**
   *
   */
  public function rlaRedirect(array &$form, FormStateInterface $form_state) {
    $this->pageRedirect($form_state, "/assets/range_assessment");
  }

  /**
   *
   */
  public function pstRedirect(array &$form, FormStateInterface $form_state) {
    $this->pageRedirect($form_state, "/assets/pasture_assessment");
  }

  /**
   *
   */
  public function phstRedirect(array &$form, FormStateInterface $form_state) {
    $this->pageRedirect($form_state, "/assets/pasture_health_assessment");
  }

  /**
   *
   */
  public function ssaRedirect(array &$form, FormStateInterface $form_state) {
    $this->pageRedirect($form_state, "/assets/soil_health_sample");
  }

  /**
   *
   */
  public function shmuRedirect(array &$form, FormStateInterface $form_state) {
    $this->pageRedirect($form_state, "/assets/soil_health_management_unit");
  }

  /**
   *
   */
  public function operRedirect(array &$form, FormStateInterface $form_state) {
    $this->pageRedirect($form_state, "/assets/operation");
  }

  /**
   *
   */
  public function irrRedirect(array &$form, FormStateInterface $form_state) {
    $this->pageRedirect($form_state, "/assets/irrigation");
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
