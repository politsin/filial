<?php

namespace Drupal\filial\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class Page.
 *
 * @package Drupal\filial\Controller
 */
class Page extends ControllerBase {

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public function hello($name) {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: hello with parameter(s): $name'),
    ];
  }

}
