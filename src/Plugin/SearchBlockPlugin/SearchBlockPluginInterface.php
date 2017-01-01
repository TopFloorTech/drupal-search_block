<?php

namespace Drupal\search_blocks\Plugin\SearchBlockPlugin;

use Drupal\Component\Plugin\ConfigurablePluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\search_blocks\Plugin\Block\SearchBlockInterface;

/**
 * Provides an interface describing the a search block processor plugin.
 */
interface SearchBlockPluginInterface extends ConfigurablePluginInterface {

  /**
   * Gets the search block associated with this plugin instance.
   *
   * @return SearchBlockInterface
   *   The search block.
   */
  public function getSearchBlock();

  /**
   * Sets the search block associated with this plugin.
   *
   * @param \Drupal\search_blocks\Plugin\Block\SearchBlockInterface $searchBlock
   *   The search block.
   */
  public function setSearchBlock(SearchBlockInterface $searchBlock);

  /**
   * Modify or add to the settings form for the search block.
   *
   * @param array $form
   *   The existing form array.
   *
   * @param \Drupal\Core\Form\FormStateInterface $formState
   *   The FormStateInterface object
   */
  public function settingsForm(array &$form, FormStateInterface $formState);

  /**
   * Process the submission of the settings form of the associated form block.
   *
   * @param array $form
   *   The form array.
   *
   * @param \Drupal\Core\Form\FormStateInterface $formState
   *   The FormStateInterface object.
   */
  public function settingsFormSubmit(array $form, FormStateInterface $formState);

  /**
   * Modify or add to the search form for the search block.
   *
   * @param array $form
   *   The existing form array.
   *
   * @param \Drupal\Core\Form\FormStateInterface $formState
   *   The FormStateInterface object.
   */
  public function searchForm(array &$form, FormStateInterface $formState);

  /**
   * Processes a search block submission.
   *
   * @param array $form
   *  The form object.
   *
   * @param \Drupal\Core\Form\FormStateInterface $formState
   *  The FormStateInterface object.
   */
  public function searchFormSubmit(array &$form, FormStateInterface $formState);

  /**
   * Modify the block's elements while they're being built.
   *
   * @param array $element
   *   The current search block array being built.
   */
  public function blockBuild(array &$element);

  /**
   * Process the search submission.
   *
   * @param \Drupal\Core\Form\FormStateInterface $formState
   *   The form state containing the search values.
   */
  public function processSearch(FormStateInterface $formState);
}
