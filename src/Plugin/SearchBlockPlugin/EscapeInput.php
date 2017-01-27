<?php

namespace Drupal\search_block\Plugin\SearchBlockPlugin;

use Drupal\Core\Annotation\Translation;
use Drupal\Core\Form\FormStateInterface;
use Drupal\search_block\Annotation\SearchBlockPlugin;

/**
 * @SearchBlockPlugin(
 *   id = "escape_input",
 *   name = @Translation("Escape input"),
 *   description = @Translation("Use this processor if you'll be passing the search terms in the URL."),
 *   weight = 10,
 * )
 */
class EscapeInput extends SearchBlockPluginBase {
  public function searchForm(array &$form, FormStateInterface $formState) {
    $keys = $formState->getValue('keys');

    $form['keys']['#default_value'] = urldecode($keys);
  }

  public function searchFormSubmit(array &$form, FormStateInterface $formState) {
    $keys = $formState->getValue('keys');

    $formState->setValue('keys', urlencode($keys));
  }
}
