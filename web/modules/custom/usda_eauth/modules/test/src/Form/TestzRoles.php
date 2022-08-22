<?php

namespace Drupal\usda_eauth_test\Form;
Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\Email;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\Entity\User;
use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;
use Drupal\Core\Routing; 
use Drupal\Core\DrupalKernel; 
use Drupal\redirect\Entity\Redirect;
use Drupal\Core\Entity\EntityInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \SimpleXMLElement;
use Drupal\Core\Access\AccessResult;
use \Drupal\usda_eauth\zRolesUtilities;

class TestzRoles extends FormBase {

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
        
         /* Display the users that have Admin zRole */
         $role = 'CIG_App_Admin';

         $userInfo = zRolesUtilities::getListByzRole($role);
         print_r('<br>** ' . $role . ' role has ' . count($userInfo) . ' users. ** <br>');
         /* userinfo may be a single object or an array of userinfo objects, so check count */
         if (count($userInfo) == 1) {
            zRolesUtilities::printUserInfo ($userInfo);
            }
         else
            {
            foreach ($userInfo as $user) {
               zRolesUtilities::printUserInfo ($user);
            }
         }
         
         /* Display the users that have data steward zRole */
         $role = 'CIG_NSHDS';
         $userInfo = zRolesUtilities::getListByzRole($role);
         print_r('<br><br>** ' . $role . ' role has ' . count($userInfo) . ' users. ** <br>');
         if (count($userInfo) == 1) {
            zRolesUtilities::printUserInfo ($userInfo);
            }
         else
            {
            foreach ($userInfo as $user) {
               zRolesUtilities::printUserInfo ($user);
            }
         }
          
      /* Display the users that have CIG_NCDS zRole */
         $role = 'CIG_NCDS';
         $userInfo = zRolesUtilities::getListByzRole($role);
         print_r('<br><br>** ' . $role . ' role has ' . count($userInfo) . ' users. ** <br>');
         if (count($userInfo) == 1) {
            zRolesUtilities::printUserInfo ($userInfo);
            }
         else 
            {
            foreach ($userInfo as $user) {
               zRolesUtilities::printUserInfo ($user);
            }
         }
           

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
    return 'test_zroles';
  }

}

