<?php

namespace Drupal\iframe_resizer\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class IframeResizerSettingsForm.
 *
 * @package Drupal\iframe_resizer\Form
 */
class IframeResizerSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'iframe_resizer_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('iframe_resizer.settings');

    $form['iframe_resizer_usage'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('iFrame Resizer Usage'),
      '#description' => $this->t("At least one of the checkboxes in this section should be checked. Otherwise, this module won't do anything."),
    ];
    $form['iframe_resizer_usage']['host'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('This site will host resizable iFrames.'),
      '#description' => $this->t("Enable this option if the iFrames being included in this site should be resizable (Note that the site being iFramed in will need to include the iFrame Resizer library's iframeResizer.contentWindow.js file)."),
      '#default_value' => $config->get('iframe_resizer_usage.host'),
    ];
    $form['iframe_resizer_usage']['hosted'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Pages from this site will be hosted within iFrames that have been made resizable by the iFrame Resizer JavaScript library.'),
      '#description' => $this->t('Enable this option if sites using the iFrame Resizer library will be hosting pages from your site in an iFrame.'),
      '#default_value' => $config->get('iframe_resizer_usage.hosted'),
    ];

    // Set up advanced configuration options for sites hosting resizable
    // iFrames.
    $form['iframe_resizer_advanced'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Advanced Options for Hosting Resizable iFrames'),
      '#collapsible' => TRUE,
      '#states' => array(
        'visible' => array(
          'input[name="host"]' => array('checked' => TRUE),
        ),
      ),
    );
    $form['iframe_resizer_advanced']['target_type'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Which iFrames should be targeted by the iFrame Resizer library?'),
      '#default_value' => $config->get('iframe_resizer_advanced.target_type'),
      '#options' => array(
        'all_iframes' => $this->t('All iFrames'),
        'specific' => $this->t('Specific iFrames'),
      ),
    );
    $form['iframe_resizer_advanced']['target_selectors'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Specify the iFrames which should be targeted by the iFrame Resizer library by supplying jQuery selectors.'),
      '#default_value' => $config->get('iframe_resizer_advanced.target_selectors'),
      '#description' => $this->t('Use one or more jQuery selectors (for example, "#iframe-id" or "div.content > .iframe-class" without the quotation marks) to specify which hosted iFrames should be targeted by the iFrame Resizer library. Enter one selector per line.'),
      '#states' => array(
        'disabled' => array(
          'input[name="target_type"]' => array('value' => 'all_iframes'),
        ),
        'enabled' => array(
          'input[name="target_type"]' => array('value' => 'specific'),
        ),
        'required' => array(
          'input[name="target_type"]' => array('value' => 'specific'),
        ),
      ),
    );
    $form['iframe_resizer_advanced']['override_defaults'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Override the default behavior of the iFrame Resizer library.'),
      '#default_value' => $config->get('iframe_resizer_advanced.override_defaults'),
    );

    $form['iframe_resizer_advanced']['iframe_resizer_options'] = array(
      '#type' => 'fieldset',
      '#title' => t('Configure the options made available by the iFrame Resizer library'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      '#states' => array(
        'visible' => array(
          'input[name="override_defaults"]' => array('checked' => TRUE),
        ),
      ),
    );
    $form['iframe_resizer_advanced']['iframe_resizer_options']['log'] = array(
      '#type' => 'checkbox',
      '#title' => t('Turn iFrame Resizer JavaScript console logging on.'),
      '#description' => t('Setting the log option to true will make the scripts in both the host page and the iFrame output everything they do to the JavaScript console so you can see the communication between the two scripts.'),
      '#default_value' => $config->get('iframe_resizer_advanced.options.log'),
    );
    $height_calc_options = array(
      'bodyOffset',
      'bodyScroll',
      'documentElementOffset',
      'documentElementScroll',
      'max',
      'min',
      'grow',
      'lowestElement',
      'taggedElement',
    );
    $form['iframe_resizer_advanced']['iframe_resizer_options']['heightCalculationMethod'] = array(
      '#type' => 'select',
      '#title' => t('iFrame Height Calculation Method'),
      '#description' => t('Different circumstances require different methods of calculating the height of the iFramed content. The iframe resizer library default is bodyOffset.'),
      '#default_value' => $config->get('iframe_resizer_advanced.options.heightCalculationMethod'),
      '#options' => array_combine($height_calc_options, $height_calc_options),
      '#states' => array(
        'required' => array(
          'input[name="override_defaults"]' => array('checked' => TRUE),
        ),
      ),
    );
    $width_calc_options = array(
      'scroll',
      'bodyOffset',
      'bodyScroll',
      'documentElementOffset',
      'documentElementScroll',
      'max',
      'min',
      'rightMostElement',
      'taggedElement',
    );
    $form['iframe_resizer_advanced']['iframe_resizer_options']['widthCalculationMethod'] = array(
      '#type' => 'select',
      '#title' => t('iFrame Width Calculation Method'),
      '#description' => t('Different circumstances require different methods of calculating the width of the iFramed content. The iframe resizer library default is scroll.'),
      '#default_value' => $config->get('iframe_resizer_advanced.options.widthCalculationMethod'),
      '#options' => array_combine($width_calc_options, $width_calc_options),
      '#states' => array(
        'required' => array(
          'input[name="override_defaults"]' => array('checked' => TRUE),
        ),
      ),
    );

    $form['iframe_resizer_advanced']['iframe_resizer_options']['autoResize'] = array(
      '#type' => 'checkbox',
      '#title' => t('Automatically resize the iFrame when its DOM changes.'),
      '#description' => t('Checked by default'),
      '#default_value' => $config->get('iframe_resizer_advanced.options.autoResize'),
    );

    $form['iframe_resizer_advanced']['iframe_resizer_options']['bodyBackground'] = array(
      '#type' => 'textfield',
      '#title' => t('iFrame body background CSS'),
      '#description' => t("Override the body background style of the iFrame. Leave blank to use the iFrame's default background."),
      '#default_value' => $config->get('iframe_resizer_advanced.options.bodyBackground'),
    );

    $form['iframe_resizer_advanced']['iframe_resizer_options']['bodyMargin'] = array(
      '#type' => 'textfield',
      '#title' => t('iFrame body margin CSS'),
      '#description' => t("Override the iFrame's body's margin styles. Leave blank to use the iFrame's default body margin styles."),
      '#default_value' => $config->get('iframe_resizer_advanced.options.bodyMargin'),
    );

    $form['iframe_resizer_advanced']['iframe_resizer_options']['inPageLinks'] = array(
      '#type' => 'checkbox',
      '#title' => t('Enable in page linking inside the iFrame and from the iFrame to the parent page'),
      '#default_value' => $config->get('iframe_resizer_advanced.options.inPageLinks'),
    );

    $form['iframe_resizer_advanced']['iframe_resizer_options']['interval'] = array(
      '#type' => 'number',
      '#title' => t('Page size change check interval'),
      '#description' => t("How often to check (in milliseconds) for page size changes in browsers which don't support mutationObserver. Default is 32. Setting this property to a negative number will force the interval check to run instead of mutationObserver. Set to zero to disable."),
      '#default_value' => $config->get('iframe_resizer_advanced.options.interval'),
      '#size' => 5,
      '#states' => array(
        'required' => array(
          'input[name="override_defaults"]' => array('checked' => TRUE),
        ),
      ),
    );

    // If the maxHeight value is negative, display an empty text field. That
    // will be interpreted as Infinity.
    $max_height_default = '';
    if ($config->get('iframe_resizer_advanced.options.maxHeight') >= 0) {
      $max_height_default = $config->get('iframe_resizer_advanced.options.maxHeight');
    }
    $form['iframe_resizer_advanced']['iframe_resizer_options']['maxHeight'] = array(
      '#type' => 'number',
      '#min' => 0,
      '#title' => t('Maximum height of the iFrame (in pixels)'),
      '#description' => t("Leave blank to set no maximum, the default."),
      '#default_value' => $max_height_default,
      '#size' => 8,
    );

    // If the maxWidth value is negative, display an empty text field. That will
    // be interpreted as Infinity.
    $max_width_default = '';
    if ($config->get('iframe_resizer_advanced.options.maxWidth') >= 0) {
      $max_width_default = $config->get('iframe_resizer_advanced.options.maxWidth');
    }
    $form['iframe_resizer_advanced']['iframe_resizer_options']['maxWidth'] = array(
      '#type' => 'number',
      '#min' => 0,
      '#title' => t('Maximum width of the iFrame (in pixels)'),
      '#description' => t("Leave blank to set no maximum, the default."),
      '#default_value' => $max_width_default,
      '#size' => 8,
    );

    $min_height_default = $config->get('iframe_resizer_advanced.options.minHeight');
    if ($min_height_default == 0) {
      $min_height_default = '';
    }
    $form['iframe_resizer_advanced']['iframe_resizer_options']['minHeight'] = array(
      '#type' => 'number',
      '#min' => 0,
      '#title' => t('Minimum height of the iFrame (in pixels)'),
      '#description' => t('Leave blank to set no minimum, the default.'),
      '#default_value' => $min_height_default,
      '#size' => 8,
    );

    $min_width_default = $config->get('iframe_resizer_advanced.options.minWidth');
    if ($min_width_default == 0) {
      $min_width_default = '';
    }
    $form['iframe_resizer_advanced']['iframe_resizer_options']['minWidth'] = array(
      '#type' => 'number',
      '#min' => 0,
      '#title' => t('Minimum width of the iFrame (in pixels)'),
      '#description' => t('Leave blank to set no minimum, the default.'),
      '#default_value' => $min_width_default,
      '#size' => 8,
    );

    $form['iframe_resizer_advanced']['iframe_resizer_options']['resizeFrom'] = array(
      '#type' => 'select',
      '#title' => t('Resize event listener'),
      '#description' => t('Listen for resize events from the parent page, or the iFrame. \'Parent\' is the library default.'),
      '#default_value' => $config->get('iframe_resizer_advanced.options.resizeFrom'),
      '#options' => array(
        'parent' => t('Parent'),
        'child' => t('Child'),
      ),
      '#states' => array(
        'required' => array(
          'input[name="override_defaults"]' => array('checked' => TRUE),
        ),
      ),
    );

    $form['iframe_resizer_advanced']['iframe_resizer_options']['scrolling'] = array(
      '#type' => 'checkbox',
      '#title' => t('Enable scroll bars in iFrame'),
      '#default_value' => $config->get('iframe_resizer_advanced.options.scrolling'),
      '#description' => t('Disabled by default.'),
    );

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    // If the admin chooses to only target specific iFrames, ensure they've told
    // us which ones.
    $target_selectors = trim($form_state->getValue('target_selectors'));
    if ($form_state->getValue('target_type') == 'specific' && empty($target_selectors)) {
      $form_state->setErrorByName('target_selectors', $this->t('You must specify at least one jQuery selector.'));
    }

    // Find all of the fields that are required if the user is overriding
    // defaults and display a validation error if they weren't supplied.
    if ($form_state->getValue('override_defaults') !== 0) {
      $fields_reqd_override = array();
      foreach ($form['iframe_resizer_advanced']['iframe_resizer_options'] as $field_name => $field_value) {
        if (is_array($field_value) && isset($field_value['#states']['required']['input[name="override_defaults"]']['checked']) && $field_value['#states']['required']['input[name="override_defaults"]']['checked'] === TRUE) {
          $fields_reqd_override[$field_name] = $field_value['#title'];
        }
      }
      foreach ($fields_reqd_override as $field_name => $field_title) {
        if (trim($form_state->getValue($field_name)) === '') {
          $form_state->setErrorByName($field_name, $this->t('%name field is required.', array('%name' => $field_title)));
        }
      }
    }

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $config = $this->configFactory->getEditable('iframe_resizer.settings');

    // If the user left the maxHeight or maxWidth field blank, we'll store it in
    // the config settings as -1 and interpret that as Infinity in the JS.
    $max_height = $form_state->getValue('maxHeight');
    if ($max_height === '') {
      $max_height = -1;
    }

    $max_width = $form_state->getValue('maxWidth');
    if ($max_width === '') {
      $max_width = -1;
    }

    // Set and save the configuration data. Check booleans against '=== 1' so
    // we store booleans instead of ints.
    $config
      ->set('iframe_resizer_usage.host', $form_state->getValue('host') === 1)
      ->set('iframe_resizer_usage.hosted', $form_state->getValue('hosted') === 1)
      ->set('iframe_resizer_advanced.target_type', $form_state->getValue('target_type'))
      ->set('iframe_resizer_advanced.target_selectors', $form_state->getValue('target_selectors'))
      ->set('iframe_resizer_advanced.override_defaults', $form_state->getValue('override_defaults') === 1)
      ->set('iframe_resizer_advanced.options.log', $form_state->getValue('log') === 1)
      ->set('iframe_resizer_advanced.options.heightCalculationMethod', $form_state->getValue('heightCalculationMethod'))
      ->set('iframe_resizer_advanced.options.widthCalculationMethod', $form_state->getValue('widthCalculationMethod'))
      ->set('iframe_resizer_advanced.options.autoResize', $form_state->getValue('autoResize') === 1)
      ->set('iframe_resizer_advanced.options.bodyBackground', $form_state->getValue('bodyBackground'))
      ->set('iframe_resizer_advanced.options.bodyMargin', $form_state->getValue('bodyMargin'))
      ->set('iframe_resizer_advanced.options.inPageLinks',  $form_state->getValue('inPageLinks') === 1)
      ->set('iframe_resizer_advanced.options.interval', (int) $form_state->getValue('interval'))
      ->set('iframe_resizer_advanced.options.maxHeight', (int) $max_height)
      ->set('iframe_resizer_advanced.options.maxWidth', (int) $max_width)
      ->set('iframe_resizer_advanced.options.minHeight', (int) $form_state->getValue('minHeight'))
      ->set('iframe_resizer_advanced.options.minWidth', (int) $form_state->getValue('minWidth'))
      ->set('iframe_resizer_advanced.options.resizeFrom', $form_state->getValue('resizeFrom'))
      ->set('iframe_resizer_advanced.options.scrolling', (bool) $form_state->getValue('scrolling'));

    $config->save();

    parent::submitForm($form, $form_state);

  }

  /**
   * Gets the configuration names that will be editable.
   *
   * @return array
   *   An array of configuration object names that are editable if called in
   *   conjunction with the trait's config() method.
   */
  protected function getEditableConfigNames() {
    return ['iframe_resizer.settings'];
  }

}
