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
  public function getUserIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
  public function get_latlon() {
    $weather_service = \Drupal::service('weather_forecast.default');
    $ipaddress = $weather_service->getUserIP();
    $config = \Drupal::config('weather_forecast.weather');
    $GMApiKey = $config->get('google_map_api_key');
    
    $uri = 'http://api.ipstack.com/'. $ipaddress .'?access_key='. $GMApiKey;
    
  try {
     $response = \Drupal::httpClient()->get($uri, array('headers' => array('Accept' => 'text/plain')));
     $data = (string) $response->getBody();
     $data = json_decode($data);
     \Drupal::logger('get_latlon')->notice("item array: <pre>" .print_r($data,TRUE)."</pre>");
     if (empty($data)) {
       return FALSE;
     }
     $coords = array();
     $coords['lat'] =  $data->latitude;
     $coords['lon'] =  $data->longitude;
     

    return $coords;

    //AIzaSyD8sQjJLn9jHZuUSXYTCFQ5-4Dpxs1CYPU
  }
  catch (RequestException $e) {
    return FALSE;
  }
}
  public function get_weather() {
    $config = \Drupal::config('weather_forecast.weather');
    $OWApiKey = $config->get('openweather_api_key');
    $weather_service = \Drupal::service('weather_forecast.default');
    $coords = $weather_service->get_latlon();
    $uri = 'api.openweathermap.org/data/2.5/weather?lat='. $coords['lat'] .'&lon='. $coords['lon'] .'&appid='. $OWApiKey .'&units=metric';
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
     $items['humidity'] =  $data->main->humidity;
     $items['icon'] =  $data->weather[0]->icon;
     $items['description'] = $data->weather[0]->description;
     return $items;
   }
   catch (RequestException $e) {
     return FALSE;
   } 
  }
}
