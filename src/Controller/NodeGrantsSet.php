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
   * Inti.
   */
  public static function init(&$grants, $node) {
    $grants = [];
    $type = $node->getType();
    $config = \Drupal::config('filial.nodesettings');
    if ($config->get('node_' . $type)) {
      if ($config->get('filial')) {
        self::filial($grants, $node);
      }
      if ($config->get('client')) {
        self::client($grants, $node);
      }
    }
    return $grants;
  }

  /**
   * Filial grants.
   */
  public static function filial(&$grants, $node) {
    $uid = $node->uid->entity->id();
    $filial = self::getFilial($uid);
    if ($filial) {
      $grant = [
        'realm' => 'filial',
        'gid' => $filial,
        'grant_view' => 1,
        'grant_update' => 1,
        'grant_delete' => 0,
        'priority' => 0,
      ];
      $grants[] = $grant;
    }
    return $grants;
  }

  /**
   * Client grants.
   */
  public static function client(&$grants, $node) {
    $clients = $node->field_client;
    if (!empty($clients)) {
      foreach ($clients as $key => $user) {
        $uid = $user->entity->id();
        if ($uid) {
          $grant = [
            'realm' => 'client',
            'gid' => $uid,
            'grant_view' => 1,
            'grant_update' => 0,
            'grant_delete' => 0,
            'priority' => 1,
          ];
          $grants[] = $grant;
        }
      }
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
