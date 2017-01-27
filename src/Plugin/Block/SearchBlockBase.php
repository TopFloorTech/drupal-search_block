<?php

namespace Drupal\search_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\search_block\Form\SearchBlockForm;
use Drupal\search_block\SearchBlockPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class SearchBlockBase extends BlockBase implements SearchBlockInterface, ContainerFactoryPluginInterface {

  /**
   * @var SearchBlockPluginManager
   */
  protected $pluginManager;

  /**
   * SearchBlockBase constructor.
   *
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\search_block\SearchBlockPluginManager $pluginManager
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, SearchBlockPluginManager $pluginManager) {
    $this->pluginManager = $pluginManager;

    parent::__construct($configuration, $plugin_id, $plugin_definition);
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

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $defaults = parent::defaultConfiguration();

    $defaults['plugins'] = [];
    $defaults['submit_button_type'] = 'submit';
    $defaults['submit_button_text'] = 'Submit';

    return $defaults;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $form['plugins'] = [
      '#type' => 'checkboxes',
      '#options' => $this->pluginManager->getPluginOptions(),
      '#title' => $this->t('Plugins'),
      '#description' => $this->t('Choose which plugins to enable for this search block.'),
      '#default_value' => isset($config['plugins']) ? $config['plugins'] : [],
    ];

    $form['search_form'] = [
      '#type' => 'details',
      '#title' => $this->t('Search form'),
      '#description' => $this->t('These options control how the search form looks and works.'),
    ];

    $form['search_form']['submit_button_type'] = [
      '#type' => 'radios',
      '#title' => $this->t('Submit button type'),
      '#description' => $this->t('An input is the standard submit method, but a button is easier to customize with CSS.'),
      '#options' => [
        'submit' => $this->t('Input'),
        'button' => $this->t('Button'),
      ],
      '#default_value' => isset($config['submit_button_type']) ? $config['submit_button_type'] : 'submit',
    ];

    $form['search_form']['submit_button_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Submit button text'),
      '#description' => $this->t('Enter the text to use for the submit button.'),
      '#default_value' => isset($config['submit_button_text']) ? $config['submit_button_text'] : 'Submit',
    ];

    $form['search_handler'] = [
      '#type' => 'details',
      '#title' => $this->t('Search handler'),
      '#description' => $this->t('Settings related to handling of the search input.'),
    ];

    $form['block_content'] = [
      '#type' => 'details',
      '#title' => $this->t('Block content'),
      '#description' => $this->t('Control other content in the block around the search form.'),
    ];

    $form['block_content']['header_content'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Header content'),
      '#description' => $this->t('This content will appear before the search form.'),
      '#default_value' => (isset($config['header_content']['value'])) ? $config['header_content']['value'] : '',
      '#format' => (isset($config['header_content']['format'])) ? $config['header_content']['format'] : filter_default_format(),
    ];

    $form['block_content']['footer_content'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Footer content'),
      '#description' => $this->t('This content will appear after the search form.'),
      '#default_value' => (isset($config['footer_content']['value'])) ? $config['footer_content']['value'] : '',
      '#format' => (isset($config['footer_content']['format'])) ? $config['footer_content']['format'] : filter_default_format(),
    ];

    foreach ($this->getEnabledPlugins() as $plugin) {
      $plugin->settingsForm($form, $form_state);
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    foreach ($this->getEnabledPlugins() as $plugin) {
      $plugin->settingsFormSubmit($form, $form_state);
    }

    $values = $form_state->getValues();

    $this->setConfigurationValue('plugins', array_filter($values['plugins']));
    $this->setConfigurationValue('submit_button_type', $values['search_form']['submit_button_type']);
    $this->setConfigurationValue('submit_button_text', $values['search_form']['submit_button_text']);
    $this->setConfigurationValue('header_content', $values['block_content']['header_content']);
    $this->setConfigurationValue('footer_content', $values['block_content']['footer_content']);

    parent::blockSubmit($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $element = [];

    $form = new SearchBlockForm();
    $form->setPlugins($this->getEnabledPlugins());

    $element['header'] = $this->getProcessedTextField('header_content');
    $element['form'] = \Drupal::formBuilder()->getForm($form);
    $element['footer'] = $this->getProcessedTextField('footer_content');

    foreach ($this->getEnabledPlugins() as $plugin) {
      $plugin->blockBuild($element);
    }

    return $element;
  }

  protected function getProcessedTextField($fieldName) {
    $config = $this->getConfiguration();

    $element = [];

    $field = $config[$fieldName];
    if (!empty($field['value'])) {
      $element[$fieldName] = [
        '#type' => 'processed_text',
        '#text' => $field['value'],
        '#format' => $field['format'],
      ];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getEnabledPlugins($onlyEnabled = TRUE) {
    $config = $this->getConfiguration();

    $plugins = isset($config['plugins']) ? $config['plugins'] : [];

    return $this->pluginManager->getPlugins($plugins, $this);
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginManager() {
    return $this->pluginManager;
  }
}
