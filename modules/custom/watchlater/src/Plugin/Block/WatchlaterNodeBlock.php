<?php

namespace Drupal\watchlater\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\watchlater\WatchlaterStorageInterface;
use Drupal\Core\Form\FormBuilderInterface;


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
   * @param FormBuilderInterface $form The form builder for watch later items.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    RouteMatchInterface $routeMatch,
    WatchlaterStorageInterface $storage,
    AccountInterface $user,
    FormBuilderInterface $formBuilder
  )
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatch = $routeMatch;
    $this->storage = $storage;
    $this->currentUser = $user;
    $this->formBuilder = $formBuilder;
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
      $container->get("current_user"),
      $container->get("form_builder")
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $node = $this->routeMatch->getParameter('node');
    $markup = '';
    if ($this->isValidNode($node)) {
      $markup = $this->processWatchlaterForm($node);
    }

    return [
      '#markup' => $markup
    ];
  }

  /**
   * Process a watch later form and return its rendered markup.
   * @param Node $node The node to process the form for.
   * @return string Rendered form.
   */
  private function processWatchlaterForm($node)
  {
      if ($this->storage->isInList($node->id(), $this->currentUser->id())) {
          $formType = 'Drupal\watchlater\Form\RemoveForm';
      } else {
          $formType = 'Drupal\watchlater\Form\AddForm';
      }

      return render($this->formBuilder->getForm($formType, $node->id()));
  }

  /**
   */
  private function isValidNode($node)
  {
    return !is_null($node) && $this->currentUser->id() > 0;
  }
}
