<?php

namespace Drupal\cig_pods\Plugin\Asset\AssetType;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;
/**
   * Provides the Producer asset type.
   *
   * @AssetType(
   * id = "producer",
   * label = @Translation("Producer"),
   * handlers = {
   *  "form" = {
   *     "add"="Drupal\cig_pods\Form\ProducerForm",
   *  }
   * },
   * )
   */
class Producer extends FarmAssetType {

}