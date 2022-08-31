<?php

namespace Drupal\cig_pods\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;

class PodsFormBase extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'pods_form_base';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * Helper method for generating #options lists of entities.
   *
   * @param string $entity_type
   *   The entity type.
   * @param string $bundle
   *   The bundle.
   *
   * @return array
   *   Returns an associative array of entity names, keyed by entity ID.
   */
  protected function entityOptions(string $entity_type, string $bundle) {
    $options = [];

    // If the entity type is "asset", use our custom Entity Reference View to
    // get the list of options. Otherwise, use the default selection handler.
    $selection_options = [
      'handler' => 'default:' . $entity_type,
      'target_type' => $entity_type,
      'target_bundles' => [$bundle],
    ];
    if ($entity_type == 'asset') {
      $selection_options = [
        'handler' => 'views',
        'target_type' => $entity_type,
        'view' => [
          'view_name' => 'pods_asset_er',
          'display_name' => 'entity_reference',
          'arguments' => [$bundle],
        ],
      ];
    }
    $selection_handler = \Drupal::service('plugin.manager.entity_reference_selection')->getInstance($selection_options);
    $referenceable_entities = $selection_handler->getReferenceableEntities();
    if (!empty($referenceable_entities[$bundle])) {
      $options = $referenceable_entities[$bundle];
    }
    return $options;
  }

}
