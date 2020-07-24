<?php

namespace Drupal\weather_forecast;

/**
 * Class WeatherService.
 */
class WeatherService implements WeatherServiceInterface {

  /**
   * Constructs a new WeatherService object.
   */
  public function __construct() {

  }
  public function get_latlon() {
    $coords = array();
    $coords['lat'] = 53.5461;
    $coords['lon'] = -113.4938;

    return $coords;

    //AIzaSyCzGW2jCzxBuYMAN7ZPgScEP7F2ZWw2i3Y
  }
  public function get_weather() {
    $weather_service = \Drupal::service('weather_forecast.default');
    $coords = $weather_service->get_latlon();
    $uri = 'api.openweathermap.org/data/2.5/weather?lat='. $coords['lat'] .'&lon='. $coords['lon'] .'&appid=9d4ef49e4231cec49d2b72a82cd7474b&units=metric';
    try {
     $response = \Drupal::httpClient()->get($uri, array('headers' => array('Accept' => 'text/plain')));
     $data = (string) $response->getBody();
     $data = json_decode($data);
     if (empty($data)) {
       return FALSE;
     }
     $items = array();
     $items['city'] =  $data->name;
     $items['country'] =  $data->sys->country;
     $items['temp'] =  $data->main->temp;
     
     return $items;
   }
   catch (RequestException $e) {
     return FALSE;
   } 
  }
}
