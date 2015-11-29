<?php

/**
 * @file
 * Iframe Resizer administration pages.
 */

/**
 * Returns with the general configuration form.
 */
function iframe_resizer_admin_form($form, &$form_state) {

  $library_path = iframe_resizer_get_library_path();
  if (!$library_path) {
    drupal_set_message(t('You need to download the !library and extract the entire contents of the archive into the %path directory on your server.', array(
      '!library' => l(t('iFrame Resizer JavaScript file'), IFRAME_RESIZER_LIBRARY_URL),
      '%path' => 'sites/all/libraries')
    ), 'error');
    return;
  }

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
    '#title' => t('Advanced Options for Hosting Resizable iFrames'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#states' => array(
      'visible' => array(
        'input[name="iframe_resizer_host"]' => array('checked' => TRUE),
      ),
    ),
  );

  $form['iframe_resizer_advanced']['iframe_resizer_target_type'] = array(
    '#type' => 'radios',
    '#title' => t('Which iFrames should be targeted by the iFrame Resizer library?'),
    '#default_value' => variable_get('iframe_resizer_target_type', 'all_iframes'),
    '#options' => array('all_iframes' => t('All iFrames'), 'specific' => t('Specific iFrames')),
  );
  $form['iframe_resizer_advanced']['iframe_resizer_target_specifiers'] = array(
    '#type' => 'textarea',
    '#title' => t('Specify the iFrames which should be targeted by the iFrame Resizer library by supplying jQuery selectors.'),
    '#default_value' => variable_get('iframe_resizer_target_specifiers', ''),
    '#description' => t('Use one or more jQuery selectors (one per line) to specify which hosted iFrames should be targeted by the iFrame Resizer library.'),
    '#states' => array(
      'disabled' => array(
        'input[name="iframe_resizer_target_type"]' => array('value' => 'all_iframes'),
      ),
      'enabled' => array(
        'input[name="iframe_resizer_target_type"]' => array('value' => 'specific'),
      ),
      'required' => array(
        'input[name="iframe_resizer_target_type"]' => array('value' => 'specific'),
      ),
    ),
  );

  $form['iframe_resizer_advanced']['iframe_resizer_override_defaults'] = array(
    '#type' => 'checkbox',
    '#title' => t('Override the default behavior of the iFrame Resizer library.'),
    '#default_value' => variable_get('iframe_resizer_override_defaults', FALSE),
  );

  $form['iframe_resizer_advanced']['iframe_resizer_options'] = array(
    '#type' => 'fieldset',
    '#title' => t('Configure the options made available by the iFrame Resizer library'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#states' => array(
      'visible' => array(
        'input[name="iframe_resizer_override_defaults"]' => array('checked' => TRUE),
      ),
    )
  );
  $form['iframe_resizer_advanced']['iframe_resizer_options']['iframe_resizer_log'] = array(
    '#type' => 'checkbox',
    '#title' => t('Turn iFrame Resizer JavaScript console logging on.'),
    '#description' => t('Setting the log option to true will make the scripts in both the host page and the iFrame output everything they do to the JavaScript console so you can see the communication between the two scripts.'),
    '#default_value' => variable_get('iframe_resizer_log', FALSE),
  );
  $height_calc_options = drupal_map_assoc(array(
    'bodyScroll',
    'documentElementOffset',
    'documentElementScroll',
    'max',
    'min',
    'grow',
    'lowestElement',
    'taggedElement',
  ));
  $height_calc_options = array('bodyOffset' => 'Use library default (bodyOffset)') + $height_calc_options;
  $form['iframe_resizer_advanced']['iframe_resizer_options']['iframe_resizer_height_calculation_method'] = array(
    '#type' => 'select',
    '#title' => t('iFrame Height Calculation Method'),
    '#description' => t('Different circumstances require different methods of calculating the height of the iFramed content.'),
    '#default_value' => variable_get('iframe_resizer_height_calculation_method', 'bodyOffset'),
    '#options' => $height_calc_options,
    '#states' => array(
      'required' => array(
        'input[name="iframe_resizer_override_defaults"]' => array('checked' => TRUE),
      ),
    ),
  );
  $width_calc_options = drupal_map_assoc(array(
    'bodyOffset',
    'bodyScroll',
    'documentElementOffset',
    'documentElementScroll',
    'max',
    'min',
    'rightMostElement',
    'taggedElement',
  ));
  $width_calc_options = array('scroll' => 'Use library default (scroll)') + $width_calc_options;
  $form['iframe_resizer_advanced']['iframe_resizer_options']['iframe_resizer_width_calculation_method'] = array(
    '#type' => 'select',
    '#title' => t('iFrame Width Calculation Method'),
    '#description' => t('Different circumstances require different methods of calculating the width of the iFramed content.'),
    '#default_value' => variable_get('iframe_resizer_width_calculation_method', 'scroll'),
    '#options' => $width_calc_options,
    '#states' => array(
      'required' => array(
        'input[name="iframe_resizer_override_defaults"]' => array('checked' => TRUE),
      ),
    ),
  );

  $form['iframe_resizer_advanced']['iframe_resizer_options']['iframe_resizer_autoresize'] = array (
    '#type' => 'checkbox',
    '#title' => t('Automatically resize the iFrame when its DOM changes.'),
    '#description' => t('Checked by default'),
    '#default_value' => variable_get('iframe_resizer_autoresize', TRUE),
  );

  $form['iframe_resizer_advanced']['iframe_resizer_options']['iframe_resizer_bodybackground'] = array (
    '#type' => 'textfield',
    '#title' => t('iFrame body background CSS'),
    '#description' => t("Override the body background style of the iFrame. Leave blank to use the iFrame's default background."),
    '#default_value' => variable_get('iframe_resizer_bodybackground', ''),
  );

  $form['iframe_resizer_advanced']['iframe_resizer_options']['iframe_resizer_bodymargin'] = array (
    '#type' => 'textfield',
    '#title' => t('iFrame body margin CSS'),
    '#description' => t("Override the iFrame's body's margin styles. Leave blank to use the iFrame's default body margin styles."),
    '#default_value' => variable_get('iframe_resizer_bodymargin', ''),
  );

  $form['iframe_resizer_advanced']['iframe_resizer_options']['iframe_resizer_inpagelinks'] = array (
    '#type' => 'checkbox',
    '#title' => t('Enable in page linking inside the iFrame and from the iFrame to the parent page'),
    '#default_value' => variable_get('iframe_resizer_inpagelinks', FALSE),
  );

  $form['iframe_resizer_advanced']['iframe_resizer_options']['iframe_resizer_interval'] = array (
    '#type' => 'textfield',
    '#title' => t('Page size change check interval'),
    '#description' => t("How often to check (in milliseconds) for page size changes in browsers which don't support mutationObserver. Default is 32. Setting this property to a negative number will force the interval check to run instead of mutationObserver. Set to zero to disable."),
    '#default_value' => variable_get('iframe_resizer_interval', 32),
    '#element_validate' => array('element_validate_integer'),
    '#size' => 5,
  );

  $form['#validate'][] = 'iframe_resizer_admin_form_validate';

  return system_settings_form($form);
}

/**
 * Provides validation for the general configuration form.
 */
function iframe_resizer_admin_form_validate($form, &$form_state) {
  if ($form_state['values']['iframe_resizer_target_type'] == 'specific' && empty($form_state['values']['iframe_resizer_target_specifiers'])) {
    form_set_error('iframe_resizer_target_specifiers', t('You must specify at least one jQuery selector.'));
  }
}
