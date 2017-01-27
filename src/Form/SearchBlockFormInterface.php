<?php

namespace Drupal\search_block\Form;

use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\search_block\Plugin\Block\SearchBlockInterface;
use Drupal\search_block\Plugin\SearchBlockPlugin\SearchBlockPluginInterface;

/**
 * Defines a Search Block Form interface.
 */
interface SearchBlockFormInterface extends FormInterface {

  /**
   * Gets the search block associated with this form, or NULL.
   *
   * @param \Drupal\Core\Form\FormStateInterface $formState
   *   The optional form state.
   *
   * @return SearchBlockPluginInterface[]
   *   The search block plugins
   */
  public function getPlugins(FormstateInterface $formState = NULL);

  /**
   * Sets the search block associated with this form.
   *
   * @param SearchBlockPluginInterface[] $plugins
   */
  public function setPlugins(array $plugins);

  /**
   * Get the default configuration of this form to use if no search block is
   * associated with this form yet.
   *
   * @return mixed
   *   The default configuration array.
   */
  public function defaultConfiguration();

  /**
   * Gets the settings from the search block and fills any missing values from
   * the default configuration.
   *
   * @return array
   *   The settings array.
   */
  public function getConfiguration();

  /**
   * Gets an array of all of the default form values to pre-fill.
   *
   * @return array
   *   The default form values.
   */
  public function defaultFormValues();
}
