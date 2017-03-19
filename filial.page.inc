<?php

/**
 * @file
 * Contains filial.page.inc.
 *
 * Page callback for Filial entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Filial templates.
 *
 * Default template: filial.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_filial(array &$variables) {
  // Fetch FilialEntity Entity Object.
  $filial = $variables['elements']['#filial'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
