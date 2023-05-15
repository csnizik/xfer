<?php
namespace Drupal\csv_import\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\asset\Entity\Asset;
/**
 * Provides route responses for the Example module.
 */
class CsvImportController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function upload() {
    return [
      '#children' => '
        inputs:
        <form action="/csv_import/upload_inputs" enctype="multipart/form-data" method="post">
          <input type="file" id="file" name="file">
          <input type="submit">
        </form>
        
        operations:
        <form action="/csv_import/upload_operations" enctype="multipart/form-data" method="post">
          <input type="file" id="file" name="file">
          <input type="submit">
        </form>

        soil health:
        <form action="/csv_import/upload_soil_health_sample" enctype="multipart/form-data" method="post">
          <input type="file" id="file" name="file">
          <input type="submit">
        </form>

        combo:
        <form action="/csv_import/upload_combo" enctype="multipart/form-data" method="post">
          <input type="file" id="file" name="file">
          <input type="submit">
        </form>
    ',
    ];
  }

  public function process_combo_operations($csv) {
    foreach($csv as $csv_line) {
      if($csv_line[0] === "Operation") {
        $shmu = \Drupal::entityTypeManager()->getStorage('asset')->load($csv_line[2]);
        $project = \Drupal::entityTypeManager()->getStorage('asset')->load($shmu->get('project')->target_id);

        // $field_input = \Drupal::entityTypeManager()->getStorage('asset')->load($csv_line[2]);

        // $operation_submission = [];
        // $operation_submission['type'] = 'operation';

        // $operation_submission['shmu'] = $shmu;
        // $operation_submission['field_operation_date'] = strtotime($csv_line[1]);
        // $operation_submission['field_operation'] = $csv_line[3];
        // $operation_submission['field_ownership_status'] = $csv_line[4];
        // $operation_submission['field_tractor_self_propelled_machine'] = $csv_line[5];
        // $operation_submission['field_row_number'] = $csv_line[6];
        // $operation_submission['field_width'] = $csv_line[7];
        // $operation_submission['field_horsepower'] = $csv_line[8];
        // $operation_submission['project'] = $project;

        // $operation_to_save = Asset::create($operation_submission);
        
        // $operation_to_save->save();
      }
    }

    return [
      "#children" => "saved " . nl2br(print_r($shmu, true)) . " operations.",
    ];
    
  }

  public function process_inputs() {
    $file = \Drupal::request()->files->get("file");
    $fName = $file->getClientOriginalName();
    $fLoc = $file->getRealPath();
    $csv = array_map('str_getcsv', file($fLoc));
    array_shift($csv);
    $out = 0;

    foreach($csv as $csv_line) {

      $operation = \Drupal::entityTypeManager()->getStorage('asset')->load($csv_line[0]);
      $project = \Drupal::entityTypeManager()->getStorage('asset')->load($operation->get('project')->target_id);

      $input_submission = [];
      $input_submission['type'] = 'input';
      $input_submission['field_input_date'] = strtotime($csv_line[1]);
      $input_submission['field_input_category'] = $csv_line[2];
      $input_submission['field_input'] = $csv_line[3];
      $input_submission['field_unit'] = $csv_line[4];
      $input_submission['field_rate_units'] = $csv_line[5];
      $input_submission['field_cost_per_unit'] = $csv_line[6];
      $input_submission['field_custom_application_unit'] = $csv_line[7];
      $input_submission['project'] = $project;

      $operation_taxonomy_name = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($operation->get('field_operation')->target_id);
      $input_taxonomy_name = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($csv_line[2]);
      $input_submission['name'] = $operation_taxonomy_name->getName() . "_" . $input_taxonomy_name->getName() . "_" . $csv_line[1];

      
      $input_to_save = Asset::create($input_submission);

      $cost_submission = [];
      $cost_submission ['type'] = 'cost_sequence';
      $cost_submission ['field_cost_type'] = $csv_line[8];
      $cost_submission ['field_cost'] = $csv_line[9];

      $other_cost = Asset::create($cost_submission);

      $input_to_save->set('field_input_cost_sequences', $other_cost);
      $input_to_save->save();
      
      $operation->get('field_input')[] = $input_to_save->id();
      $operation->save();

      $out = $out + 1;// . nl2br(print_r($csv_line, true)) . "\n";
    }

    return [
      "#children" => "added " . $out . " inputs.",
    ];
    
  }

  public function process_operations() {
    $file = \Drupal::request()->files->get("file");
    $fName = $file->getClientOriginalName();
    $fLoc = $file->getRealPath();
    $csv = array_map('str_getcsv', file($fLoc));
    array_shift($csv);
    $out = 0;

    foreach($csv as $csv_line) {

      $shmu = \Drupal::entityTypeManager()->getStorage('asset')->load($csv_line[0]);
      $project = \Drupal::entityTypeManager()->getStorage('asset')->load($shmu->get('project')->target_id);

      $field_input = \Drupal::entityTypeManager()->getStorage('asset')->load($csv_line[2]);

      $operation_submission = [];
      $operation_submission['type'] = 'operation';

      $operation_submission['shmu'] = $shmu;
      $operation_submission['field_operation_date'] = strtotime($csv_line[1]);
      $operation_submission['field_input'] = $field_input;
      $operation_submission['field_operation'] = $csv_line[3];
      $operation_submission['field_ownership_status'] = $csv_line[4];
      $operation_submission['field_tractor_self_propelled_machine'] = $csv_line[5];
      $operation_submission['field_row_number'] = $csv_line[6];
      $operation_submission['field_width'] = $csv_line[7];
      $operation_submission['field_horsepower'] = $csv_line[8];
      $operation_submission['project'] = $project;

      $operation_to_save = Asset::create($operation_submission);
      
      $operation_to_save->save();
      $out = $out + 1;
    }
    return [
      "#children" => "saved " . $out . " operations.",
    ];
    
  }

  public function process_soil_health_sample() {
    $file = \Drupal::request()->files->get("file");
    $fName = $file->getClientOriginalName();
    $fLoc = $file->getRealPath();
    $csv = array_map('str_getcsv', file($fLoc));
    array_shift($csv);
    $out = 0;

    foreach($csv as $csv_line) { 

      $shmu = array_pop(\Drupal::entityTypeManager()->getStorage('asset')->loadByProperties(['type' => 'soil_health_management_unit', 'name' => $csv_line[1]]));
      $project = \Drupal::entityTypeManager()->getStorage('asset')->load($shmu->get('project')->target_id);

      $soil_health_sample_submission = [];
      $soil_health_sample_submission['type'] = 'soil_health_sample';
      $soil_health_sample_submission['soil_id'] = $csv_line[0];
      $soil_health_sample_submission['shmu'] = array_pop(\Drupal::entityTypeManager()->getStorage('asset')->loadByProperties(['type' => 'soil_health_management_unit', 'name' => $csv_line[1]]));
      $soil_health_sample_submission['field_soil_sample_collection_dat'] = \DateTime::createFromFormat("D, m/d/Y - G:i", $csv_line[2])->getTimestamp();
      $soil_health_sample_submission['field_equipment_used'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_equipment', 'name' => $csv_line[3]]));
      $soil_health_sample_submission['field_diameter'] = $csv_line[4];
      $soil_health_sample_submission['name'] = $csv_line[5];
      $soil_health_sample_submission['field_plant_stage_at_sampling'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_plant_stage', 'name' => $csv_line[6]]));
      $soil_health_sample_submission['field_sampling_depth'] = $csv_line[7];
      $soil_health_sample_submission['field_soil_sample_geofield'] = $csv_line[8];
      $soil_health_sample_submission['project'] = $project;
      
      $soil_health_sample_to_save = Asset::create($soil_health_sample_submission);
      
      $soil_health_sample_to_save->save();
      $out = $out + 1;

    }
    return [
      "#children" => "saved " . $out . " soil health sample.",
    ];
  }

  public function process_combo() {
    // grab the contents of the file and same some info
    $file = \Drupal::request()->files->get("file");
    $file_name = $file->getClientOriginalName();
    $item_count = 0;
    $file_loc = $file->getRealPath();
    
    $csv = array_map('str_getcsv', file($file_loc));
    array_shift($csv);

    // holds items being added by reference number
    $operation_ref_nums = [];
    $input_ref_nums = [];
    
    // break sheet down into sections
    $current_type = "";

    foreach($csv as $csv_line) {
      if ($csv_line[0] != "") { // header row
        $current_type = $csv_line[0];
      } else { // object
        $items[$current_type][] = $csv_line;
      }
    }

    // process each section in turn
    foreach($items["Operation"] as $csv_line) {
        $shmu = \Drupal::entityTypeManager()->getStorage('asset')->load($csv_line[2]);
        $project = \Drupal::entityTypeManager()->getStorage('asset')->load($shmu->get('project')->target_id);
      
        $operation_submission = [];
        $operation_submission['type'] = 'operation';
  
        $operation_submission['shmu'] = $shmu;
        $operation_submission['field_operation_date'] = strtotime($csv_line[3]);
        $operation_submission['field_operation'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_operation_type', 'name' => $csv_line[9]]));
        $operation_submission['field_ownership_status'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_equipment_ownership', 'name' => $csv_line[4]]));
        $operation_submission['field_tractor_self_propelled_machine'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_tractor_self_propelled_machine', 'name' => $csv_line[5]]));;
        $operation_submission['field_row_number'] = $csv_line[6];
        $operation_submission['field_width'] = $csv_line[7];
        $operation_submission['field_horsepower'] = $csv_line[8];
        $operation_submission['project'] = $project;
        $operation_to_save = Asset::create($operation_submission);
        $operation_to_save->save();

        $operation_ref_nums[$csv_line[1]] = $operation_to_save;

        $item_count++;

    }

    foreach($items["Input"] as $csv_line) {
      $operation = $operation_ref_nums[$csv_line[2]];
      $project = $operation->id;

      $input_submission = [];
      $input_submission['type'] = 'input';
      $input_submission['field_input_date'] = strtotime($csv_line[3]);
      $input_submission['field_input_category'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_input', 'name' => $csv_line[4]]));
      $input_submission['field_input'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_input', 'name' => $csv_line[5]]));
      $input_submission['field_unit'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_unit', 'name' => $csv_line[6]]));
      $input_submission['field_rate_units'] = $csv_line[7];
      $input_submission['field_cost_per_unit'] = $csv_line[8];
      $input_submission['field_custom_application_unit'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_unit', 'name' => $csv_line[9]]));;

      $operation_taxonomy_name = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($operation->get('field_operation')->target_id);
      $input_taxonomy_name = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_input', 'name' => $csv_line[5]]));
      
      $input_submission['name'] = $operation_taxonomy_name->getName() . "_" . $input_taxonomy_name->getName() . "_" . $csv_line[3];
      $input_submission['project'] = $project;

      $input_to_save = Asset::create($input_submission);
      $input_to_save->save();
        
      $operation->get('field_input')[] = $input_to_save->id();
      $operation->save();

      $input_ref_nums[$csv_line[1]] = $input_to_save;

      $item_count++;

    }

    foreach($items["OpCosts"] as $csv_line) {
     $operation = $operation_ref_nums[$csv_line[1]];

      $cost_submission = [];
      $cost_submission ['type'] = 'cost_sequence';
      $cost_submission ['field_cost_type'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_operation_type', 'name' => $csv_line[2]]));;
      $cost_submission ['field_cost'] = $csv_line[3];

      $other_cost = Asset::create($cost_submission);
      $other_cost->save();


      $new_cost_id = $other_cost->id();
      $old_cost_sequence_target_ids = $operation->get('field_operation_cost_sequences');
      //dpm($cost_sequence_target_ids);
      $cost_sequence_target_ids = [];
      foreach ($old_cost_sequence_target_ids as $val) {
        $cost_sequence_target_ids[] = $val->target_id;
      }

      // add new cost_id to existing sequence and save it back
      $cost_sequence_target_ids[] = $new_cost_id;
      $operation->set('field_operation_cost_sequences', $cost_sequence_target_ids);
      $operation->save();

      $item_count++;
    }
    
    foreach($items["InputCosts"] as $csv_line) {
      $input = $input_ref_nums[$csv_line[1]];

      $cost_submission = [];
      $cost_submission ['type'] = 'cost_sequence';
      $cost_submission ['field_cost_type'] = array_pop(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'd_operation_type', 'name' => $csv_line[2]]));
      $cost_submission ['field_cost'] = $csv_line[3];

      $other_cost = Asset::create($cost_submission);
      $other_cost->save();

      $new_cost_id = $other_cost->id();

      $old_cost_sequence_target_ids = $input->get('field_input_cost_sequences');
      
      $cost_sequence_target_ids = [];
      foreach ($old_cost_sequence_target_ids as $val) {
        $cost_sequence_target_ids[] = $val->target_id;
      }

      // add new cost_id to existing sequence and save it back. is this efficient? no.
      $cost_sequence_target_ids[] = $new_cost_id;
      $input->set('field_input_cost_sequences', $cost_sequence_target_ids);
      $input->save();

      $item_count++;
    }

    $out_str = "";

    $out_str .= "Processed " . "<b>" . $item_count . "</b>" . " items from " . "<b>" . $file_name . "</b>"  . ".";
    $out_str .= "<br /><br />";
    
    $out_str .= "<b>" . count($items["Operation"]) . "</b>" . " Operations.<br />";
    // foreach($operation_ref_nums as $op) {
    //   dpm($op);
    //   $out_str .= "<a href=\"/edit/operation/" . $op->id->target_id ."\">" . "id" . $op->id->target_id  . "</a>";
    // }
    // $out_str .= "<br />";

    $out_str .= "<b>" . count($items["Input"]) . "</b>" . " Inputs.<br />";
    $out_str .= "<b>" . count($items["InputCosts"]) . "</b>" . " Input costs.<br />";
    $out_str .= "<b>" . count($items["OpCosts"]) . "</b>" . " Operation costs.<br />";

    return [
      "#children" => $out_str,
    ];
    
  }
  
  public function process_operations_with_other_costs() {

    $file = \Drupal::request()->files->get("file");
    $fName = $file->getClientOriginalName();
    $fLoc = $file->getRealPath();
    $csv = array_map('str_getcsv', file($fLoc));

    $oc_index =  array_search("other_costs",$csv[0]);

    $csv_oc = $csv[1][$oc_index];

    $result = str_replace( '"', '', $csv_oc);

    $exps = explode("|",$result);

    $csid = [];

    foreach( $exps as $exp){
      
      $cval = explode(",",$exp);
      $cost = $cval[0];
      $cost_type = $cval[1];
      
      //create cost sequence
      $cost_sequence = [];
      $cost_sequence['type'] = 'cost_sequence';
      $cost_sequence['field_cost_type'] = ['target_id' => $cost_type];
      $cost_sequence['field_cost'] = $cost;
      $cost_sequenceN = Asset::create($cost_sequence);

      $cost_sequenceN->save();

      $nid = $cost_sequenceN->id();

      $csid[] = $nid;

    }

    $shmu = \Drupal::entityTypeManager()->getStorage('asset')->load($csv[1][0]);
    $project = \Drupal::entityTypeManager()->getStorage('asset')->load($shmu->get('project')->target_id);

    $field_input = \Drupal::entityTypeManager()->getStorage('asset')->load($csv[1][2]);


    $operation_submission = [];
    $operation_submission['type'] = 'operation';

    $operation_submission['shmu'] = $shmu;
    $operation_submission['field_operation_date'] = strtotime($csv[1][1]);
    //$operation_submission['field_input'] = $field_input;
    $operation_submission['field_operation'] = $csv[1][3];
    $operation_submission['field_ownership_status'] = $csv[1][4];
    $operation_submission['field_tractor_self_propelled_machine'] = $csv[1][5];
    $operation_submission['field_row_number'] = $csv[1][6];
    $operation_submission['field_width'] = $csv[1][7];
    $operation_submission['field_horsepower'] = $csv[1][8];
    $operation_submission['project'] = $project;
    $operation_submission['field_operation_cost_sequences'] = $csid;
    $operation_to_save = Asset::create($operation_submission);


    $operation_to_save->save();

    return [
      "#children" => nl2br(print_r("saved", true)),
    ];
    
  }

}