<?php

namespace Drupal\search_blocks\Form;

use Drupal\block\Entity\Block;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\search_blocks\Plugin\Block\SearchBlockInterface;

/**
 * Provides a base implementation of a search block form.
 */
class SearchBlockFormBase extends FormBase implements SearchBlockFormInterface {

  /** @var SearchBlockInterface */
  var $searchBlock = NULL;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    // TODO: Unique form ID per block instance
    return 'search_blocks_search_block_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $settings = $this->getConfiguration();

    $defaultValues = $this->defaultFormValues();

    $values = $form_state->getValues();

    $form['keys'] = [
      '#type' => 'search',
      '#title' => $this->t($settings['input_label']),
      '#placeholder' => $this->t($settings['input_placeholder']),
      '#default_value' => isset($values['keys']) ? $values['keys'] : $defaultValues['keys'],
    ];

    $form['search_block'] = [
      '#type' => 'hidden',
      '#title' => $this->t('Search block'),
      '#default_value' => $this->searchBlock->getPluginId(),
    ];

    $form['actions']['#type'] = 'actions';

    $form['actions']['submit'] = [
      '#type' => $settings['submit_button_type'],
      '#value' => $this->t($settings['submit_button_title']),
      '#button_type' => 'primary',
      '#submit' => ['::submitForm']
    ];

    if (!is_null($this->searchBlock)) {
      foreach ($this->searchBlock->getEnabledPlugins() as $plugin) {
        $plugin->searchForm($form, $form_state);
      }
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if (is_null($this->searchBlock) && $form_state->hasValue('search_block')) {
      $this->searchBlock = Block::load($form_state->getValue('search_block'));
    }

    if (!is_null($this->searchBlock)) {
      $plugins = $this->searchBlock->getEnabledPlugins();

      foreach ($plugins as $plugin) {
        $plugin->searchFormSubmit($form, $form_state);
      }

      $response = NULL;
      foreach ($plugins as $plugin) {

        $currentResponse = $plugin->processSearch($form_state);

        if (!is_null($response)) {
          $response = $currentResponse;
        }
      }

      if (!is_null($response)) {
        return $response;
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getSearchBlock() {
    return $this->searchBlock;
  }

  /**
   * {@inheritdoc}
   */
  public function setSearchBlock(SearchBlockInterface $searchBlock) {
    $this->searchBlock = $searchBlock;
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

  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {
    $settings = (!is_null($this->searchBlock))
      ? $this->searchBlock->getConfiguration()
      : [];

    return $settings + $this->defaultConfiguration();
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
