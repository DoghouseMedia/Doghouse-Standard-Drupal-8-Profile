<?php

/**
 * @file
 * Contains \Drupal\google_tag\Form\GoogleTagSettingsform.
 *
 * @author Jim Berry ("solotandem", http://drupal.org/user/240748)
 */

namespace Drupal\google_tag\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class GoogleTagSettingsForm
 * @package Drupal\google_tag\Form
 */
class GoogleTagSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'google_tag_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['google_tag.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('google_tag.settings');

    // Build form elements.
    $form['settings'] = [
      '#type' => 'vertical_tabs',
      '#attributes' => ['class' => ['google-tag']],
      '#attached' => [
        'library' => ['google_tag/drupal.settings_form'],
      ],
    ];

    // General tab.
    $form['general'] = [
      '#type' => 'details',
      '#title' => $this->t('General'),
      '#group' => 'settings',
    ];

    $form['general']['container_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Container ID'),
      '#description' => $this->t('The ID assigned by Google Tag Manager (GTM) for this website container. To get a container ID, <a href="https://tagmanager.google.com/">sign up for GTM</a> and create a container for your website.'),
      '#default_value' => $config->get('container_id'),
      '#attributes' => ['placeholder' => ['GTM-xxxxxx']],
      '#size' => 12,
      '#maxlength' => 15,
      '#required' => TRUE,
    ];

    // Page paths tab.
    $description = $this->t('On this and the next two tabs, specify the conditions on which the GTM JavaScript snippet will either be included in or excluded from the page response, thereby enabling or disabling tracking and other analytics.');
    $description .= $this->t(' All conditions must be satisfied for the snippet to be included. The snippet will be excluded if any condition is not met.<br /><br />');
    $description .= $this->t(' On this tab, specify the path condition.');

    // @todo Use singular for element names.
    $form['path'] = [
      '#type' => 'details',
      '#title' => $this->t('Page paths'),
      '#group' => 'settings',
      '#description' => $description,
    ];

    $form['path']['path_toggle'] = [
      '#type' => 'radios',
      '#title' => $this->t('Add snippet on specific paths'),
      '#options' => [
        GOOGLE_TAG_EXCLUDE_LISTED => $this->t('All paths except the listed paths'),
        GOOGLE_TAG_INCLUDE_LISTED => $this->t('Only the listed paths'),
      ],
      '#default_value' => $config->get('path_toggle'),
    ];

    $args = [
      '%blog' => 'blog',
      '%blog-wildcard' => 'blog/*',
      '%front' => '<front>',
    ];

    $form['path']['path_list'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Listed paths'),
      '#description' => $this->t('Enter one relative path per line using the "*" character as a wildcard. Example paths are: "%blog" for the blog page, "%blog-wildcard" for each individual blog, and "%front" for the front page.', $args),
      '#default_value' => $config->get('path_list'),
      '#rows' => 10,
    ];

    // User roles tab.
    $form['role'] = [
      '#type' => 'details',
      '#title' => $this->t('User roles'),
      '#description' => $this->t('On this tab, specify the user role condition.'),
      '#group' => 'settings',
    ];

    $form['role']['role_toggle'] = [
      '#type' => 'radios',
      '#title' => t('Add snippet for specific roles'),
      '#options' => [
        GOOGLE_TAG_EXCLUDE_LISTED => $this->t('All roles except the selected roles'),
        GOOGLE_TAG_INCLUDE_LISTED => $this->t('Only the selected roles'),
      ],
      '#default_value' => $config->get('role_toggle'),
    ];

    $user_roles = array_map(function($role) {
      return $role->label();
    }, user_roles());

    $form['role']['role_list'] = [
      '#type' => 'checkboxes',
      '#title' => t('Selected roles'),
      '#default_value' => $config->get('role_list'),
      '#options' => $user_roles,
    ];

    // Response statuses tab.
    $description = t('Enter one response status per line. For more information, refer to the <a href="http://en.wikipedia.org/wiki/List_of_HTTP_status_codes">list of HTTP status codes</a>.');

    $form['status'] = [
      '#type' => 'details',
      '#title' => $this->t('Response statuses'),
      '#group' => 'settings',
      '#description' => t('On this tab, specify the page response status condition.'),
    ];

    $form['status']['status_toggle'] = [
      '#type' => 'radios',
      '#title' => $this->t('Add snippet for specific statuses'),
      '#options' => [
        GOOGLE_TAG_EXCLUDE_LISTED => $this->t('All statuses except the listed statuses'),
        GOOGLE_TAG_INCLUDE_LISTED => $this->t('Only the listed statuses'),
      ],
      '#default_value' => $config->get('status_toggle'),
    ];

    $form['status']['status_list'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Listed statuses'),
      '#description' => $description,
      '#default_value' => $config->get('status_list'),
      '#rows' => 5,
    ];

    // Advanced tab.
    $form['advanced'] = [
      '#type' => 'details',
      '#title' => $this->t('Advanced'),
      '#group' => 'settings',
    ];

    $form['advanced']['compact_snippet'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Compact the JavaScript snippet'),
      '#description' => $this->t('If checked, then the JavaScript snippet will be compacted to remove unnecessary whitespace. This is <strong>recommended on production sites</strong>. Leave unchecked to output a snippet that can be examined using a JavaScript debugger in the browser.'),
      '#default_value' => $config->get('compact_snippet'),
    ];

    $form['advanced']['include_file'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Include the snippet as a file'),
      '#description' => $this->t('If checked, then each JavaScript snippet will be included as a file. This is <strong>recommended</strong>. Leave unchecked to inline each snippet into the page. This only applies to data layer and script snippets.'),
      '#default_value' => $config->get('include_file'),
    ];

    $form['advanced']['data_layer'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Data layer'),
      '#description' => $this->t('The name of the data layer. Default value is "dataLayer". In most cases, use the default.'),
      '#default_value' => $config->get('data_layer'),
      '#attributes' => ['placeholder' => ['dataLayer']],
      '#required' => TRUE,
    ];

    $description = t('The types of tags, triggers, and variables <strong>allowed</strong> on a page. Enter one class per line. For more information, refer to the <a href="https://developers.google.com/tag-manager/devguide#security">developer documentation</a>.');

    $form['advanced']['whitelist_classes'] = [
      '#type' => 'textarea',
      '#title' => $this->t('White-listed classes'),
      '#description' => $description,
      '#default_value' => $config->get('whitelist_classes'),
      '#rows' => 5,
    ];

    $form['advanced']['blacklist_classes'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Black-listed classes'),
      '#description' => $this->t('The types of tags, triggers, and variables <strong>forbidden</strong> on a page. Enter one class per line.'),
      '#default_value' => $config->get('blacklist_classes'),
      '#rows' => 5,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Trim the text values.
    $container_id = trim($form_state->getValue('container_id'));
    $form_state->setValue('data_layer', trim($form_state->getValue('data_layer')));
    $form_state->setValue('path_list', $this->cleanText($form_state->getValue('path_list')));
    $form_state->setValue('status_list', $this->cleanText($form_state->getValue('status_list')));
    $form_state->setValue('whitelist_classes', $this->cleanText($form_state->getValue('whitelist_classes')));
    $form_state->setValue('blacklist_classes', $this->cleanText($form_state->getValue('blacklist_classes')));

    // Replace all types of dashes (n-dash, m-dash, minus) with a normal dash.
    $container_id = str_replace(['–', '—', '−'], '-', $container_id);
    $form_state->setValue('container_id', $container_id);

    if (!preg_match('/^GTM-\w{4,}$/', $container_id)) {
      // @todo Is there a more specific regular expression that applies?
      // @todo Is there a way to "test the connection" to determine a valid ID for
      // a container? It may be valid but not the correct one for the website.
      $form_state->setError($form['general']['container_id'], $this->t('A valid container ID is case sensitive and formatted like GTM-xxxxxx.'));
    }

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('google_tag.settings')
      ->set('container_id', $form_state->getValue('container_id'))
      ->set('path_toggle', $form_state->getValue('path_toggle'))
      ->set('path_list', $form_state->getValue('path_list'))
      ->set('role_toggle', $form_state->getValue('role_toggle'))
      ->set('role_list', $form_state->getValue('role_list'))
      ->set('status_toggle', $form_state->getValue('status_toggle'))
      ->set('status_list', $form_state->getValue('status_list'))
      ->set('compact_snippet', $form_state->getValue('compact_snippet'))
      ->set('include_file', $form_state->getValue('include_file'))
      ->set('data_layer', $form_state->getValue('data_layer'))
      ->set('whitelist_classes', $form_state->getValue('whitelist_classes'))
      ->set('blacklist_classes', $form_state->getValue('blacklist_classes'))
      ->save();

    parent::submitForm($form, $form_state);

    $this->saveSnippets();
  }

  /**
   * Saves JS snippet files based on current settings.
   *
   * @return bool
   *   Whether the files were saved.
   */
  public function saveSnippets() {
    // Save the altered snippets after hook_google_tag_snippets_alter().
    module_load_include('inc', 'google_tag', 'includes/snippet');
    $result = TRUE;
    $snippets = google_tag_snippets();
    foreach ($snippets as $type => $snippet) {
      $path = file_unmanaged_save_data($snippet, "public://js/google_tag.$type.js", FILE_EXISTS_REPLACE);
      $result = !$path ? FALSE : $result;
    }
    if (!$path) {
      drupal_set_message(t('An error occurred saving one or more snippet files. Please try again or contact the site administrator if it persists.'));
    }
    else {
      drupal_set_message(t('Created three snippet files based on configuration.'));
      \Drupal::service('asset.js.collection_optimizer')->deleteAll();
      _drupal_flush_css_js();
    }
  }

  /**
   * Cleans a string representing a list of items.
   *
   * @param string $text
   *   The string to clean.
   * @param string $format
   *   The final format of $text, either 'string' or 'array'.
   *
   * @return string
   *   The clean text.
   */
  public function cleanText($text, $format = 'string') {
    $text = explode("\n", $text);
    $text = array_map('trim', $text);
    $text = array_filter($text, 'trim');
    if ($format == 'string') {
      $text = implode("\n", $text);
    }
    return $text;
  }
}
