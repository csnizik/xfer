<?php

namespace Drupal\cig_pods\Form;

use Drupal\Core\Entity\EntityReferenceSelection\SelectionPluginManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base form for PODS.
 */
class PodsFormBase extends FormBase {

  /**
   * The selection plugin manager service.
   *
   * @var \Drupal\Core\Entity\EntityReferenceSelection\SelectionPluginManagerInterface
   */
  protected $selectionPluginManager;

  /**
   * Constructs a new PodsFormBase instance.
   *
   * @param \Drupal\Core\Entity\EntityReferenceSelection\SelectionPluginManagerInterface $selection_plugin_manager
   *   The selection plugin manager service.
   */
  public function __construct(SelectionPluginManagerInterface $selection_plugin_manager) {
    $this->selectionPluginManager = $selection_plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.entity_reference_selection'),
    );
  }

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
    $selection_handler = $this->selectionPluginManager->getInstance($selection_options);
    $referenceable_entities = $selection_handler->getReferenceableEntities();
    if (!empty($referenceable_entities[$bundle])) {
      $options = $referenceable_entities[$bundle];
    }
    return $options;
  }

}
