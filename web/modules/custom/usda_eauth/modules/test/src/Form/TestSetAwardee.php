<?php

namespace Drupal\usda_eauth_test\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;


class TestSetAwardee extends FormBase {

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


        $eAuthId = '28200711150011206144332';
        $email =  'william.may@oh.usda.go';
        $firstName =  'William';
        $lastName =  'May';
        $roleId = '5202';
        $roleName =  'NRCS Soil Health Data Steward';
        $roleEnum =  'CIG_NSHDS';
        $roleDisplay =  'NRCS Soil Health Data Steward';

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

         /* redirect to the proper route based on role */
         switch ($roleEnum)
           {
            case 'CIG_App_Admin':
		            (new RedirectResponse('/pods_admin_dashboard'))->send();
	            	break;
            case 'CIG_NSHDS':
                (new RedirectResponse('/pods_awardee_dashboard'))->send();
                break;
            case 'CIG_APT':
              (new RedirectResponse('/pods_awardee_dashboard'))->send();
              break;
            case 'CIG_NCDS':
                (new RedirectResponse('/pods_awardee_dashboard'))->send();
                break;
            case 'NA':
                //(new RedirectResponse('/user/login'))->send();
                \Drupal::messenger()->addError(t('You do not have a valid zRole assigned. Please see your administrator'));
                break;
            default:
                \Drupal::messenger()->addStatus(t('Login Failed'));
                break;
            }
         return $form;


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
    return 'test_set_awardee';
  }

}

