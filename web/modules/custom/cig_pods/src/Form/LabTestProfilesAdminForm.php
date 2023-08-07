<?php

namespace Drupal\cig_pods\Form;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\asset\Entity\Asset;

/**
 * Lab test profile admin form.
 */
class LabTestProfilesAdminForm extends PodsFormBase {

  /**
   * Get soil health extraction options.
   */
  public function getSoilHealthExtractionOptions($bundle) {
    $options = $this->entityOptions('taxonomy_term', $bundle);
    return ['' => '- Select -'] + $options;
  }

  /**
   * Create element names.
   */
  private function createElementNames() {
    return ['name', 'field_profile_laboratory', 'field_profile_aggregate_stability_method', 'field_profile_respiratory_incubation_days', 'field_profile_respiration_detection_method',
      'electroconductivity_method', 'nitrate_n_method', 'phosphorus_method', 'potassium_method', 'calcium_method', 'magnesium_method', 'sulfur_method', 'iron_method', 'manganese_method',
      'copper_method', 'zinc_method', 'boron_method', 'aluminum_method', 'molybdenum_method', 'field_profile_aggregate_stability_unit', 'field_lab_profile_infiltration_method',
      'ph_method',
    ];

  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, AssetInterface $asset = NULL) {
    $labTestProfile = $asset;

    $is_edit = $labTestProfile <> NULL;

    if ($is_edit) {
      $form_state->set('operation', 'edit');
      $form_state->set('lab_test_id', $labTestProfile->id());

    }
    else {
      $form_state->set('operation', 'create');
    }

    if ($form_state->get('operation') == 'create') {

    }

    $form['#attached']['library'][] = 'cig_pods/lab_test_profiles_admin_form';
    $form['#attached']['library'][] = 'cig_pods/css_form';
    $form['#attached']['library'][] = 'core/drupal.form';

    $agg_stab_method = $this->getSoilHealthExtractionOptions("d_aggregate_stability_me");
    $agg_stab_unit = $this->getSoilHealthExtractionOptions("d_aggregate_stability_un");
    $ec_method = $this->getSoilHealthExtractionOptions("d_ec_method");
    $lab = $this->getSoilHealthExtractionOptions("d_laboratory");
    foreach ($lab as $key => $item) {
      $lab[$key] = html_entity_decode($item);
    }
    $nitrate_method = $this->getSoilHealthExtractionOptions("d_nitrate_n_method");
    $resp_detect = $this->getSoilHealthExtractionOptions("d_respiration_detection_");
    $resp_incub = $this->getSoilHealthExtractionOptions("d_respiration_incubation");
    $s_he_extract = $this->getSoilHealthExtractionOptions("d_soil_health_extraction");
    $infiltration_method = $this->getSoilHealthExtractionOptions("d_infiltration_method");
    $soil_ph_method = $this->getSoilHealthExtractionOptions("d_ph_method");

    $form['lab_test_title'] = [
      '#markup' => '<h1>Lab Test Profiles</h1>',
    ];

    $form['subform_1'] = [
      '#markup' => '<div class="subform-title-container subform-title-container-top"><h2>Profiles Information</h2><h4>21 Fields | Section 1 of 1</h4></div>',
    ];

    $profile_name = $is_edit ? $labTestProfile->get('name')->value : "";
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Test Profile Name'),
      '#default_value' => $profile_name,
      '#required' => TRUE,
    ];

    $laboratory_default_value = $is_edit ? $labTestProfile->get('field_profile_laboratory')->target_id : NULL;
    $form['field_profile_laboratory'] = [
      '#type' => 'select',
      '#title' => 'Laboratory',
      '#options' => $lab,
      '#default_value' => $laboratory_default_value,
      '#required' => TRUE,
    ];

    $aggregate_method_default_value = $is_edit ? $labTestProfile->get('field_profile_aggregate_stability_method')->target_id : NULL;
    $form['field_profile_aggregate_stability_method'] = [
      '#type' => 'select',
      '#title' => 'Aggregate Stability Method',
      '#options' => $agg_stab_method,
      '#default_value' => $aggregate_method_default_value,
      '#required' => TRUE,
    ];

    $aggregate_unit_default_value = $is_edit ? $labTestProfile->get('field_profile_aggregate_stability_unit')->target_id : NULL;
    $form['field_profile_aggregate_stability_unit'] = [
      '#type' => 'select',
      '#title' => 'Aggregate Stability Unit',
      '#options' => $agg_stab_unit,
      '#default_value' => $aggregate_unit_default_value,
      '#required' => TRUE,
    ];

