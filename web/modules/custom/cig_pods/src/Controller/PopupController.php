<?php

namespace Drupal\cig_pods\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Controller\ControllerBase;

class PopupController extends ControllerBase {

  public function openConfirmModal() {
    $response = new AjaxResponse();
    $modal_form = $this->formBuilder()->getForm('Drupal\cig_pods\Form\ConfirmModalForm');
    $options = [
      'width' => '50%',
      'classes' => 'confirm-modal-popup',
      'dialogClass' => 'confirm-dialog-popup',
    ];
    $response->addCommand(new OpenModalDialogCommand('Are you sure you want to cancel?', $modal_form, $options));
    return $response;
  }

}