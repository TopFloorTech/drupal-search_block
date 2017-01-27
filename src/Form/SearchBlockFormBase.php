<?php

namespace Drupal\search_block\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\search_block\Plugin\SearchBlockPlugin\SearchBlockPluginInterface;
use Drupal\views\Entity\View;

/**
 * Provides a base implementation of a search block form.
 */
class SearchBlockFormBase extends FormBase implements SearchBlockFormInterface {

  /** @var SearchBlockPluginInterface[] */
  var $plugins = [];

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'search_block_search_block_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $settings = $this->getConfiguration();

    $defaultValues = $this->defaultFormValues();

    $values = $form_state->getValues();

    $keyValue = isset($values['keys']) ? $values['keys'] : $defaultValues['keys'];

    if (empty($keyValue)) {
      $keyValue = $this->getKeysFromUrl();
    }

    $form['keys'] = [
      '#type' => 'search',
      '#title' => $this->t($settings['input_label']),
      '#placeholder' => $this->t($settings['input_placeholder']),
      '#default_value' => $keyValue,
    ];

    $form['plugins'] = [
      '#type' => 'hidden',
      '#default_value' => $this->getPluginIds($form_state),
      '#access' => FALSE,
    ];

    $form['actions']['#type'] = 'actions';

    $form['actions']['submit'] = [
      '#type' => $settings['submit_button_type'],
      '#value' => $this->t($settings['submit_button_title']),
      '#button_type' => 'primary',
      '#submit' => ['::submitForm']
    ];

    foreach ($this->getPlugins($form_state) as $plugin) {
      $plugin->searchForm($form, $form_state);
    }

    return $form;
  }

  protected function getPluginIds(FormStateInterface $formState = NULL) {
    $plugins = $this->getPlugins($formState);

    $ids = [];

    foreach ($plugins as $plugin) {
      $ids[] = $plugin->getId();
    }

    return $ids;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $plugins = $this->getPlugins($form_state);

    foreach ($plugins as $plugin) {
      $plugin->searchFormSubmit($form, $form_state);
    }

    foreach ($plugins as $plugin) {
      $plugin->processSearch($form_state);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getPlugins(FormStateInterface $formState = NULL) {
    if (is_null($this->plugins) && $formState->hasValue('plugins')) {
      /** @var \Drupal\search_block\SearchBlockPluginManager $pluginManager */
      $pluginManager = \Drupal::getContainer()->get('plugin.manager.search_block_plugin');

      $this->setPlugins($pluginManager->getPlugins($formState->getValue('search_block')));
    }

    return $this->plugins;
  }

  /**
   * {@inheritdoc}
   */
  public function setPlugins(array $plugins = []) {
    $this->plugins = $plugins;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'submit_button_type' => 'submit',
      'submit_button_title' => 'Submit',
      'input_label' => 'Search',
      'input_placeholder' => 'Search',
    ];
  }

  protected function getKeysFromUrl() {
    $current_path = \Drupal::service('path.current')->getPath();

    $keys = '';

    if (strpos($current_path, '/search/site/') === 0) {
      $parts = explode('/', $current_path);

      $last = array_pop($parts);

      $keys = urldecode($last);
    }

    return $keys;
  }

  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {
    return $this->defaultConfiguration();
  }

  /**
   * Gets an array of all of the default form values to pre-fill.
   *
   * @return array
   *   The default form values.
   */
  public function defaultFormValues() {
    // TODO: Something here.
    return [
      'keys' => '',
    ];
  }
}