    $respiratory_incubation_default_value = $is_edit ? $labTestProfile->get('field_profile_respiratory_incubation_days')->target_id : NULL;
    $form['field_profile_respiratory_incubation_days'] = [
      '#type' => 'select',
      '#title' => 'Respiration Incubation Days',
      '#options' => $resp_incub,
      '#default_value' => $respiratory_incubation_default_value,
      '#required' => TRUE,
    ];

    $respiratory_detection_default_value = $is_edit ? $labTestProfile->get('field_profile_respiration_detection_method')->target_id : NULL;
    $form['field_profile_respiration_detection_method'] = [
      '#type' => 'select',
      '#title' => 'Respiration Detection Method',
      '#options' => $resp_detect,
      '#default_value' => $respiratory_detection_default_value,
      '#required' => TRUE,
    ];

    $infiltration_method_default_value = $is_edit ? $labTestProfile->get('field_lab_profile_infiltration_method')->target_id : NULL;
    $form['autoload_container']['field_lab_profile_infiltration_method'] = [
      '#type' => 'select',
      '#title' => $this->t('Infiltration Method'),
      '#options' => $infiltration_method,
      '#default_value' => $infiltration_method_default_value,
      '#required' => TRUE,
    ];

    $electroconductivity_method_default_value = $is_edit ? $labTestProfile->get('electroconductivity_method')->target_id : NULL;
    $form['electroconductivity_method'] = [
      '#type' => 'select',
      '#title' => $this->t('Electroconductivity Method'),
      '#options' => $ec_method,
      '#default_value' => $electroconductivity_method_default_value,
      '#required' => TRUE,
    ];

    $nitrate_n_method_default_value = $is_edit ? $labTestProfile->get('nitrate_n_method')->target_id : NULL;
    $form['nitrate_n_method'] = [
      '#type' => 'select',
      '#title' => $this->t('Nitrate-N Method'),
      '#options' => $nitrate_method,
      '#default_value' => $nitrate_n_method_default_value,
      '#required' => TRUE,
    ];

    $soil_ph_method_default_value = $is_edit ? $labTestProfile->get('ph_method')->target_id : NULL;
    $form['ph_method'] = [
      '#type' => 'select',
      '#title' => $this->t('Soil pH Method'),
      '#options' => $soil_ph_method,
      '#default_value' => $soil_ph_method_default_value,
      '#required' => TRUE,
    ];

    $phosphorus_method_default_value = $is_edit ? $labTestProfile->get('phosphorus_method')->target_id : NULL;
    $form['phosphorus_method'] = [
      '#type' => 'select',
      '#title' => $this->t('Phosphorus Method'),
      '#options' => $s_he_extract,
      '#default_value' => $phosphorus_method_default_value,
      '#required' => TRUE,
    ];

    $potassium_method_default_value = $is_edit ? $labTestProfile->get('potassium_method')->target_id : NULL;
    $form['potassium_method'] = [
      '#type' => 'select',
      '#title' => $this->t('Potassium Method'),
      '#options' => $s_he_extract,
      '#default_value' => $potassium_method_default_value,
      '#required' => TRUE,
    ];

    $calcium_method_default_value = $is_edit ? $labTestProfile->get('calcium_method')->target_id : NULL;
    $form['calcium_method'] = [
      '#type' => 'select',
      '#title' => $this->t('Calcium Method'),
      '#options' => $s_he_extract,
      '#default_value' => $calcium_method_default_value,
      '#required' => TRUE,
    ];

    $magnesium_method_default_value = $is_edit ? $labTestProfile->get('magnesium_method')->target_id : NULL;
    $form['magnesium_method'] = [
      '#type' => 'select',
      '#title' => $this->t('Magnesium Method'),
      '#options' => $s_he_extract,
      '#default_value' => $magnesium_method_default_value,
      '#required' => TRUE,
    ];

    $sulfur_method_default_value = $is_edit ? $labTestProfile->get('sulfur_method')->target_id : NULL;
    $form['sulfur_method'] = [
      '#type' => 'select',
      '#title' => $this->t('Sulfur Method'),
      '#options' => $s_he_extract,
      '#default_value' => $sulfur_method_default_value,
      '#required' => TRUE,
    ];

