<?php

namespace Drupal\cig_pods\Plugin\Log\LogType;

use Drupal\farm_entity\Plugin\Log\LogType\FarmLogType;
/**
   * Provides the Operation log type.
   *
   * @LogType(
   * id = "operation",
   * label = @Translation("Operation"),
   * )
   */
class Operation extends FarmLogType {

  /**
   * {@inheritdoc}
   */
  // public function buildFieldDefinitions() {
  //  $fields = parent::buildFieldDefinitions();

   // Lot number.
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

  //  return $fields;

  //}
 }     

