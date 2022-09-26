<?php

namespace Drupal\usda_eauth_test\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;


class TestSetAdmin extends FormBase {

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
        
        $eAuthId = '28200310160021007137';
        $email =  'Thomas.Gust@ia.usda.gov';
        $firstName =  'THOMAS';
        $lastName =  'GUST';
        $roleId = '5200';
        $roleName =  'CIG App Admin';
        $roleEnum =  'CIG_App_Admin';
        $roleDisplay =  'CIG App Admin';

        /*Store the user info in the session */
        $session = \Drupal::request()->getSession();
        $session->set('eAuthId', $eAuthId);
        $session->set('EmailAddress', $email);
        $session->set('FirstName', $firstName);
        $session->set('LastName', $lastName);
        $session->set('ApplicationRoleId', $roleId);
        $session->set('ApplicationRoleName', $roleName);
        $session->set('ApplicationRoleEnumeration', $roleEnum);
        $session->set('ApplicationRoleDisplay', $roleDisplay);

      // Redirect to the PODS dashboard.
      (new RedirectResponse('/pods'))->send();

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
    return 'test_set_admin';
  }

}