    $iron_method_default_value = $is_edit ? $labTestProfile->get('iron_method')->target_id : NULL;
    $form['iron_method'] = [
      '#type' => 'select',
      '#title' => $this->t('Iron Method'),
      '#options' => $s_he_extract,
      '#default_value' => $iron_method_default_value,
      '#required' => TRUE,
    ];

    $manganese_method_default_value = $is_edit ? $labTestProfile->get('manganese_method')->target_id : NULL;
    $form['manganese_method'] = [
      '#type' => 'select',
      '#title' => $this->t('Manganese Method'),
      '#options' => $s_he_extract,
      '#default_value' => $manganese_method_default_value,
      '#required' => TRUE,
    ];

    $copper_method_default_value = $is_edit ? $labTestProfile->get('copper_method')->target_id : NULL;
    $form['copper_method'] = [
      '#type' => 'select',
      '#title' => $this->t('Copper Method'),
      '#options' => $s_he_extract,
      '#default_value' => $copper_method_default_value,
      '#required' => TRUE,
    ];

    $zinc_method_default_value = $is_edit ? $labTestProfile->get('zinc_method')->target_id : NULL;
    $form['zinc_method'] = [
      '#type' => 'select',
      '#title' => $this->t('Zinc Method'),
      '#options' => $s_he_extract,
      '#default_value' => $zinc_method_default_value,
      '#required' => TRUE,
    ];

    $boron_method_default_value = $is_edit ? $labTestProfile->get('boron_method')->target_id : NULL;
    $form['boron_method'] = [
      '#type' => 'select',
      '#title' => $this->t('Boron Method'),
      '#options' => $s_he_extract,
      '#default_value' => $boron_method_default_value,
      '#required' => TRUE,
    ];

    $aluminum_method_default_value = $is_edit ? $labTestProfile->get('aluminum_method')->target_id : NULL;
    $form['aluminum_method'] = [
      '#type' => 'select',
      '#title' => $this->t('Aluminum Method'),
      '#options' => $s_he_extract,
      '#default_value' => $aluminum_method_default_value,
      '#required' => TRUE,
    ];

    $molybdenum_method_default_value = $is_edit ? $labTestProfile->get('molybdenum_method')->target_id : NULL;
    $form['molybdenum_method'] = [
      '#type' => 'select',
      '#title' => $this->t('Molybdenum Method'),
      '#options' => $s_he_extract,
      '#default_value' => $molybdenum_method_default_value,
      '#required' => TRUE,
    ];

    $form['actions']['save'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    $form['actions']['cancel'] = [
      '#type' => 'submit',
      '#limit_validation_errors' => '',
      '#value' => $this->t('Cancel'),
      '#submit' => ['::redirectAfterCancel'],
    ];

    if ($is_edit) {
      $form['actions']['delete'] = [
        '#type' => 'submit',
        '#value' => $this->t('Delete'),
        '#submit' => ['::deleteLabTest'],
      ];
    }
    return $form;
  }

  /**
   * Redirect after cancel.
   */
  public function redirectAfterCancel(array $form, FormStateInterface $form_state) {
    $form_state->setRedirect('cig_pods.dashboard');
  }

  /**
   * Delete lab test.
   */
  public function deleteLabTest(array &$form, FormStateInterface $form_state) {
    $lab_test_id = $form_state->get('lab_test_id');
    $labTest = \Drupal::entityTypeManager()->getStorage('asset')->load($lab_test_id);

    try {
      $labTest->delete();
      $form_state->setRedirect('cig_pods.dashboard');
    }
    catch (\Exception $e) {
      $this
        ->messenger()
        ->addError($e->getMessage());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'lab_test_profiles_admin';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $profile_submission = [];
    if ($form_state->get('operation') === 'create') {
      $elementNames = $this->createElementNames();
      foreach ($elementNames as $elemName) {
        $profile_submission[$elemName] = $form_state->getValue($elemName);
      }

      $profile_submission['type'] = 'lab_testing_profile';
      $profile = Asset::create($profile_submission);
      $profile->save();

      $form_state->setRedirect('cig_pods.dashboard');

    }
    else {
      $id = $form_state->get('lab_test_id');
      $labTestProfile = \Drupal::entityTypeManager()->getStorage('asset')->load($id);

      $elementNames = $this->createElementNames();
      foreach ($elementNames as $elemName) {
        $labTestProfile->set($elemName, $form_state->getValue($elemName));
      }

      $labTestProfile->save();
      $form_state->setRedirect('cig_pods.dashboard');
    }
  }

}
