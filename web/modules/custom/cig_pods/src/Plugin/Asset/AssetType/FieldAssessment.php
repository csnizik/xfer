<?php

namespace Drupal\cig_pods\Plugin\Asset\AssetType;
use Drupal\farm_field\FarmFieldFactory;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;
/**
   * Provides the CIG Project asset type.
   *
   * @AssetType(
   * id = "field_assessment",
   * label = @Translation("Field Assessment"),
   * )
   */
class FieldAssessment extends FarmAssetType {

   public function buildFieldDefinitions(){
      $fields = parent::buildFieldDefinitions();

      // Note that $fields['name'] is already populated at this point

      $field_info = [
         // 'shmu'
         'shmu' => [
             'label'=> 'Soil Health Management Unit',
             'type'=> 'entity_reference',
             'target_type'=> 'asset',
             'target_bundle'=> 'soil_health_management_unit',
             'required' => FALSE,
             'description' => '',
         ],
         'field_assessment_date' => [
             'label'=> 'Date',
             'type'=> 'timestamp',
             'required' => FALSE,
             'description' => '',

         ],
         'field_assessment_soil_cover' => [
             'label'=> 'Soil Cover',
             'type'=> 'entity_reference',
             'target_type'=> 'taxonomy_term',
             'target_bundle'=> 'd_assessment_evaluation',
             'required' => FALSE,
             'description' => '',

         ],
         'field_assessment_residue_breakdown' => [
             'label'=> 'Residue Breakdown',
             'type'=> 'entity_reference',
             'target_type'=> 'taxonomy_term',
             'target_bundle'=> 'd_assessment_evaluation',
             'required' => FALSE,
             'description' => '',

         ],
         'field_assessment_surface_crusts' => [
             'label'=> 'Surface Crusts',
             'type'=> 'entity_reference',
             'target_type'=> 'taxonomy_term',
             'target_bundle'=> 'd_assessment_evaluation',
             'required' => FALSE,
             'description' => '',

         ],
         'field_assessment_ponding' => [
             'label'=> 'Ponding',
             'type'=> 'entity_reference',
             'target_type'=> 'taxonomy_term',
             'target_bundle'=> 'd_assessment_evaluation',
             'required' => FALSE,
             'description' => '',

         ],
         'field_assessment_penetration_resistance' => [
             'label'=> 'Penetration Resistance',
             'type'=> 'entity_reference',
             'target_type'=> 'taxonomy_term',
             'target_bundle'=> 'd_assessment_evaluation',
             'required' => FALSE,
             'description' => '',

         ],
         'field_assessment_water_stable_aggregates' => [
             'label'=> 'Water Stable Aggregates',
             'type'=> 'entity_reference',
             'target_type'=> 'taxonomy_term',
             'target_bundle'=> 'd_assessment_evaluation',
             'required' => FALSE,
             'description' => '',
         ],
         'field_assessment_soil_structure' => [
             'label'=> 'Soil Structure',
             'type'=> 'entity_reference',
             'target_type'=> 'taxonomy_term',
             'target_bundle'=> 'd_assessment_evaluation',
             'required' => FALSE,
             'description' => '',
         ],
         'field_assessment_soil_color' => [
             'label'=> 'Soil Color',
             'type'=> 'entity_reference',
             'target_type'=> 'taxonomy_term',
             'target_bundle'=> 'd_assessment_evaluation',
             'required' => FALSE,
             'description' => '',

         ],
         'field_assessment_plant_roots' => [
            'label'=> 'Soil Color',
            'type'=> 'entity_reference',
            'target_type'=> 'taxonomy_term',
            'target_bundle'=> 'd_assessment_evaluation',
            'required' => FALSE,
            'description' => '',

        ],
         'field_assessment_biological_diversity' => [
             'label'=> 'Biological Diversity',
             'type'=> 'entity_reference',
             'target_type'=> 'taxonomy_term',
             'target_bundle'=> 'd_assessment_evaluation',
             'required' => FALSE,
             'description' => '',

         ],
         'field_assessment_biopores' => [
             'label'=> 'Biopores',
             'type'=> 'entity_reference',
             'target_type'=> 'taxonomy_term',
             'target_bundle'=> 'd_assessment_evaluation',
             'required' => FALSE,
             'description' => '',
         ],
         'field_assessment_rc_soil_organic_matter' => [
             'label'=> 'Soil Organic Matter Resource Concern Identified',
             'type'=> 'boolean',
             'required' => FALSE,
             'description' => '',
         ],
         'field_assessment_rc_aggregate_instability' => [
             'label'=> 'Aggregate Instability Resource Concern Identified',
             'type'=> 'boolean',
             'required' => FALSE,
             'description' => '',

         ],
         'field_assessment_rc_compaction' => [
             'label'=> 'Compation Resource Concern Identified',
             'type'=> 'boolean',
             'required' => FALSE,
             'description' => '',

         ],
         'field_assessment_rc_soil_organism_habitat' => [
             'label'=> 'Soil Organism Habitat Resource Concern Identified',
             'type'=> 'boolean',
             'required' => FALSE,
             'description' => '',

         ],
        'project' =>[
          'label' => 'Project',
          'type' => 'entity_reference',
          'target_type' => 'asset',
          'target_bundle' => 'project',
          'required' => TRUE,
          'multiple' => TRUE,
        ],
      ];
    $farmFieldFactory = new FarmFieldFactory();
    foreach($field_info as $name => $info){
        $fields[$name] = $farmFieldFactory->bundleFieldDefinition($info)
                            -> setDisplayConfigurable('form',TRUE)
                            -> setDisplayConfigurable('view', TRUE);
    }

    return $fields;


   }
}
