<?php

namespace Drupal\weather_forecast\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'WeatherBlock' block.
 *
 * @Block(
 *  id = "weather_block",
 *  admin_label = @Translation("Weather Forecast"),
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
    
    $items['tempf'] = $weather_service->celsius_to_fahrenheit($items['temp']);
    \Drupal::logger('weather_forecast')->notice("item array: <pre>" .print_r($items,TRUE)."</pre>");
    $build = [];
    $build['#theme'] = 'weather_block';
    return array(
      '#theme'  => 'weather_block',
      '#items'  => $items,
      '#attached' => [
        'library' => [
          'weather_forecast/weather_forecast_style',
        ],
      ],
      
      );
  }

}
