<?php

namespace Drupal\usda_eauth\Form;

Use Drupal\Core\Form\FormBase;
Use Drupal\Core\Form\FormStateInterface;
use \Drupal\usda_eauth\zRolesUtilities; //for getUserAccessRolesAndScopes
use Drupal\Core\Site\Settings;  //used to access Settings

/* redirect */
use Symfony\Component\HttpFoundation\RedirectResponse;

class generic extends FormBase {


   /**
   * {@inheritdoc}
   */
    public function buildForm(array $form, FormStateInterface $form_state, $options = NULL){

        $form['actions'] = [
            '#type' =>
            'actions',
        ];

        $form['actions']['cancel'] = [
            '#type' =>
            'submit',
            '#value' =>
                $this->t('Continue'),
        ];

        /* Check for error in login url from eAuth server */
        foreach ($_GET as $key => $value)
        {
         if ($key == 'error')
           {
            (new RedirectResponse('/user/logout'))->send();
            \Drupal::messenger()->addStatus(t('Login Failed'));
            return $form;
           }
        }

        /* Get code from eAuth Server. If code is missing, then error out */
        try
           {
            $code =$_GET['code'];
            $state = $_GET['state'];
           }
        catch (\Exception $e)
           {
            (new RedirectResponse('/user/logout'))->send();
            \Drupal::messenger()->addStatus(t('Login Failed'));
            return $form;
           }

        /* Get environment values from Settings */
        $eAuthTokenUrl = Settings::get('eAuthTokenUrl', '');
        $client_id = Settings::get('client_id', '');
        $client_secret = Settings::get('client_secret', '');

        /* Make a curl call to get the token using 'code' from eAuth via Url  */
         $getTokenUrl = 'client_id=' . $client_id . '&state=' . $state .'&client_secret=' .$client_secret  .  '=&code=' . $code . '&grant_type=authorization_code';

         $curl = curl_init();

         curl_setopt_array($curl, [
         CURLOPT_URL => $eAuthTokenUrl,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => "",
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 30,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => "POST",
         CURLOPT_POSTFIELDS =>  $getTokenUrl,
         CURLOPT_HTTPHEADER => [
             "content-type: application/x-www-form-urlencoded"
             ],
         ]);

         $response = curl_exec($curl);
         $err = curl_error($curl);
         curl_close($curl);

         if ($err) {
            echo "cURL Error #:" . $err;
         }

         /* Get eAuthID from response  */
         $arr_resp = (array) json_decode($response);
         $id_token = $arr_resp['id_token'];
         /*id_token is in jwt so use the next line to 'decode' it to an array. */
         $resp = (array) json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $id_token)[1]))));
         /* eAuthId is held in the sub part of the array */
         $eAuthId = $resp['sub'];

         // Get the zRoles utility service.
         /** @var \Drupal\usda_eauth\zRolesUtilitiesInterface $zroles_util */
         $zroles_util = \Drupal::service('usda_eauth.zroles');

        /* Make a soap call to get the zRole info using the eAuth Id */
         $response = $zroles_util->getUserAccessRolesAndScopes( $eAuthId);

         /* Get the user info from zRoles response */
         $email = $zroles_util->getTokenValue ($response, 'EmailAddress');
         $firstName = $zroles_util->getTokenValue ($response, 'FirstName');
         $lastName = $zroles_util->getTokenValue ($response, 'LastName');
         $roleId = $zroles_util->getTokenValue ($response, 'ApplicationRoleId');
         $roleName = $zroles_util->getTokenValue ($response, 'ApplicationRoleName');
         $roleEnum = $zroles_util->getTokenValue ($response, 'ApplicationRoleEnumeration');
         $roleDisplay = $zroles_util->getTokenValue ($response, 'ApplicationRoleDisplay');

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
            case 'CIG_NCDS':
                (new RedirectResponse('/pods_awardee_dashboard'))->send();
                break;
            case 'CIG_APA':
              (new RedirectResponse('/pods_admin_dashboard'))->send();
                break;
          case 'CIG_APT':
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
    }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state){
    return;
  }

  /**
   * {@inheritdoc}
   */
  /* The submit should never be run as all use cases will RedirectResponse to another route, thus this form should never be seen*/
  public function submitForm(array &$form, FormStateInterface $form_state) {
     (new RedirectResponse('/user/login'))->send();

  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'generic';
  }

}

