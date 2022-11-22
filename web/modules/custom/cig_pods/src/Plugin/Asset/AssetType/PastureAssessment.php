<?php

namespace Drupal\cig_pods\Plugin\Asset\AssetType;

use Drupal\farm_field\FarmFieldFactory;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;

/**
 * Provides the CIG Project asset type.
 *
 * @AssetType(
 * id = "pasture_assessment",
 * label = @Translation("Pasture Assessment"),
 * )
 */
class PastureAssessment extends FarmAssetType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();

    // Note that $fields['name'] is already populated at this point.
    $field_info = [

      'shmu' => [
        'label' => 'Soil Health Management Unit',
        'type' => 'entity_reference',
        'target_type' => 'asset',
        'target_bundle' => 'soil_health_management_unit',
        'required' => TRUE,
        'description' => '',
      ],
      'pasture_assessment_date' => [
        'label' => 'Date',
        'type' => 'timestamp',
        'required' => TRUE,
        'description' => '',
      ],
      'pasture_assessment_land_use' => [
        'label' => 'Land Use',
        'type' => 'entity_reference',
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'd_land_use',
        'required' => TRUE,
        'description' => '',

      ],
      'pasture_assessment_desirable_plants' => [
        'label' => 'Rills',
        'type' => 'string',
        'required' => FALSE,
        'description' => '',

      ],
      'pasture_assessment_Legume_dry_weight' => [
        'label' => 'Percent Legume by Dry Weight',
        'type' => 'string',
        'required' => FALSE,
        'description' => '',

      ],
      'pasture_assessment_live_plant_cover' => [
        'label' => 'Live (includes dormant) Plant Cover',
        'type' => 'string',
        'required' => FALSE,
        'description' => '',

      ],
      'pasture_assessment_diversity_dry_weight' => [
        'label' => 'Plant Diversity by Dry Weight',
        'type' => 'string',
        'required' => FALSE,
        'description' => '',
      ],
      'pasture_assessment_litter_soil_cover' => [
        'label' => 'Plant Residue and Litter as Soil Cover',
        'type' => 'string',
        'required' => FALSE,
        'description' => '',
      ],
      'pasture_assessment_grazing_utilization_severity' => [
        'label' => 'Grazing Utilization and Severity',
        'type' => 'string',
        'required' => FALSE,
        'description' => '',
      ],
      'pasture_assessment_livestock_concentration' => [
        'label' => 'Livestock Concentration',
        'type' => 'string',
        'required' => FALSE,
        'description' => '',
      ],
      'pasture_assessment_soil_compaction' => [
        'label' => 'Soil Compaction and Soil Regenerative Features',
        'type' => 'string',
        'required' => FALSE,
        'description' => '',
      ],
      'pasture_assessment_plant_rigor' => [
        'label' => 'Plant Rigor',
        'type' => 'string',
        'required' => FALSE,
        'description' => '',
      ],
      'pasture_assessment_erosion' => [
        'label' => 'Erosion',
        'type' => 'string',
        'required' => FALSE,
        'description' => '',
      ],
      'pasture_assessment_condition_store' => [
        'label' => 'Pasture Condition Score',
        'type' => 'string',
        'required' => FALSE,
        'description' => '',
      ],
      'project' => [
        'label' => 'Project',
        'type' => 'entity_reference',
        'target_type' => 'asset',
        'target_bundle' => 'project',
        'required' => TRUE,
        'multiple' => TRUE,
      ],

    ];
    $farmFieldFactory = new FarmFieldFactory();
    foreach ($field_info as $name => $info) {
      $fields[$name] = $farmFieldFactory->bundleFieldDefinition($info)
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);
    }

    return $fields;

  }

}
