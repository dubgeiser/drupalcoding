<?php

namespace Drupal\watchlater\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\watchlater\WatchlaterStorageInterface;


/**
 * A node block for the watchlater module
 *
 * @author <per.juchtmans@digipolis.gent>
 *
 * @Block(
 *  id = "block_watchlater",
 *  admin_label = @Translation("Watchlater node block")
 * )
 */
class WatchLaterNodeBlock extends BlockBase
  implements ContainerFactoryPluginInterface
{
  /**
   * @var RouteMatchInterface
   */
  private $routeMatch;

  /**
   * @var WatchlaterStorageInterface
   */
  private $storage;

  /**
   * @var AccountInterface
   */
  private $currentUser;

  /**
   * @param array $configuration The configuration for the plugin
   * @param string $plug_id The identifier for the plugin.
   * @param string $plugin_definition The plugin implementation definition.
   * @param RouteMatchInterface $routeMatch The current route match
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    RouteMatchInterface $routeMatch,
    WatchlaterStorageInterface $storage,
    AccountInterface $user
  )
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatch = $routeMatch;
    $this->storage = $storage;
    $this->currentUser = $user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  )
  {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get("current_route_match"),
      $container->get("watchlater.storage"),
      $container->get("current_user")
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $node = $this->routeMatch->getParameter('node');
    if (!is_null($node)) {
      $this->storage->add($node->id(), $this->user->id());
    }

    return [
      '#markup' => '<h1>Hello world</h1>'
    ];
  }
}
