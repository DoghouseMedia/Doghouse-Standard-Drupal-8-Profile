<?php

/**
 * @file
 * Enables modules and site configuration for the Doghouse standard site installation.
 */

use Drupal\contact\Entity\ContactForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements hook_form_FORM_ID_alter() for install_configure_form().
 */
function doghouse_standard_form_install_configure_form_alter(&$form, FormStateInterface $form_state) {
  $default_email = 'admin@doghouse.agency';

  // Site information defaults.
  $form['site_information']['site_mail']['#default_value'] = $default_email;

  // Account information defaults.
  $form['admin_account']['account']['name']['#default_value'] = 'superuser';
  $form['admin_account']['account']['mail']['#default_value'] = $default_email;

  // Date/time settings defaults.
  $form['regional_settings']['site_default_country']['#default_value'] = 'AU';
  $form['regional_settings']['date_default_timezone']['#default_value'] = 'Australia/Perth';

}

/**
 * Implements hook_form_FORM_ID_alter() for install_settings_form().
 */
function doghouse_standard_form_install_settings_form_alter(&$form, FormStateInterface $form_state) {
  $default_db = str_replace('.local','_dev', \Drupal::request()->getHost());

  // Database configuration defaults.
  $form['settings']['mysql']['database']['#default_value'] = $default_db;
  $form['settings']['mysql']['username']['#default_value'] = 'drupal';
}
