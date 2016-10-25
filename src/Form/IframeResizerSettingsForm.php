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

    // Set up advanced configuration options for sites hosting resizable iFrames.
    $form['iframe_resizer_advanced'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Advanced Options for Hosting Resizable iFrames'),
      '#collapsible' => TRUE,
      '#states' => array(
        'visible' => array(
          'input[name="host"]' => array('checked' => TRUE),
        ),
      ),
      '#description' => $this->t('Advanced options to be applied when this site will be hosting resizeable iFrames.'),
    );
    $form['iframe_resizer_advanced']['target_type'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Which iFrames should be targeted by the iFrame Resizer library?'),
      '#default_value' => $config->get('iframe_resizer_advanced.target_type'),
      '#options' => array(
        'all_iframes' => $this->t('All iFrames'),
        'specific' => $this->t('Specific iFrames')
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
      ->set('iframe_resizer_advanced.override_defaults', $form_state->getValue('override_defaults'));

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
    return ['iframe_resizer.settings',];
  }
}
