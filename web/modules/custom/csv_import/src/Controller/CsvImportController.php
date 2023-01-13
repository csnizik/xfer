<?php
namespace Drupal\csv_import\Controller;

use Drupal\Core\Controller\ControllerBase;

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
    return [
      "#children" => "uploaded file: " . $fName . "</br>" . nl2br(print_r($csv, true))
    ];
  }

}