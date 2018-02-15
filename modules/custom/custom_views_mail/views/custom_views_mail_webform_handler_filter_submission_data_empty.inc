<?php


/**
 * Class custom_views_mail_webform_handler_filter_submission_data_empty
 * Provide Custom filter for webform submissions data.
 */
class custom_views_mail_webform_handler_filter_submission_data_empty extends webform_handler_filter_submission_data {
  function operators() {

    $operators = array();

    $operators['empty_select'] = array(
      'title' => t('Not Empty / empty (NULL) select'),
      'method' => 'op_empty_select',
      'short' => t('empty_select'),
      'values' => 1,
    );

    return $operators;
  }

  function op_empty_select($field) {

    if ($this->value == 'yes') {

      $this->query->add_where_expression($this->options['group'],
        "($field  IS NOT NULL AND $field <> '')",
        array());
    }
    else {

      $this->query->add_where_expression($this->options['group'],
        "($field  IS NULL OR $field = '')",
        array());
    }

  }

  /**
   * Provide a simple Select options
   */
  function value_form(&$form, &$form_state) {
    parent::value_form($form, $form_state);

    $default_value = 'All';

    if (empty($form_state['exposed'])) {
      // Add a select all option to the value form.
      $options = array('All' => t('Select all'));
    }
    $options['yes'] = t('Yes');
    $options['no'] = t('No');

    //convert to select
    $form['value']['#type'] = 'select';
    $form['value']['#required'] = 'FALSE';
    $form['value']['#options'] = $options;
    $form['value']['#default_value'] = $default_value;

    if (isset($form['value']['#size'])) {
      unset($form['value']['#size']);
    }

    if (!empty($form_state['exposed'])) {
      $identifier = $this->options['expose']['identifier'];

      if (empty($form_state['input'][$identifier])) {
        $form_state['input'][$identifier] = $default_value;
      }
    }

  }

}