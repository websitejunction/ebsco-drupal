<?php

namespace Drupal\weather_forecast;

/**
 * Interface WeatherServiceInterface.
 */
interface WeatherServiceInterface {
    
    public function get_weather();
    public function get_latlon();

}
