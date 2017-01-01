<?php

namespace Drupal\search_blocks\Plugin\Block;

use Drupal\Core\Block\BlockPluginInterface;
use Drupal\search_blocks\Plugin\SearchBlockPlugin\SearchBlockPluginInterface;
use Drupal\search_blocks\SearchBlockPluginManager;

interface SearchBlockInterface extends BlockPluginInterface {
  /**
   * Gets the search block plugins that are enabled for this block.
   *
   * @return SearchBlockPluginInterface[]
   *   The search block plugins.
   */
  public function getEnabledPlugins();

  /**
   * Gets a search block plugin manager instance.
   *
   * @return SearchBlockPluginManager
   *   The search block plugin manager instance.
   */
  public function getPluginManager();
}
