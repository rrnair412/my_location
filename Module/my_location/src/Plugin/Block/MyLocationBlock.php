<?php

namespace Drupal\my_location\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactory;

/**
 * Display Location and Current time as per Time Zone Selected from Configuration Form.
 *
 * @Block(
 *   id = "my_location_block",
 *   admin_label = @Translation("Display Users Current Time"),
 * )	
 */
class MyLocationBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Configuration Factory.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * block class constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param Drupal\Core\Config\ConfigFactory $configFactory
   *   Drupal config factory service.
   */

  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactory $configFactory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $configFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    //$timezone = \Drupal::config('my_location.settings')->get('timezone');
    $timezone = $this->configFactory->get('my_location.settings')->get('timezone');
    $date = new \DateTime("now", new \DateTimeZone($timezone) );
    $date_output = $date->format('jS M Y - g:i a');

    // Render date and time to twig template.
    $renderable = [
      '#theme' => 'my-location',
      '#date' => $date_output,
      '#timezone' => $timezone,
      '#cache' => [
          'max-age' => 0,
       ],
    ];

    return $renderable;
  }

}