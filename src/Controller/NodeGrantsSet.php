<?php

namespace Drupal\filial\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class Page.
 *
 * @package Drupal\filial\Controller
 */
class NodeGrantsSet extends ControllerBase {

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public static function set(&$grants, $node) {
    $uid = $node->uid->entity->id();
    $grants = [];
    $filial = self::getFilial($uid);
    if ($filial) {
      $grant = [
        'realm' => 'filial',
        'gid' => $filial,
        'grant_view' => 1,
        'grant_update' => 0,
        'grant_delete' => 0,
        'priority' => 0,
      ];
      $grants[] = $grant;
    }

    return $grants;
  }

  /**
   * Filials query by user.
   */
  public static function getFilial($uid) {
    $ids = self::queryList($uid);
    $filial = FALSE;
    if (!empty($ids)) {
      foreach ($ids as $id) {
        $filial = $id;
      }
    }
    return $filial;
  }

  /**
   * Filials query by user.
   */
  public static function queryList($uid) {
    $query = \Drupal::entityQuery('filial');
    $query->condition('field_filial_team', $uid);
    $ids = $query->execute();
    return $ids;
  }

  /**
   * Filial query by user.
   */
  public static function querySingle($uid) {
    $query = \Drupal::entityQuery('filial');
    $query->condition('field_filial_team', $uid);
    $ids = $query->execute();
    $id = array_shift($ids);
    return $id;
  }

}
