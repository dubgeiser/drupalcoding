<?php

namespace Drupal\watchlater\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\watchlater\WatchlaterStorageInterface;

/**
 * Form to add a "Watch Later" item.
 *
 * @author <per.juchtmans@digipolis.gent>
 */
class AddForm extends FormBase
{
  /**
   * @var Drupal\watchlater\WatchlaterStorageInterface;
   */
  private $storage;

  public function __construct(WatchlaterStorageInterface $storage)
  {
    $this->storage = $storage;
  }

  /**
   * @param ContainerInterface $container Storage container for watch later items.
   * @return AddForm
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('watchlater.storage')
    );
  }

  /**
   * @inheritdoc
   */
  public function getFormId()
  {
    return 'watchlater_add_form';
  }

  /**
   * @inheritdoc
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form = [
      'actions' => [
        'submit' => [
          '#type' => 'submit',
          '#value' => t('Add article'),
        ],
      ],
    ];
    return $form;
  }

  /**
   * @inheritdoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $nid = $form_state->getBuildInfo()['args'][0];
    $uid = $this->currentUser()->id();
    $this->storage->add($nid, $uid);
    drupal_set_message(t("Item has been added to the 'watch later' list."));
  }
}

