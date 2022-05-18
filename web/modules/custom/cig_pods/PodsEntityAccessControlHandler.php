<?php

namespace Drupal\cig_pods;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Product entity.
 *
 * @see \Drupal\drupalbook_product\Entity\DrupalbookProductEntity.
 */
class PodsEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\drupalbook_product\Entity\DrupalbookProductEntityInterface $entity */

	// For testing purposes, allow all actions
	return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowed();
  }

}