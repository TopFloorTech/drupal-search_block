<?php

namespace Drupal\search_block\Plugin\Block;

use Drupal\Core\Annotation\Translation;
use Drupal\Core\Block\Annotation\Block;
use Drupal\search_block\SearchBlockPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Block(
 *   id = "search_block_search_block",
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
    /** @var SearchBlockPluginManager $searchBlockHandlerManager */
    $searchBlockHandlerManager = $container->get('plugin.manager.search_block_plugin');

    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $searchBlockHandlerManager
    );
  }
}
