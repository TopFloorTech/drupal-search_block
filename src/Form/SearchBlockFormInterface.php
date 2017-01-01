<?php

namespace Drupal\search_blocks\Form;

use Drupal\Core\Form\FormInterface;
use Drupal\search_blocks\Plugin\Block\SearchBlockInterface;

/**
 * Defines a Search Block Form interface.
 */
interface SearchBlockFormInterface extends FormInterface {

  /**
   * Gets the search block associated with this form, or NULL.
   *
   * @return SearchBlockInterface|NULL
   *   The search block, or NULL.
   */
  public function getSearchBlock();

  /**
   * Sets the search block associated with this form.
   *
   * @param \Drupal\search_blocks\Plugin\Block\SearchBlockInterface $searchBlock
   *   The search block to associate with this form.
   */
  public function setSearchBlock(SearchBlockInterface $searchBlock);

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
