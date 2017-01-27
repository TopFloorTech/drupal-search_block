<?php

namespace Drupal\search_block\Plugin\Block;

use Drupal\Core\Block\BlockPluginInterface;
use Drupal\search_block\Plugin\SearchBlockPlugin\SearchBlockPluginInterface;
use Drupal\search_block\SearchBlockPluginManager;

interface SearchBlockInterface extends BlockPluginInterface {
  /**
   * Gets the search block plugins that are enabled for this block.
   *
   * @return SearchBlockPluginInterface[]
   *   The search block plugins.
   */
  public function getEnabledPlugins();
}
