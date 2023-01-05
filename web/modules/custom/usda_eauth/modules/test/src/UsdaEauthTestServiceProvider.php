<?php

namespace Drupal\usda_eauth_test;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Drupal\Core\DependencyInjection\ServiceProviderInterface;

/**
 * Override the usda_eauth.zroles service with a testing class.
 */
class UsdaEauthTestServiceProvider extends ServiceProviderBase implements ServiceProviderInterface {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    $definition = $container->getDefinition('usda_eauth.zroles');
    $definition->setClass('Drupal\usda_eauth_test\ZRolesUtilitiesTest');
  }

}
