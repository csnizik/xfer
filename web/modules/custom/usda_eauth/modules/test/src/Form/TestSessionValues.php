<?php

namespace Drupal\usda_eauth_test\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Site\Settings;  //used to access Settings


class TestSessionValues extends FormBase {

    public function buildForm(array $form, FormStateInterface $form_state, $options = NULL){

        $form['actions'] = [
            '#type' => 
            'actions',
        ];

        $form['actions']['cancel'] = [
            '#type' => 
            'submit',
            '#value' => 
              $this->t('Cancel'),
        ];
        
         /* Display the the current users zrole info from the session variables */
         $session = \Drupal::request()->getSession();
         $sessionEA = $session->get('eAuthId');
         $sessionEM = $session->get('EmailAddress');
         $sessionFN = $session->get('FirstName');
         $sessionLN = $session->get('LastName');
         $sessionID = $session->get('ApplicationRoleId');
         $sessionRN = $session->get('ApplicationRoleName');
         $sessionRE = $session->get('ApplicationRoleEnumeration');
         $sessionRD = $session->get('ApplicationRoleDisplay');

         print_r( 'Current user info: <br>' .
                  ' eAuthId = '.  $sessionEA . '<br>' .
                  ' Email = '.  $sessionEM . '<br>' .
                  ' First Name = '.  $sessionFN . '<br>' .
                  ' Last Name = '.  $sessionLN . '<br>' .
                  ' Role ID  = '.  $sessionID . '<br>' .
                  ' Role Name  = '.  $sessionRN . '<br>' .
                  ' Role Enum = '.  $sessionRE . '<br>' .
                  ' Role Display Name = '.  $sessionRD . '<br>' );

          $endpoint = Settings::get('zroles_url', 'd1');
          $nrtApplicationId = Settings::get('nrtApplicationId', 'd2');
          $wsSecuredToken = Settings::get('wsSecuredToken', 'd3');

          $eAuthTokenUrl = Settings::get('eAuthBaseUrl', 'd5') . '/token';
          $client_id = Settings::get('client_id', 'd6');
          $client_secret = Settings::get('client_secret', 'd7');

          print_r('<br> Current settings info: <br>' .       
                  ' endpoint = ' . $endpoint . '<br>' .
                  ' nrtApplicationId = ' . $nrtApplicationId . '<br>' .
                  ' wsSecuredToken = ' . $wsSecuredToken . '<br>' .
                  ' eAuthTokenUrl = ' . $eAuthTokenUrl . '<br>' .
                  ' client_id = ' . $client_id . '<br>' .
                  ' client_secret = ' . $client_secret . '<br>' );

      return $form;
    }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state){
  }

  /**
   * {@inheritdoc}
   */

  public function submitForm(array &$form, FormStateInterface $form_state) {
     (new RedirectResponse('/user/login'))->send();
  }

  /**
   * {@inheritdoc}
   */

  public function getFormId() {
    return 'test_session_values';
  }





}

