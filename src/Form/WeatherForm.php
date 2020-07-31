<?php

namespace Drupal\weather_forecast\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class WeatherForm.
 */
class WeatherForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'weather_forecast.weather',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'weather_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('weather_forecast.weather');
    $form['openweather_api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Openweather API Key'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('openweather_api_key'),
    ];
    $form['google_map_api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Google Map API Key'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('google_map_api_key'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('weather_forecast.weather')
      ->set('openweather_api_key', $form_state->getValue('openweather_api_key'))
      ->set('google_map_api_key', $form_state->getValue('google_map_api_key'))
      ->save();
  }

}
