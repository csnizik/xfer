<?php

namespace Drupal\pods_config_overrides\Config;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryOverrideInterface;
use Drupal\Core\Config\StorageInterface;
use Drupal\Core\Site\Settings;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * PODS config override class.
 */
class PODSConfigOverrides implements ConfigFactoryOverrideInterface {

  /**
   * The site settings.
   *
   * @var \Drupal\Core\Site\Settings
   */
  protected $settings;

  /**
   * Constructs a new PODSConfigOverrides instance.
   *
   * @param \Drupal\Core\Site\Settings $settings
   *   The site settings.
   */
  public function __construct(Settings $settings) {
    $this->settings = $settings;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('settings'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function loadOverrides($names) {
    $overrides = [];

    // Override the paths used by the scss compiler module
    if (in_array('scss.settings', $names)) {
      $overrides['scss.settings']['scss_directory'] = '../../../modules/custom/cig_pods/css';
      $overrides['scss.settings']['css_directory'] = '../../../modules/custom/cig_pods/css';

    }

    return $overrides;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheSuffix() {
    return 'UsdaEAuthOverride';
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata($name) {
    return new CacheableMetadata();
  }

  /**
   * {@inheritdoc}
   */
  public function createConfigObject($name, $collection = StorageInterface::DEFAULT_COLLECTION) {
    return NULL;
  }

}
