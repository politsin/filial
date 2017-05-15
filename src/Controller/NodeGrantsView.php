<?php

namespace Drupal\filial\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class Page.
 *
 * @package Drupal\filial\Controller
 */
class NodeGrantsView extends ControllerBase {

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public static function view(&$grants, $account) {
    $uid = \Drupal::currentUser()->id();
    $ids = NodeGrantsSet::queryList($uid);
    $grants = [];
    $grants['filial'][] = 0;
    if (!empty($ids)) {
      foreach ($ids as $id) {
        $grants['filial'][] = (int) $id;
      }
    }
    dsm($grants);

    return $grants;
  }

}
