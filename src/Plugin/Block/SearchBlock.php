<?php

namespace Drupal\search_blocks\Plugin\Block;

use Drupal\Core\Annotation\Translation;
use Drupal\Core\Block\Annotation\Block;
use Drupal\search_blocks\SearchBlockPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Block(
 *   id = "search_blocks_search_block",
 *   admin_label = @Translation("Search block"),
 *   category = @Translation("Search Blocks")
 * )
 */
class SearchBlock extends SearchBlockBase {
  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, SearchBlockPluginManager $pluginManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $pluginManager);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.search_block_plugin')
    );
  }
}
