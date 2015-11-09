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

  return system_settings_form($form);
}
