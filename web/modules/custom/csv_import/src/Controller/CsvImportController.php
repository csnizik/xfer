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
  public function test() {
    return [
      '#children' => '<form action="/csv_import/process" enctype="multipart/form-data" method="post">
      <input type="file" id="file" name="file">
      <input type="submit">
    </form>',
    ];
  }


  public function process() {
    $file = \Drupal::request()->files->get("file");
    $fName = $file->getClientOriginalName();
    $fLoc = $file->getRealPath();
    $csv = array_map('str_getcsv', file($fLoc));


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

    $operation_taxonomy_name = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($operation->get('field_operation')->target_id);
    $input_taxonomy_name = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($csv[1][2]);
    $input_submission['name'] = $operation_taxonomy_name->getName() . "_" . $input_taxonomy_name->getName() . "_" . $csv[1][1];

    
    $input_to_save = Asset::create($input_submission);

    $cost_submission = [];
    $cost_submission ['type'] = 'cost_sequence';
    $cost_submission ['field_cost_type'] = $csv[1][8];
    $cost_submission ['field_cost'] = $csv[1][9];

    $other_cost = Asset::create($cost_submission);

    $input_to_save->set('field_input_cost_sequences', $other_cost);
    $input_to_save->save();
    
    $operation->get('field_input')[] = $input_to_save->id();
    $operation->save();

    return [
      "#children" => "uploaded.",
    ];
    
  }

}