<?php

namespace Drupal\cig_pods\Form;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\asset\Entity\Asset;
use Drupal\Core\Render\Element\Checkboxes;

/**
 * Project form.
 */
class ProjectForm extends PodsFormBase {

  /**
   * Get awardee options.
   */
  public function getAwardeeOptions() {
    $options = $this->entityOptions('asset', 'awardee');
    return ['' => '- Select -'] + $options;
  }

  /**
   * Get referenced contacts.
   */
  public function getReferencedContacts(array $form, FormStateInterface $form_state, $project_id) {
    $contacts = \Drupal::entityTypeManager()->getStorage('asset')->loadByProperties(
    ['type' => 'contact', 'project' => $project_id]
     );
    $form_state->set('contacts', $contacts);
  }

  /**
   * Get awardee contact name options.
   */
  public function getAwardeeContactNameOptions(array &$form, FormStateInterface $form_state) {
    $contact_options_email = [];

    $contact_name_options = [];
    $contact_name_options[''] = ' - Select -';
    $this->addContactsToArray('CIG_NSHDS', $contact_name_options, $contact_options_email);
    $this->addContactsToArray('CIG_APT', $contact_name_options, $contact_options_email);
    $this->addContactsToArray('CIG_App_Admin', $contact_name_options, $contact_options_email);
    $this->addContactsToArray('CIG_APA', $contact_name_options, $contact_options_email);

    $form_state->set('contact_emails', $contact_options_email);

    return $contact_name_options;
  }

  /**
   * Add contacts to array.
   */
  public function addContactsToArray(string $zRoleType, array &$contact_name_options, array &$contact_options_email) {
    $zRoleContacts = \Drupal::service('usda_eauth.zroles')->getListByzRole($zRoleType);

    foreach ($zRoleContacts as $zContacts) {
      if (gettype($zContacts->UsdaeAuthenticationId) != "string") {
        continue; // discard users with no eauth id
      }
      
      if (array_key_exists($zContacts->UsdaeAuthenticationId, $contact_name_options)) {
        continue;
      }
      
      $contact_name_options[$zContacts->UsdaeAuthenticationId] = $zContacts->FirstName . ' ' . $zContacts->LastName;
      $contact_options_email[$zContacts->UsdaeAuthenticationId] = $zContacts->EmailAddress;
    }
  }
  /**
   * Get awardee contact type options.
   */
  public function getAwardeeContactTypeOptions() {
    $contact_type_options = [];
    $contact_type_options[''] = ' - Select -';

    $contact_type_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
           ['vid' => 'd_contact_type']
     );
    $contact_type_keys = array_keys($contact_type_terms);
    foreach ($contact_type_keys as $contact_type_key) {
      $term = $contact_type_terms[$contact_type_key];
      $contact_type_options[$contact_type_key] = $term->getName();
    }

