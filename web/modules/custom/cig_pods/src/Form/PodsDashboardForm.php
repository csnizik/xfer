<?php

namespace Drupal\cig_pods\Form;

use Drupal\cig_pods\ProjectAccessControlHandler;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

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
        'create_awardee' => $this->t('Awardee Org'),
        'create_project' => $this->t('Project'),
        'create_lab_test_profile' => $this->t('Lab Test Profile'),
      ],
      '#attributes' => [
        'onchange' => 'this.form.submit();',
      ],
      '#prefix' => '<div id="top-form">',
    ];

    // Create a hidden submit button that will be used when an item is
    // selected from the dropdown. This is necessary because we are using
    // this.form.submit() and if Drupal can't detect the triggering element,
    // it will assume the first button was clicked.
    $form['create'] = [
      '#type' => 'submit',
      '#value' => 'Submit',
      '#name' => 'create',
      '#attributes' => [
        'style' => 'display: none;',
      ],
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
      '#value' => $this->t('Project(s): @count', ['@count' => $entityCount[0]]),
      '#name' => 'project',
    ];

    $form['awardee_org'] = [
      '#type' => 'submit',
      '#value' => $this->t('Awardee Organization(s): @count', ['@count' => $entityCount[1]]),
      '#name' => 'awardee',
    ];

    $form['awardee_lab'] = [
      '#type' => 'submit',
      '#value' => $this->t('Lab Test Profile(s): @count', ['@count' => $entityCount[2]]),
      '#name' => 'lab_profile',
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
        'create_producer' => $this->t('Producer'),
        'create_soil_health_management_unit' => $this->t('SHMU'),
        'create_soil_health_sample' => $this->t('Soil Sample'),
        'create_field_assessment' => $this->t('CIFSH Assessment'),
        'create_range_assessment' => $this->t('IIRH Assessment'),
        'create_pasture_assessment' => $this->t('PCS Assessment'),
        'create_pasture_health_assessment' => $this->t('DIPH Assessment'),
        'create_lab_result' => $this->t('Soil Test Result'),
        'create_lab_testing_method' => $this->t('Methods'),
        'create_operation' => $this->t('Operation'),
        'create_irrigation' => $this->t('Irrigation'),
      ],
      '#attributes' => [
        'onchange' => 'this.form.submit();',
      ],
      '#prefix' => '<div id="top-form">',
    ];

    // Create a hidden submit button that will be used when an item is
    // selected from the dropdown. This is necessary because we are using
    // this.form.submit() and if Drupal can't detect the triggering element,
    // it will assume the first button was clicked.
    $form['create'] = [
      '#type' => 'submit',
      '#value' => 'Submit',
      '#name' => 'create',
      '#attributes' => [
        'style' => 'display: none;',
      ],
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
      '#value' => $this->t('Producer(s): @count', ['@count' => $entityCount['producer']]),
      '#name' => 'producer',
    ];

    $form['awardee_soil_health_management_unit'] = [
      '#type' => 'submit',
      '#value' => $this->t('SHMU(s): @count', ['@count' => $entityCount['soil_health_management_unit']]),
      '#name' => 'soil_health_management_unit',
    ];

    $form['awardee_soil_health_sample'] = [
      '#type' => 'submit',
      '#value' => $this->t('Soil Sample(s): @count', ['@count' => $entityCount['soil_health_sample']]),
      '#name' => 'soil_health_sample',
    ];

    $form['awardee_in_field_assessment'] = [
      '#type' => 'submit',
      '#value' => $this->t('CIFSH Assessment(s): @count', ['@count' => $entityCount['field_assessment']]),
      '#name' => 'field_assessment',
    ];

    $form['awardee_rangeland_assessment'] = [
      '#type' => 'submit',
      '#value' => $this->t('IIRH Assessment(s): @count', ['@count' => $entityCount['range_assessment']]),
      '#name' => 'range_assessment',
    ];

    $form['awardee_pasture_assessment'] = [
      '#type' => 'submit',
      '#value' => $this->t('PCS Assessment(s): @count', ['@count' => $entityCount['pasture_assessment']]),
      '#name' => 'pasture_assessment',
    ];

    $form['awardee_pasture_health_assessment'] = [
      '#type' => 'submit',
      '#value' => $this->t('DIPH Assessment(s): @count', ['@count' => $entityCount['pasture_health_assessment']]),
      '#name' => 'pasture_health_assessment',
    ];

    $form['awardee_lab_result'] = [
      '#type' => 'submit',
      '#value' => $this->t('Soil Test Result(s): @count', ['@count' => $entityCount['lab_result']]),
      '#name' => 'lab_result',
    ];

    $form['awardee_lab'] = [
      '#type' => 'submit',
      '#value' => $this->t('Method(s):  @count', ['@count' => $entityCount['lab_testing_method']]),
      '#name' => 'lab_testing_method',
    ];

    $form['awardee_irrigation'] = [
      '#type' => 'submit',
      '#value' => $this->t('Irrigation(s): @count', ['@count' => $entityCount['irrigation']]),
      '#name' => 'irrigation',
    ];

    $form['awardee_operation'] = [
      '#type' => 'submit',
      '#value' => $this->t('Operation(s): @count', ['@count' => $entityCount['operation']]),
      '#name' => 'operation',
      '#suffix' => '</div>',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Define redirect paths based on asset type.
    $redirects = [

      // Admin asset creation paths:
      'create_project' => '/create/project',
      'create_awardee' => '/create/awardee_org',
      'create_lab_test_profile' => '/create/lab_test_profiles_admin',

      // Admin asset list paths:
      'project' => '/assets/project',
      'awardee' => '/assets/awardee',
      'lab_profile' => '/assets/lab_testing_profile',

      // Awardee asset creation paths:
      'create_producer' => '/create/producer',
      'create_soil_health_management_unit' => '/create/shmu',
      'create_soil_health_sample' => '/create/soil_health_sample',
      'create_field_assessment' => '/create/field_assessment',
      'create_range_assessment' => '/create/range_assessment',
      'create_pasture_assessment' => '/create/pasture_assessment',
      'create_pasture_health_assessment' => '/create/pasture_health_assessment',
      'create_lab_result' => '/create/lab_results',
      'create_lab_testing_method' => '/create/lab_testing_method',
      'create_irrigation' => '/create/irrigation',
      'create_operation' => '/create/operation',

      // Awardee asset list paths:
      'producer' => '/assets/producer',
      'soil_health_management_unit' => '/assets/soil_health_management_unit',
      'soil_health_sample' => '/assets/soil_health_sample',
      'field_assessment' => '/assets/field_assessment',
      'range_assessment' => '/assets/range_assessment',
      'pasture_assessment' => '/assets/pasture_assessment',
      'pasture_health_assessment' => '/assets/pasture_health_assessment',
      'lab_result' => '/assets/lab_result',
      'lab_testing_method' => '/assets/lab_testing_method',
      'irrigation' => '/assets/irrigation',
      'operation' => '/assets/operation',
    ];

    // Get the triggering element name and redirect accordingly.
    // This will either be "create" or a specific asset type. If it is "create"
    // then we know that the "Create new" select box was changed, and we can
    // get the submitted value and redirect to the asset creation form.
    // Otherwise, one of the asset type buttons was clicked, so we redirect to
    // the asset list page.
    $triggering_element = $form_state->getTriggeringElement();
    if (!empty($triggering_element['#name'])) {
      $name = $triggering_element['#name'];
      if ($name == 'create') {
        $type = $form_state->getValue('create_new');
        if (isset($redirects[$type])) {
          $form_state->setRedirectUrl(Url::fromUri('internal:' . $redirects[$type]));
        }
      }
      elseif (isset($redirects[$name])) {
        $form_state->setRedirectUrl(Url::fromUri('internal:' . $redirects[$name]));
      }
    }
  }

}
