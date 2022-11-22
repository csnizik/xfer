<?php

namespace Drupal\usda_eauth;

use \Symfony\Component\EventDispatcher\EventSubscriberInterface;
use \Symfony\Component\HttpKernel\KernelEvents;
use \Symfony\Component\HttpKernel\Event;
use \Symfony\Component\HttpFoundation\Session\Session;
use \Symfony\Component\DependencyInjection\ContainerInterface;
use \Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;

class ResponseHandlerSubscriber implements EventSubscriberInterface {
    // Define rate at which to check auth expiration refresh
    CONST REFRESH_RATE = 60 * 30;
    
    public static function getSubscribedEvents() {
        return [
            KernelEvents::RESPONSE => 'responseHandler',
        ];
    }

    // Check if eauth user is valid
    public function checkZRoles($eAuthId) {
        $zroles_util = \Drupal::service('usda_eauth.zroles');
        if($eAuthId == NULL) return TRUE;
        $ret = $zroles_util->getUserAccessRolesAndScopes($eAuthId);
        
        // \Drupal::logger("type_tests")->error('type: ' . gettype($ret));
        // \Drupal::logger("type_tests")->error('val: ' . print_r($ret, true));

        return !(strpos($ret, "An error while looking up the user in active directory")); 
    }

    public function logoutUser($session) {  
        user_logout();
    }

    // Gives a new auth experation time only if valid user. Otherwise kicked them out.
    public function refreshAuthExpiryMark($session, $eAuthId) {
        if ($this->checkZroles($eAuthId)) {
            $session->set('auth_expiry_mark', time());
        } else {
            $this->logoutUser($session);
        }
    }

    public function responseHandler(\Symfony\Component\HttpKernel\Event\ResponseEvent $event) {
        $session = \Drupal::request()->getSession();
        $eAuthId = $session->get("eAuthId");

        // \Drupal::logger('handler_test')->error("handler triggered for: " . $session->get('eAuthId'));

        $auth_expiry = $session->get("auth_expiry_mark");

        // sets a new eauth expiration time for a valid eauth user if no expiration time has been set or time is up
        if(($auth_expiry == NULL) || ((time() - $auth_expiry) > self::REFRESH_RATE)) { 
            $this->refreshAuthExpiryMark($session, $eAuthId);
        }
    }
}

?>
