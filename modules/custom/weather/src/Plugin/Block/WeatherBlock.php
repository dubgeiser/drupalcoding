<?php

namespace Drupal\weather\Plugin\Block;

use Drupal\Core\Block\BlockBase;


/**
 * Weather block.
 *
 * @author <per.juchtmans@digipolis.gent>
 */
class WeatherBlock extends BlockBase
{
  public function build()
  {
    $build = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#value' => '',
      '#attributes' => ['id' => ['weather']]
    ];
    $build['#attached']['library'][] = 'weather/drupal.weather';
    return $build;
  }
}
