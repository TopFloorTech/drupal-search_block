<?php

namespace Drupal\search_block\Plugin\SearchBlockPlugin;

use Drupal\Core\Annotation\Translation;
use Drupal\Core\Form\FormStateInterface;
use Drupal\search_block\Annotation\SearchBlockPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @SearchBlockPlugin(
 *   id = "redirect",
 *   name = @Translation("Redirect"),
 *   description = @Translation("Redirect to a results page on submission."),
 *   weight = 99,
 * )
 */
class Redirect extends SearchBlockPluginBase {
  public function settingsForm(array &$form, FormStateInterface $formState) {
    $config = !is_null($this->searchBlock) ? $this->searchBlock->getConfiguration() : [];

    $form['search_handler']['redirect_path'] = [
      '#type' => 'textfield',
      '#title' => t('Redirect path'),
      '#description' => t('The base path for the redirect (may include search terms at the end).'),
      '#default_value' => isset($config['redirect_path']) ? $config['redirect_path'] : '/search/node'
    ];
  }

  public function settingsFormSubmit(array $form, FormStateInterface $formState) {
    $values = $formState->getValues();

    if (isset($this->searchBlock)) {
      $this->searchBlock->setConfigurationValue('redirect_path', $values['search_handler']['redirect_path']);
    }
  }

  public function processSearch(FormStateInterface $formState) {
    if (isset($this->searchBlock)) {
      $config = $this->searchBlock->getConfiguration();
      $path = $config['redirect_path'];

      // Temporary override
      $path = '/search/site/' . $formState->getValue('keys', '');

      if (!empty($path)) {
        $url = \Drupal::pathValidator()->getUrlIfValid($path);

        $formState->setRedirect($url->getRouteName(), $url->getRouteParameters());
      }
    }
  }
}
