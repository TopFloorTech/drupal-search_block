<?php

namespace Drupal\search_block\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a search block plugin annotation object.
 *
 * Plugin Namespace: Plugin\SearchBlockPlugin
 *
 * @Annotation
 */
class SearchBlockPlugin extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the plugin.
   *
   * @ingroup plugin_translatable
   *
   * @var \Drupal\Core\Annotation\Translation
   */
  public $name;

  /**
   * A description of what the plugin does or when to use it.
   *
   * @var \Drupal\Core\Annotation\Translation
   */
  public $description;

  /**
   * The plugin weight.
   *
   * @var integer
   */
  public $weight;
}
