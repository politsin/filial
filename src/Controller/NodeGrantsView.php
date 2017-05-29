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
    //$grants['filial'][] = 1;
    $grants['client'][] = (int) $uid;
    if (!empty($ids)) {
      foreach ($ids as $id) {
        $grants['filial'][] = (int) $id;
      }
    }
    //drupal_set_message(print_r($grants, TRUE));

    return $grants;
  }

}
