<?php

namespace Drupal\city\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements the form controller.
 */
class Settings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'city_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['city.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('city.settings');

    $form['general'] = [
      '#type' => 'details',
      '#title' => $this->t('General settings'),
      '#open' => TRUE,
    ];

    $form['general']['phone'] = [
      '#title' => $this->t('City default phone'),
      '#default_value' => $config->get('phone'),
      '#type' => 'textfield',
      '#description' => '',
    ];
    $form['general']['address'] = [
      '#title' => $this->t('City default address'),
      '#default_value' => $config->get('address'),
      '#type' => 'textfield',
      '#description' => '',
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * Implements form validation.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * Implements a form submit handler.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $config = $this->config('city.settings');
    $config
      ->set('phone', $form_state->getValue('phone'))
      ->set('address', $form_state->getValue('address'))
      ->save();
  }

}
