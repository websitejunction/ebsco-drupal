<?php

namespace Drupal\weather_forecast\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'WeatherBlock' block.
 *
 * @Block(
 *  id = "weather_block",
 *  admin_label = @Translation("Weather block"),
 * )
 */
class WeatherBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $weather_service = \Drupal::service('weather_forecast.default');
    if (!$items=$weather_service->get_weather()) {
      $items = array();

    }
    \Drupal::logger('weather_forecast')->notice("item array: <pre>" .print_r($items,TRUE)."</pre>");
    $build = [];
    $build['#theme'] = 'weather_block';
    $build['weather_block']['#markup'] = '<p>' . $items['city'] . '</p>';
    return array(
      '#theme'  => 'weather_block',
      //'#image'  => $image_url,
      '#items'  => $items
      );
  }

}
