<?php

namespace Drupal\filial\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;
use Drupal\node\Entity\Node;

/**
 * Class Page.
 *
 * @package Drupal\filial\Controller
 */
class GetFilial extends ControllerBase {

  /**
   * Load filial bt fid.
   */
  public static function load($fid) {
    if (is_numeric($fid)) {
      return \Drupal::entityManager()->getStorage('filial')->load($fid);
    }
    else {
      return FALSE;
    }

  }

  /**
   * Filials query by user.
   */
  public static function getByNid($nid) {
    if (!is_object($nid)) {
      $node = Node::load($nid);
    }
    else {
      $node = $nid;
    }
    $uid = $node->uid->entity->id();
    $filial = self::getByUid($uid);
    return $filial;
  }

  /**
   * Filials query by user.
   */
  public static function getByUid($uid) {
    $ids = self::queryList($uid);
    $filial = FALSE;
    if (!empty($ids)) {
      foreach ($ids as $id) {
        $filial = $id;
      }
    }
    if (count($ids) > 1) {
      $user = User::load($uid);
      $try = $user->field_filial_current->entity->id();
      if (in_array($try, $ids)) {
        $filial = $try;
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
