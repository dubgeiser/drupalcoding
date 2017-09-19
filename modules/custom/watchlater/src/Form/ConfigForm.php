<?php

namespace Drupal\watchlater\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form for the configuration of the watchlater module.
 *
 * @author <per.juchtmans@digipolis.gent>
 */
class ConfigForm extends ConfigFormBase
{

  /** @inheritDoc */
  public function getFormId()
  {
    return 'watchlater_config_form';
  }

  /** @inheritDoc */
  public function getEditableConfigNames()
  {
    return['watchlater.config'];
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $config = $this->config('watchlater.config');
    $form = parent::buildForm($form, $form_state);
    $form['is_enabled'] = [
      '#type' => 'checkbox',
      '#title' => 'Enable watch later?',
      '#default_value' => $config->get('is_enabled'),
    ];
    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    parent::submitForm($form, $form_state);
    $this->config('watchlater.config')
      ->set('is_enabled', $form_state->getValue('is_enabled'))
      ->save()
      ;
  }
}
