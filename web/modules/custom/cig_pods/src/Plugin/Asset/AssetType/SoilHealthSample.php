<?php

namespace Drupal\cig_pods\Plugin\Log\LogType;

use Drupal\farm_entity\Plugin\Log\AssetType\FarmAssetType;
/**
   * Provides the Soil Health Sample asset type.
   *
   * @AssetType(
   * id = "soil_health_sample",
   * label = @Translation("Soil Health Sample"),
   * )
   */
class SoilHealthSample extends FarmAssetType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
   $fields = parent::buildFieldDefinitions();

  //  // Lot number.
  //  $options = [
  //    'type' => 'string',
  //    'label' => $this->t('Field Pass'),
  //    'description' => $this->t('If this operation is a part of a grouped field pass, enter the field pass number here.'),
  //    'weight' => [
  //      'form' => 20,
  //      'view' => 20,
  //    ],
  //  ];
  //  $fields['field_pass'] = $this->farmFieldFactory->bundleFieldDefinition($options);

   return $fields;
 }     


}