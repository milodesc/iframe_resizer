<?php

/**
 * @file
 * iframe Resizer administration pages.
 */

/**
 * Returns with the general configuration form.
 */
function iframe_resizer_admin_form($form, &$form_state) {
  $form['iframe_resizer_usage'] = array(
    '#type' => 'fieldset',
    '#title' => t('iFrame Resizer Usage'),
    '#description' => t('At least one of the options below should be chosen. Otherwise, this module won\'t do anything.'),
  );
  $form['iframe_resizer_usage']['iframe_resizer_host'] = array(
    '#type' => 'checkbox',
    '#title' => t('This site will host resizable iFrames.'),
    '#default_value' => variable_get('iframe_resizer_host', FALSE),
    '#description' => t('Enable this option if the iFrames being included in this site should be resizable (Note that the site being iFramed in will need to include the iFrame Resizer library\'s iframeResizer.contentWindow.js file).'),
  );
  $form['iframe_resizer_usage']['iframe_resizer_hosted'] = array(
    '#type' => 'checkbox',
    '#title' => t('Pages from this site will be hosted within iFrames that have been made resizable by the iFrame Resizer JavaScript library.'),
    '#default_value' => variable_get('iframe_resizer_hosted', FALSE),
    '#description' => t('Enable this option if sites using the iFrame Resizer library will be hosting pages from your site in an iFrame.'),
  );

  $form['iframe_resizer_advanced'] = array(
    '#type' => 'fieldset',
    '#title' => t('Advanced'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['iframe_resizer_advanced']['iframe_resizer_log'] = array(
    '#type' => 'checkbox',
    '#title' => t('Turn iFrame Resizer JavaScript console logging on.'),
    '#description' => t('Setting the log option to true will make the scripts in both the host page and the iFrame output everything they do to the JavaScript console so you can see the communication between the two scripts.'),
    '#default_value' => variable_get('iframe_resizer_log', FALSE),
  );
  $form['iframe_resizer_advanced']['iframe_resizer_height_calculation_method'] = array(
    '#type' => 'select',
    '#title' => t('iFrame Height Calculation Method'),
    '#description' => t('Different circumstances require different methods of calculating the height of the iFramed content.'),
    '#default_value' => variable_get('iframe_resizer_height_calculation_method', 'bodyOffset'),
    '#options' => drupal_map_assoc(array(
      'bodyOffset',
      'bodyScroll',
      'documentElementOffset',
      'documentElementScroll',
      'max',
      'min',
      'grow',
      'lowestElement',
      'taggedElement',
    )),
  );

  return system_settings_form($form);
}
