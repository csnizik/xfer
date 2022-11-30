<?php

namespace Drupal\usda_eauth;

use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Response handler class for checking zroles credentials.
 */
class ResponseHandlerSubscriber implements EventSubscriberInterface {
  // Define rate at which to check auth expiration refresh.
  const REFRESH_RATE = 60 * 30;

  /**
   * Get all requests to be handled.
   */
  public static function getSubscribedEvents() {
    return [
      KernelEvents::RESPONSE => 'responseHandler',
    ];
  }

  /**
   * Check if eauth user is valid.
   */
  public function checkZroles($eAuthId) {
    $zroles_util = \Drupal::service('usda_eauth.zroles');
    if ($eAuthId == NULL) {
      return TRUE;
    }
    $ret = $zroles_util->getUserAccessRolesAndScopes($eAuthId);

    // \Drupal::logger("type_tests")->error('type: ' . gettype($ret));
    // \Drupal::logger("type_tests")->error('val: ' . print_r($ret, true));
    return !(strpos($ret, "An error while looking up the user in active directory"));
  }

  /**
   * Logs out the user from drupal session.
   */
  public function logoutUser($session) {
    user_logout();
  }

  /**
   * Gives a new auth experation time only if valid user. If not, logs them out.
   */
  public function refreshAuthExpiryMark($session, $eAuthId) {
    if ($this->checkZroles($eAuthId)) {
      $session->set('auth_expiry_mark', time());
    }
    else {
      $this->logoutUser($session);
    }
  }

  /**
   * Handles all requests made in PODS.
   */
  public function responseHandler(ResponseEvent $event) {
    $session = \Drupal::request()->getSession();
    $eAuthId = $session->get("eAuthId");

    // \Drupal::logger('handler_test')
    // ->error("handler triggered for: " . $session->get('eAuthId'));
    $auth_expiry = $session->get("auth_expiry_mark");

    // Sets a new eauth expiration time for a valid eauth user
    // if no expiration time has been set or time is up.
    if (($auth_expiry == NULL) || ((time() - $auth_expiry) > self::REFRESH_RATE)) {
      $this->refreshAuthExpiryMark($session, $eAuthId);
    }
  }

}
