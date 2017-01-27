<?php

namespace Drupal\search_block;

use Drupal\Component\Plugin\Factory\DefaultFactory;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\search_block\Plugin\Block\SearchBlockInterface;
use Drupal\search_block\Plugin\SearchBlockPlugin\SearchBlockPluginInterface;

/**
 * Provides a SearchBlockPlugin plugin manager.
 */
class SearchBlockPluginManager extends DefaultPluginManager {

  /**
   * {@inheritdoc}
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct(
      'Plugin/SearchBlockPlugin',
      $namespaces,
      $module_handler,
      'Drupal\search_block\Plugin\SearchBlockPlugin\SearchBlockPluginInterface',
      'Drupal\search_block\Annotation\SearchBlockPlugin'
    );

    $this->alterInfo('search_block_handler_info');
    $this->setCacheBackend($cache_backend, 'search_block_handler_info_plugins');
    $this->factory = new DefaultFactory($this->getDiscovery());
  }

  /**
   * Gets the enabled search block plugins from the provided search block.
   *
   * @param array $ids
   *   The plugin IDs to load.
   *
   * @param \Drupal\search_block\Plugin\Block\SearchBlockInterface $searchBlock
   *   The search block to associate with each plugin.
   *
   * @return \Drupal\search_block\Plugin\SearchBlockPlugin\SearchBlockPluginInterface[] The enabled search block plugins.
   * The enabled search block plugins.
   */
  public function getPlugins(array $ids, SearchBlockInterface $searchBlock = NULL) {
    $plugins = [];

    foreach ((array) $ids as $pluginId) {
      /** @var SearchBlockPluginInterface $plugin */
      $plugin = $this->createInstance($pluginId);

      if (!is_null($searchBlock)) {
        $plugin->setSearchBlock($searchBlock);
      }

      $plugins[] = $plugin;
    }

    return $plugins;
  }

  public function getPluginOptions() {
    $definitions = $this->getDefinitions();

    uasort($definitions, function ($a, $b) {
      $aWeight = !empty($a['weight']) ? $a['weight'] : 0;
      $bWeight = !empty($b['weight']) ? $b['weight'] : 0;

      if ($aWeight == $bWeight) {
        return 0;
      }

      return ($aWeight < $bWeight) ? -1 : 1;
    });

    $options = [];

    foreach ($definitions as $pluginId => $definition) {
      $options[$pluginId] = $definition['name'];
    }

    return $options;
  }
}
