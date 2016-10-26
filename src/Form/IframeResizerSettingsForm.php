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
    $form['iframe_resizer_advanced']['iframe_resizer_options']['height_calculation_method'] = array(
      '#type' => 'select',
      '#title' => t('iFrame Height Calculation Method'),
      '#description' => t('Different circumstances require different methods of calculating the height of the iFramed content. The iframe resizer library default is bodyOffset.'),
      '#default_value' => $config->get('iframe_resizer_advanced.options.height_calculation_method'),
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
    $form['iframe_resizer_advanced']['iframe_resizer_options']['width_calculation_method'] = array(
      '#type' => 'select',
      '#title' => t('iFrame Width Calculation Method'),
      '#description' => t('Different circumstances require different methods of calculating the width of the iFramed content. The iframe resizer library default is scroll.'),
      '#default_value' => $config->get('iframe_resizer_advanced.options.width_calculation_method'),
      '#options' => array_combine($width_calc_options, $width_calc_options),
      '#states' => array(
        'required' => array(
          'input[name="override_defaults"]' => array('checked' => TRUE),
        ),
      ),
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

    $config
      ->set('iframe_resizer_usage.host', $form_state->getValue('host'))
      ->set('iframe_resizer_usage.hosted', $form_state->getValue('hosted'))
      ->set('iframe_resizer_advanced.target_type', $form_state->getValue('target_type'))
      ->set('iframe_resizer_advanced.target_selectors', $form_state->getValue('target_selectors'))
      ->set('iframe_resizer_advanced.override_defaults', $form_state->getValue('override_defaults'))
      ->set('iframe_resizer_advanced.options.log', $form_state->getValue('log'))
      ->set('iframe_resizer_advanced.options.height_calculation_method', $form_state->getValue('height_calculation_method'))
      ->set('iframe_resizer_advanced.options.width_calculation_method', $form_state->getValue('width_calculation_method'));

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
