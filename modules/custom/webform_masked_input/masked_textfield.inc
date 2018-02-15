<?php
/**
 * @file Webform module masked textfield component.
 */

module_load_include('inc', 'webform', 'components/textfield');

/**
 * Implements _webform_defaults_component().
 */
function _webform_defaults_masked_textfield() {
  return array(
    'name' => '',
    'form_key' => NULL,
    'pid' => 0,
    'weight' => 0,
    'value' => '',
    'required' => 0,
    'extra' => array(
      'width' => '',
      'maxlength' => '',
      'field_prefix' => '',
      'field_suffix' => '',
      'disabled' => 0,
      'unique' => 0,
      'title_display' => 0,
      'description' => '',
      'placeholder' => '',
      'attributes' => array(),
      'private' => FALSE,
      'analysis' => FALSE,
      'mask' => '',
      'mask_placeholder' => '_',
      'mask_as_placeholder' => TRUE,
      'clean_mask_from_value' => FALSE,
    ),
  );
}

/**
 * Implements _webform_theme_component().
 */
function _webform_theme_masked_textfield() {
  return array();
}

/**
 * Implements _webform_edit_component().
 */
function _webform_edit_masked_textfield($component) {
  $form =_webform_edit_textfield($component);

  $form['display']['mask_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('Masked input settings'),
    '#collapsible' => FALSE,
    '#weight' => -20,
  );

  $form['display']['mask_settings']['mask'] = array(
    '#type' => 'textfield',
    '#title' => t('Mask'),
    '#description' => '',
    '#default_value' => $component['extra']['mask'],
    '#parents' => array('extra', 'mask'),
  );

  $definitions_for_render = masked_input_view_configured_definitions();

  $form['display']['mask_settings']['mask']['#description'] .= t('A mask is defined by a format made up of mask literals and mask definitions. Any character not in the definitions list below is considered a mask literal. Mask literals will be automatically entered for the user as they type and will not be able to be removed by the user.') . ' ';
  $form['display']['mask_settings']['mask']['#description'] .= t('Here is a list of definitions that already exist, you can create more at !link', array('!link' => l('admin/config/user-interface/masked_input', 'admin/config/user-interface/masked_input', array('query' => array('destination' => $_GET['q'])))));
  $form['display']['mask_settings']['mask']['#description'] .= render($definitions_for_render);

  $form['display']['mask_settings']['mask_as_placeholder'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display mask as element placehoder.'),
    '#default_value' => $component['extra']['mask_as_placeholder'],
    '#parents' => array('extra', 'mask_as_placeholder'),
  );

  $form['display']['mask_settings']['clean_mask_from_value'] = array(
    '#type' => 'checkbox',
    '#title' => t('Submit value without mask.'),
    '#description' => t('Value will be submitted without mask, example: (999)999-99 => 99999999'),
    '#default_value' => $component['extra']['clean_mask_from_value'],
    '#parents' => array('extra', 'clean_mask_from_value'),
  );

  $form['display']['mask_settings']['mask_placeholder'] = array(
    '#type' => 'textfield',
    '#size' => 1,
    '#title' => t('Mask Placeholder'),
    '#description' => t('Optionally, if you are not satisfied with the underscore ("_") character as a placeholder, you may pass an optional argument to the masked_input method.'),
    '#default_value' => $component['extra']['mask_placeholder'],
    '#parents' => array('extra', 'mask_placeholder'),
  );

  return $form;
}

/**
 * Implements _webform_render_component().
 */
function _webform_render_masked_textfield($component, $value = NULL, $filter = TRUE) {

  $element = _webform_render_textfield($component, $value, $filter);

  $element['#process'] = array('masked_input_process_callback', 'ajax_process_form');
  $element['#mask'] = $component['extra']['mask'];
  $element['#placeholder'] = $component['extra']['mask_placeholder'];
  $element['#definitions'] = array();

  if(!empty($component['extra']['clean_mask_from_value'])) {
    $element['#value_callback'] = 'webform_masked_input_clean_mask_value';
  }

  if(!empty($component['extra']['mask_as_placeholder'])) {
    $element['#attributes']['placeholder'] = _webform_masked_input_get_placehoder_from_mask(
      $component['extra']['mask'],
      $component['extra']['mask_placeholder']
    );
  }


  return $element;
}

/**
 * Implements _webform_display_component().
 */
function _webform_display_masked_textfield($component, $value, $format = 'html') {
  $display = _webform_display_textfield($component, $value, $format);
  return $display;
}

/**
 * Format the output of data for this component.
 */
function theme_webform_display_masked_textfield($variables) {
  $output = theme_webform_display_textfield($variables);
  return $output;
}

/**
 * Implements _webform_analysis_component().
 */
function _webform_analysis_masked_textfield($component, $sids = array()) {
  $output = _webform_analysis_textfield($component, $sids);

  return $output;
}

/**
 * Implements _webform_table_component().
 */
function _webform_table_masked_textfield($component, $value) {
  $output = _webform_table_textfield($component, $value);
  return $output;
}

/**
 * Implements _webform_csv_headers_component().
 */
function _webform_csv_headers_masked_textfield($component, $export_options) {
  $header = _webform_csv_headers_textfield($component, $export_options);
  return $header;
}

/**
 * Implements _webform_csv_data_component().
 */
function _webform_csv_data_masked_textfield($component, $export_options, $value) {
  $result = _webform_csv_data_textfield($component, $export_options, $value);
  return $result;
}

