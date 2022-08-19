<?php

namespace Drupal\cig_pods\Plugin\Asset\AssetType;
use Drupal\farm_field\FarmFieldFactory;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;
/**
   * Provides the CIG Project asset type.
   *
   * @AssetType(
   * id = "pasture_health_assessment",
   * label = @Translation("Pasture Health Assessment"),
   * )
   */
class PastureHealthAssessment extends FarmAssetType {

    public function buildFieldDefinitions(){
        $fields = parent::buildFieldDefinitions();

        // Note that $fields['name'] is already populated at this point

        $field_info = [
            // 'pasture_health_assessment_shmu'
            'pasture_health_assessment_shmu' => [
                'label'=> 'Soil Health Management Unit',
                'type'=> 'entity_reference',
                'target_type'=> 'asset',
                'target_bundle'=> 'soil_health_management_unit',
                'required' => TRUE,
                'description' => '',
            ],
            'pasture_health_assessment_land_use' => [
                'label'=> 'Land Use',
                'type'=> 'entity_reference',
                'target_type'=> 'taxonomy_term',
                'target_bundle'=> 'd_land_use',
                'required' => TRUE,
                'description' => '',
            ],
            'pasture_health_assessment_erosion_sheet' => [
                'label'=> 'Erosion (Sheet and Rill)',
                'type'=> 'string',
                'required' => FALSE,
                'description' => '',
            ],
            'pasture_health_assessment_erosion_gullies' => [
                'label'=> 'Erosion (Gullies if present)',
                'type'=> 'string',
                'required' => FALSE,
                'description' => '',
            ],
            'pasture_health_assessment_erosion_wind_scoured' => [
                'label'=> 'Erosion, Wind-Scoured and/or Depositional Areas',
                'type'=> 'string',
                'required' => FALSE,
                'description' => '',
            ],
            'pasture_health_assessment_erosion_streambank' => [
                'label'=> 'Erosion (Streambank or Shoreline)',
                'type'=> 'string',
                'required' => FALSE,
                'description' => '',
            ],
            'pasture_health_assessment_water_flow_patterns' => [
                'label'=> 'Water flow patterns',
                'type'=> 'string',
                'required' => FALSE,
                'description' => '',
            ],
            'pasture_health_assessment_bare_ground' => [
                'label'=> 'Bare Ground (Percent)',
                'type'=> 'string',
                'required' => FALSE,
                'description' => '',
            ],
            'pasture_health_assessment_padestals' => [
                'label'=> 'Pedestals and/or Terracettes',
                'type'=> 'string',
                'required' => FALSE,
                'description' => '',
            ],
            'pasture_health_assessment_litter_movement' => [
                'label'=> 'Litter movement (Wind or Water)',
                'type'=> 'string',
                'required' => FALSE,
                'description' => '',
            ],
            'pasture_health_assessment_composition' => [
                'label'=> 'Effects of Plant Community Composition and Distribution on Infiltration and Runoff',
                'type'=> 'string',
                'required' => FALSE,
                'description' => '',
            ],
            'pasture_health_assessment_soil_surface' => [
                'label'=> 'Soil surface loss or degratation',
                'type'=> 'string',
                'required' => FALSE,
                'description' => '',
            ],
            'pasture_health_assessment_compaction_layer' => [
                'label'=> 'Compaction Layer',
                'type'=> 'string',
                'required' => FALSE,
                'description' => '',
            ],
            'pasture_health_assessment_live_plant' => [
                'label'=> 'Live plant foliar cover (hydrologic and erosion benefits)',
                'type'=> 'string',
                'required' => FALSE,
                'description' => '',
            ],
            'pasture_health_assessment_forage_plant' => [
                'label'=> 'Forage Plant Diversity',
                'type'=> 'string',
                'required' => FALSE,
                'description' => '',
            ],
            'pasture_health_assessment_percent_desirable' => [
                'label'=> 'Percent Desirable Forage Plants (for Identified Livestock Class)',
                'type'=> 'string',
                'required' => FALSE,
                'description' => '',
            ],
            'pasture_health_assessment_invasive_plants' => [
                'label'=> 'Invasive Plants',
                'type'=> 'string',
                'required' => FALSE,
                'description' => '',
            ],
            'pasture_health_assessment_annual_production' => [
                'label'=> 'Annual Production',
                'type'=> 'string',
                'required' => FALSE,
                'description' => '',
            ],
            'pasture_health_assessment_plant_vigor' => [
                'label'=> 'Plant Vigor with an Emphasis on Reproductive Capability of Perennial',
                'type'=> 'string',
                'required' => FALSE,
                'description' => '',
            ],
            'pasture_health_assessment_dying_plants' => [
                'label'=> 'Dead or Dying Plants or Plant',
                'type'=> 'string',
                'required' => FALSE,
                'description' => '',
            ],
            'pasture_health_assessment_little_cover' => [
                'label'=> 'Litter cover and depth',
                'type'=> 'string',
                'required' => FALSE,
                'description' => '',
            ],
            'pasture_health_assessment_nontoxic_legumes' => [
                'label'=> 'Percentage Nontoxic legumes',
                'type'=> 'string',
                'required' => FALSE,
                'description' => '',
            ],
            'pasture_health_assessment_uniformity' => [
                'label'=> 'Uniformity of Use',
                'type'=> 'string',
                'required' => FALSE,
                'description' => '',
            ],
            'pasture_health_assessment_livestock' => [
                'label'=> 'Livestock Concentration Areas',
                'type'=> 'string',
                'required' => FALSE,
                'description' => '',
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