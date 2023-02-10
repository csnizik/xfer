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
        <form action="/csv_import/process" enctype="multipart/form-data" method="post">
          <input type="file" id="file" name="file">
          <input type="submit">
        </form>
        
        operations:
        <form action="/csv_import/process2" enctype="multipart/form-data" method="post">
          <input type="file" id="file" name="file">
          <input type="submit">
        </form>
    ',
    ];
  }


  public function inputs() {
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



    $operation = \Drupal::entityTypeManager()->getStorage('asset')->load($csv[1][0]);
    $project = \Drupal::entityTypeManager()->getStorage('asset')->load($operation->get('project')->target_id);

    $input_submission = [];
    $input_submission['type'] = 'input';
    $input_submission['field_input_date'] = strtotime($csv[1][1]);
    $input_submission['field_input_category'] = $csv[1][2];
    $input_submission['field_input'] = $csv[1][3];
    $input_submission['field_unit'] = $csv[1][4];
    $input_submission['field_rate_units'] = $csv[1][5];
    $input_submission['field_cost_per_unit'] = $csv[1][6];
    $input_submission['field_custom_application_unit'] = $csv[1][7];
    $input_submission['project'] = $project;
    $input_submission['field_input_cost_sequences'] = $csid;

    $operation_taxonomy_name = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($operation->get('field_operation')->target_id);
    $input_taxonomy_name = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($csv[1][2]);
    $input_submission['name'] = $operation_taxonomy_name->getName() . "_" . $input_taxonomy_name->getName() . "_" . $csv[1][1];
    
    $input_to_save = Asset::create($input_submission);

    $input_to_save->save();
    


    return [
      "#children" => "uploaded.",
    ];
    
  }

  public function operations() {

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