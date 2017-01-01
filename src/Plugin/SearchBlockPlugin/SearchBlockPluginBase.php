<?php

namespace Drupal\search_blocks\Plugin\SearchBlockPlugin;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginDependencyTrait;
use Drupal\search_blocks\Plugin\Block\SearchBlockInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A shared base class which can be used by all search block processor plugins.
 */
abstract class SearchBlockPluginBase extends PluginBase implements SearchBlockPluginInterface {
  // Normally, we'd just need \Drupal\Core\Entity\DependencyTrait here for
  // plugins. However, in a few cases, plugins use plugins themselves, and then
  // the additional calculatePluginDependencies() method from this trait is
  // useful. Since PHP 5 complains when adding this trait along with its
  // "parent" trait to the same class, we just add it here in case a child class
  // does need it.
  use PluginDependencyTrait;

  /**
   * The current search block.
   *
   * @var SearchBlockInterface
   */
  protected $searchBlock = NULL;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition) {
    $configuration += $this->defaultConfiguration();
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $plugin = new static($configuration, $plugin_id, $plugin_definition);

    /** @var \Drupal\Core\StringTranslation\TranslationInterface $translation */
    $translation = $container->get('string_translation');
    $plugin->setStringTranslation($translation);

    return $plugin;
  }

  /**
   * {@inheritdoc}
   */
  public function label() {
    $plugin_definition = $this->getPluginDefinition();

    return $plugin_definition['name'];
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    $plugin_definition = $this->getPluginDefinition();
    return isset($plugin_definition['description']) ? $plugin_definition['description'] : '';
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function setConfiguration(array $configuration) {
    $this->configuration = $configuration + $this->defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function onDependencyRemoval(array $dependencies) {
    // By default, we're not reacting to anything and so we should leave
    // everything as it was.
    return FALSE;
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
  public function settingsForm(array &$form, FormStateInterface $formState) {}

  /**
   * {@inheritdoc}
   */
  public function settingsFormSubmit(array $form, FormStateInterface $formState) {}

  /**
   * {@inheritdoc}
   */
  public function searchForm(array &$form, FormStateInterface $formState) {}

  /**
   * {@inheritdoc}
   */
  public function searchFormSubmit(array &$form, FormStateInterface $formState) {}

  /**
   * {@inheritdoc}
   */
  public function blockBuild(array &$element) {}

  /**
   * {@inheritdoc}
   */
  public function processSearch(FormStateInterface $formState) {}
}
