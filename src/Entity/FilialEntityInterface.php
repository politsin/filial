<?php

namespace Drupal\filial\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Filial entities.
 *
 * @ingroup filial
 */
interface FilialEntityInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Filial name.
   *
   * @return string
   *   Name of the Filial.
   */
  public function getName();

  /**
   * Sets the Filial name.
   *
   * @param string $name
   *   The Filial name.
   *
   * @return \Drupal\filial\Entity\FilialEntityInterface
   *   The called Filial entity.
   */
  public function setName($name);

  /**
   * Gets the Filial creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Filial.
   */
  public function getCreatedTime();

  /**
   * Sets the Filial creation timestamp.
   *
   * @param int $timestamp
   *   The Filial creation timestamp.
   *
   * @return \Drupal\filial\Entity\FilialEntityInterface
   *   The called Filial entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Filial published status indicator.
   *
   * Unpublished Filial are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Filial is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Filial.
   *
   * @param bool $published
   *   TRUE to set this Filial to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\filial\Entity\FilialEntityInterface
   *   The called Filial entity.
   */
  public function setPublished($published);

}
