<?php

namespace Drupal\filial\Access;

use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Access to routes based on permissions defined via module.permissions.yml!
 */
class FilialContentAccessCheck implements AccessInterface {

  /**
   * Access.
   */
  public function access(AccountInterface $account, RouteMatchInterface $route_match) {
    $node = $route_match->getParameter('node');
    $all_nodes_access = $account->hasPermission('grant content access');
    $own_node_access = $account->hasPermission('grant own content access') && ($account->id() == $node->getOwnerId());

    return AccessResult::allowedIf(content_access_get_settings('per_node', $node->getType()) && ($all_nodes_access || $own_node_access));
  }

}