    return $contact_type_options;
  }

  /**
   * Get grant type options.
   */
  public function getGrantTypeOptions() {
    $grand_type_options = [];
    $grand_type_options = [];
    $grand_type_options[''] = ' - Select -';

    $grand_type_options = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
    ['vid' => 'd_grant_type']
    );
    $grand_type_keys = array_keys($grand_type_options);
    foreach ($grand_type_keys as $grand_type_key) {
      $term = $grand_type_options[$grand_type_key];
      $grand_type_options[$grand_type_key] = $term->getName();
    }

    return $grand_type_options;
  }

  /**
   * Convert fraction to decimal.
   */
  private function convertFractionsToDecimal($is_edit, $project, $field) {
    if ($is_edit) {
      $num = $project->get($field)[0]->getValue()["numerator"];
      $denom = $project->get($field)[0]->getValue()["denominator"];
      return $num / $denom;
    }
    else {
      return "";
    }
  }

  /**
   * Get resource concern options.
   */
  public function getResourceConcernOptions() {
    $resource_concern_options = [];
    $resource_concern_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(
    ['vid' => 'd_resource_concern']
    );

    $resource_concern_keys = array_keys($resource_concern_terms);

    foreach ($resource_concern_keys as $resource_concern_key) {
      $term = $resource_concern_terms[$resource_concern_key];
      $resource_concern_options[$resource_concern_key] = $term->getName();
    }

    return $resource_concern_options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $options = NULL, AssetInterface $asset = NULL) {
    $project = $asset;
    $is_edit = $project <> NULL;

    if ($is_edit) {
      $form_state->set('operation', 'edit');
      $form_state->set('project_id', $project->id());
      $this->getReferencedContacts($form, $form_state, $project->id());
    }
    else {
      $form_state->set('operation', 'create');
    }
    $form['#attached']['library'][] = 'cig_pods/project_entry_form';
    $form['#attached']['library'][] = 'cig_pods/css_form';
    $form['#attached']['library'][] = 'core/drupal.form';

    // Get num of contacts showing on screen. (1->n exclude:removed indexes)
    $num_contact_lines = $form_state->get('num_contact_lines');
    // Get num of added contacts. (1->n)
    $num_contacts = $form_state->get('num_contacts');
    // Get removed contacts indexes.
    $removed_contacts = $form_state->get('removed_contacts');
    $contact_default = $is_edit ? $form_state->get('contacts') : '';
    $contacts = [];
    foreach ($contact_default as $contact) {
      $contacts[] = $contact;

    }

    $ex_count = count($contacts);

    if ($is_edit) {
      // Initialize number of contact, set to 1.
      if ($num_contacts === NULL) {
        $form_state->set('num_contacts', $ex_count);
        $num_contacts = $form_state->get('num_contacts');
      }
      if ($num_contact_lines === NULL) {
        $form_state->set('num_contact_lines', $ex_count);
        $num_contact_lines = $form_state->get('num_contact_lines');
      }

    }
    else {
      // Initialize number of contact, set to 1.
      if ($num_contacts === NULL) {
        $form_state->set('num_contacts', 1);
        $num_contacts = $form_state->get('num_contacts');
      }
      if ($num_contact_lines === NULL) {
        $form_state->set('num_contact_lines', 1);
        $num_contact_lines = $form_state->get('num_contact_lines');
      }
    }

    if ($removed_contacts === NULL) {
      // Initialize arr.
      $form_state->set('removed_contacts', []);
      $removed_contacts = $form_state->get('removed_contacts');
    }

    $num_producer_lines = $form_state->get('num_producer_lines');
    $num_producers = $form_state->get('num_producers');
    $removed_producers = $form_state->get('removed_producers');

    if ($num_producer_lines == NULL) {
      $form_state->set('num_producer_lines', 1);
      $num_producer_lines = $form_state->get('num_producer_lines');
    }

    if ($num_producers === NULL) {
      $form_state->set('num_producers', 1);
      $num_producers = $form_state->get('num_producers');
    }

    if ($removed_producers === NULL) {
      $form_state->set('removed_producers', []);
      $removed_producers = $form_state->get('removed_producers');
    }

    $form['form_title'] = [
      '#markup' => '<h1 id="form-title">Project Information</h1>',
    ];

    $form['subform_1'] = [
      '#markup' => '<div class="subform-title-container"><h2>General Project Information</h2><h4>6 Fields | Section 1 of 2</h4></div>',
    ];

    $project_default_name = $is_edit ? $project->get('name')->value : '';
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Project Name'),
      '#description' => $this->t('Project Name'),
      '#required' => TRUE,
      '#default_value' => $project_default_name,
    ];

    $agreement_number_default = $is_edit ? $project->get('field_project_agreement_number')->getValue()[0]['value'] : '';
    $form['field_project_agreement_number'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Agreement Number'),
      '#description' => $this->t('Agreement Number'),
      '#default_value' => $agreement_number_default,
      '#required' => TRUE,
    ];

    $grand_type_options = $this->getGrantTypeOptions();
    $grant_type_default = $is_edit ? $project->get('field_grant_type')->target_id : NULL;
    $form['field_grant_type'] = [
      '#type' => 'select',
      '#title' => 'Grant Type',
      '#options' => $grand_type_options,
      '#required' => TRUE,
      '#default_value' => $grant_type_default,
    ];

    $awardee_org_default_name = $this->convertFractionsToDecimal($is_edit, $project, 'field_funding_amount');
    $form['field_funding_amount'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Funding Amount'),
      '#description' => $this->t('Funding Amount'),
      '#required' => TRUE,
      '#default_value' => $awardee_org_default_name,
    ];

    $field_resource_concerns_default = $is_edit ? $project->get('field_resource_concerns')->getValue() : '';
    $resource_concern_options = $this->getResourceConcernOptions();
    $field_resource_concerns_default_final = [];
    foreach ($field_resource_concerns_default as $checks) {
      $field_resource_concerns_default_final = $checks['target_id'];
      $field_resource_concerns_defaultvalue[] = $field_resource_concerns_default_final;
    }

    $form['field_resource_concerns'] = [
      '#type' => 'select2',
      '#multiple' => TRUE,
      '#title' => $this->t('Possible Resource Concerns'),
      '#options' => $resource_concern_options,
      '#required' => TRUE,
      '#default_value' => $field_resource_concerns_defaultvalue,
    ];

    $summary_default = $is_edit ? $project->get('field_summary')->getValue()[0]['value'] : '';
    $form['field_summary'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Project Summary'),
      '#description' => $this->t('Project Summary'),
      '#required' => TRUE,
      '#default_value' => $summary_default,
    ];

    /* Variables declaration end*/

    $awardee_options = $this->getAwardeeOptions();
    $contact_name_options = $this->getAwardeeContactNameOptions($form, $form_state);
    $contact_type_options = $this->getAwardeeContactTypeOptions();
    /* Awardee Information */
    $form['subform_2'] = [
      '#markup' => '<div class="subform-title-container awardee-info-spacing"><h2>Awardee Information</h2><h4>Section 2 of 2</h4></div>',
    ];

    $awardee_default_name = $is_edit ? $project->get('field_awardee')->target_id : NULL;
    $form['field_awardee'] = [
      '#type' => 'select',
      '#title' => 'Awardee Organization Name',
      '#options' => $awardee_options,
      '#required' => TRUE,
      '#default_value' => $awardee_default_name,
    ];

    $form['#tree'] = TRUE;
    $form['names_fieldset'] = [
      '#prefix' => '<div id="names-fieldset-wrapper"',
      '#suffix' => '</div>',
    ];

    $eauth_default_id = $is_edit ? $project->get('field_awardee_eauth_id')->getValue() : '';
    $contactname = [];
    foreach ($eauth_default_id as $checks) {
      $eauth = $checks['value'];
      $contactname[] = $eauth;
    }

    // num_contacts: get num of added contacts. (1->n)
    for ($i = 0; $i < $num_contacts; $i++) {

      // Check if field was removed.
      if (in_array($i, $removed_contacts)) {
        continue;
      }

      $default_name = $is_edit && !empty($contacts[$i]) ? $contacts[$i]->get('eauth_id')->value : NULL;
      $default_type = $is_edit && !empty($contacts[$i]) ? $contacts[$i]->get('field_contact_type')->target_id : NULL;

      $form['names_fieldset'][$i]['contact_name'] = [
        '#type' => 'select',
        '#title' => $this
          ->t("Contact Name"),
        '#options' => $contact_name_options,
        '#default_value' => $default_name,
        'attributes' => [
          'class' => 'something',
        ],
        '#prefix' => ($num_contact_lines > 1) ? '<div class="inline-components-short">' : '<div class="inline-components">',
        '#suffix' => '</div>',
      ];

      $form['names_fieldset'][$i]['contact_type'] = [
        '#type' => 'select',
        '#title' => $this
          ->t('Contact Type'),
        '#options' => $contact_type_options,
        '#default_value' => $default_type,
        '#prefix' => '<div class="inline-components"',
        '#suffix' => '</div>',
      ];

      if ($num_contact_lines > 1 && $i != 0) {
        $form['names_fieldset'][$i]['actions'] = [
          '#type' => 'submit',
          '#value' => $this->t('Delete'),
          '#name' => 'delete-contact-' . $i,
          '#submit' => ['::removeContactCallback'],
          '#ajax' => [
            'callback' => '::addContactRowCallback',
            'wrapper' => 'names-fieldset-wrapper',
          ],
          "#limit_validation_errors" => [],
          '#prefix' => '<div class="remove-button-container">',
          '#suffix' => '</div>',
        ];
      }

      // Css space for a new line due to previous items' float left attr.
      $form['names_fieldset'][$i]['new_line_container'] = [
        '#markup' => '<div class="clear-space"></div>',
      ];

    }

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['names_fieldset']['actions']['add_name'] = [
      '#type' => 'submit',
      '#button_type' => 'button',
      '#name' => 'add_contact_button',
      '#value' => $this->t('Add Another Contact'),
      '#submit' => ['::addContactRow'],
      '#ajax' => [
        'callback' => '::addContactRowCallback',
        'wrapper' => 'names-fieldset-wrapper',
      ],
      '#states' => [
        'visible' => [
          [":input[name='names_fieldset[0][contact_name]']" => ['!value' => '']],
          "and",
          [":input[name='names_fieldset[0][contact_type]']" => ['!value' => '']],
        ],
      ],
      "#limit_validation_errors" => [],
      '#prefix' => '<div id="addmore-button-container">',
      '#suffix' => '</div>',
    ];

    $form_state->setCached(FALSE);

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

    if ($is_edit) {
      $form['actions']['delete'] = [
        '#type' => 'submit',
        '#value' => $this->t('Delete'),
        '#submit' => ['::deleteProject'],
      ];
    }
    return $form;
  }

  /**
   * Delete project.
   */
  public function deleteProject(array &$form, FormStateInterface $form_state) {
    $project_id = $form_state->get('project_id');
    $project = \Drupal::entityTypeManager()->getStorage('asset')->load($project_id);
    $contacts = \Drupal::entityTypeManager()->getStorage('asset')->loadByProperties(
    ['type' => 'contact', 'project' => $project_id]
     );

    try {
      foreach ($contacts as $contact) {
        $contact->delete();
      }
      $project->delete();
      $form_state->setRedirect('cig_pods.dashboard');
    }
    catch (\Exception $e) {
      $this
        ->messenger()
        ->addError($e->getMessage());
    }

  }

  /**
   * Returns True if all values in array is unique, false otherwise.
   */
  public function arrayValuesAreUnique($array) {
    $count_dict = array_count_values($array);

    foreach ($count_dict as $value) {
      if ($value != 1) {
        return FALSE;
      }
    }
    return TRUE;

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Minus 1 because there is an entry with key 'actions' for the "Add Another
    // Producer Button".
    $num_producers = count($form['names_fieldset']) - 1;
    // Minus 1 as above.
    $num_contacts = count($form['names_fieldset']) - 1;

    $producers = [];
    for ($i = 0; $i < $num_producers; $i++) {
      $producer_id = $form['names_fieldset'][$i]['producer_name']['#value'];
      $producers[$i] = $producer_id;
    }
    // Check $producers array for duplicate values.
    if (!$this->arrayValuesAreUnique($producers)) {
      $form_state->setError(
      $form['names_fieldset'],
      $this->t('Each Producer selection must be unique'),
      );
    }

    $contact_names = [];
    for ($i = 0; $i < $num_contacts; $i++) {
      $contact_name_id = $form['names_fieldset'][$i]['contact_name']['#value'];
      $contact_names[$i] = $contact_name_id;
    }

    // Check $contact_names array for duplicate values.
    if (!$this->arrayValuesAreUnique($contact_names)) {
      $form_state->setError(
      $form['names_fieldset'],
      $this->t('Each contact name selection must be unique'),
      );
    }
  }

  /**
   * Redirect to PODS dashboard.
   */
  public function dashboardRedirect(array &$form, FormStateInterface $form_state) {
    $form_state->setRedirect('cig_pods.dashboard');
  }

  /**
   * Get form entity mapping.
   */
  public function getFormEntityMapping() {
    $mapping = [];

    $mapping['name'] = 'name';
    $mapping['field_project_agreement_number'] = 'field_project_agreement_number';
    $mapping['field_funding_amount'] = 'field_funding_amount';
    $mapping['field_summary'] = 'field_summary';
    $mapping['field_awardee'] = 'field_awardee';
    $mapping['field_grant_type'] = 'field_grant_type';

    return $mapping;

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $is_create = $form_state->get('operation') === 'create';
    $contact_options = $form['names_fieldset'][0]['contact_name']['#options'];

    if ($is_create) {
      $mapping = $this->getFormEntityMapping();

      $project_submission = [];

      $project_submission['type'] = 'project';

      // Single value fields can be mapped in.
      foreach ($mapping as $form_elem_id => $entity_field_id) {
        // If mapping not in form or value is empty string.
        if ($form[$form_elem_id] === NULL || $form[$form_elem_id] === '') {
          continue;
        }
        $project_submission[$entity_field_id] = $form[$form_elem_id]['#value'];
      }
      // Read from multivalued checkbox.
      $checked_resource_concerns = Checkboxes::getCheckedCheckboxes($form_state->getValue('field_resource_concerns'));

      $project_submission['field_resource_concerns'] = $checked_resource_concerns;

      // Minus 1 because there is an entry with key 'actions'.
      $num_producers = count($form['names_fieldset']) - 1;
      // As above.
      $num_contacts = count($form['names_fieldset']) - 1;

      $producers = [];
      for ($i = 0; $i < $num_producers; $i++) {
        $producers[$i] = $form['names_fieldset'][$i]['producer_name']['#value'];
      }

      $project_submission['field_producer_contact_name'] = $producers;

      $contacts = [];

      for ($i = 0; $i < $num_contacts; $i++) {
        $contact_submission['type'] = 'contact';

        $contact_type = $form['names_fieldset'][$i]['contact_type']['#value'];
        $contact_eauth_id = $form['names_fieldset'][$i]['contact_name']['#value'];

        if ($contact_eauth_id === '' || $contact_type === '' || $contact_eauth_id === NULL || $contact_type === NULL) {
          continue;
        }

        $contact_submission['field_contact_type'] = $contact_type;
        $contact_submission['name'] = $contact_options[$contact_eauth_id];
        $contact_submission['eauth_id'] = $contact_eauth_id;
        $contact = Asset::create($contact_submission);

        array_push($contacts, $contact);
      }

      $project = Asset::create($project_submission);
      $project->save();

      foreach ($contacts as $contact) {
        $contact->set('project', $project->id());
        $contact->save();
      }

      $form_state->setRedirect('cig_pods.dashboard');
    }
    else {
      $project_id = $form_state->get('project_id');
      $project = \Drupal::entityTypeManager()->getStorage('asset')->load($project_id);

      // Minus 1 because there is an entry with key 'actions'.
      $num_producers = count($form['names_fieldset']) - 1;
      // As above.
      $num_contacts = count($form['names_fieldset']) - 1;

      $project_submission['field_producer_contact_name'] = $producers;

      $contacts = [];
      for ($i = 0; $i < $num_contacts; $i++) {
        $contact_submission['type'] = 'contact';

        $contact_type = $form['names_fieldset'][$i]['contact_type']['#value'];
        $contact_eauth_id = $form['names_fieldset'][$i]['contact_name']['#value'];

        if ($contact_eauth_id === '' || $contact_type === '' || $contact_eauth_id === NULL || $contact_type === NULL) {
          continue;
        }

        $contact_submission['field_contact_type'] = $contact_type;
        $contact_submission['name'] = $contact_options[$contact_eauth_id];
        $contact_submission['eauth_id'] = $contact_eauth_id;
        $contact = Asset::create($contact_submission);

        array_push($contacts, $contact);
      }

      foreach ($contacts as $contact) {
        $contact->set('project', $project->id());
        $contact->save();
      }

      $pre_existsing_contacts = $form_state->get('contacts');
      $this->deleteContacts($pre_existsing_contacts);

      $project_name = $form_state->getValue('name');
      $agreement_number = $form_state->getValue('field_project_agreement_number');
      $field_resource_concerns = $form_state->getValue('field_resource_concerns');
      $field_funding_amount = $form_state->getValue('field_funding_amount');
      $summary = $form_state->getValue('field_summary');
      $field_awardee = $form_state->getValue('field_awardee');
      $field_grant_type = $form_state->getValue('field_grant_type');

      $project->set('name', $project_name);
      $project->set('field_project_agreement_number', $agreement_number);
      $project->set('field_resource_concerns', $field_resource_concerns);
      $project->set('field_funding_amount', $field_funding_amount);
      $project->set('field_summary', $summary);
      $project->set('field_awardee', $field_awardee);
      $project->set('field_awardee_eauth_id', $contact_eauth_ids);
      $project->set('field_grant_type', $field_grant_type);
      $project->save();
      $form_state->setRedirect('cig_pods.dashboard');
    }
  }

  /**
   * Delete contacts.
   */
  public function deleteContacts(array $pre_existsing_contacts) {

    try {
      foreach ($pre_existsing_contacts as $contact) {
        $contact->delete();
      }
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
    return 'project_create_form';
  }

  /**
   * Ajax callback for adding contact row.
   */
  public function addContactRowCallback(array &$form, FormStateInterface $form_state) {
    return $form['names_fieldset'];
  }

  /**
   * Add producer row.
   */
  public function addProducerRow(array &$form, FormStateInterface $form_state) {
    $num_producers = $form_state->get('num_producers');
    $num_producer_lines = $form_state->get('num_producer_lines');
    $form_state->set('num_producers', $num_producers + 1);
    $form_state->set('num_producer_lines', $num_producer_lines + 1);
    $form_state->setRebuild();
  }

  /**
   * Add contact row.
   */
  public function addContactRow(array &$form, FormStateInterface $form_state) {
    $num_contacts = $form_state->get('num_contacts');
    $num_contact_lines = $form_state->get('num_contact_lines');
    $form_state->set('num_contacts', $num_contacts + 1);
    $form_state->set('num_contact_lines', $num_contact_lines + 1);
    $form_state->setRebuild(TRUE);
  }

  /**
   * Remove contact.
   */
  public function removeContactCallback(array &$form, FormStateInterface $form_state) {
    $trigger = $form_state->getTriggeringElement();
    $num_line = $form_state->get('num_contact_lines');
    $indexToRemove = str_replace('delete-contact-', '', $trigger['#name']);

    // Remove the fieldset from $form (the easy way)
    unset($form['names_fieldset'][$indexToRemove]);

    // Keep track of removed fields so we can add new fields at the bottom
    // Without this they would be added where a value was removed.
    $removed_contacts = $form_state->get('removed_contacts');
    $removed_contacts[] = $indexToRemove;

    $form_state->set('removed_contacts', $removed_contacts);
    $form_state->set('num_contact_lines', $num_line - 1);

    // Rebuild form_state.
    $form_state->setRebuild();
  }

}
