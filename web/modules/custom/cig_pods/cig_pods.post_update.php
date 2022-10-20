<?php

/**
 * @file
 * Post update functions for CIG PODS module.
 */

/**
 * Install SCSS Compiler module.
 */
function cig_pods_post_update_enable_scss_compiler(&$sandbox = NULL) {
  if (!\Drupal::service('module_handler')->moduleExists('scss_compiler')) {
    \Drupal::service('module_installer')->install(['scss_compiler']);
  }
}
