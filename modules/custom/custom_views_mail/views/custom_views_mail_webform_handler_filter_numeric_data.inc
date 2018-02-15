<?php

/**
 * Numeric filter handler that works with Webform numeric submission data.
 *
 * @ingroup views_filter_handlers
 */
class custom_views_mail_webform_handler_filter_numeric_data extends webform_handler_filter_numeric_data {

  /**
   * Adds a simple operator condition to the query.
   */
  function op_simple($field) {

    static $sequence = 1;
    $param =  ":value" . $sequence++;
    $this->query->add_where_expression($this->options['group'],
      "($field  $this->operator $param AND $this->table_alias.$this->real_field <> '')",
      array($param =>  $this->value['value']));

  }

  /**
   * Adds a between or not-between condition to the query.
   */
  function op_between($field) {
    static $sequence = 1;
    $min =  ":min" . $sequence;
    $max =  ":max" . $sequence++;
    if ($this->operator == 'between') {
      $this->query->add_where_expression($this->options['group'],
        "(($min = '' OR $min <= $field) AND ($field <= $max OR $max = '' ) AND $field <> '')",
        array($min => $this->value['min'], $max => $this->value['max']));
    }
    else {
      $this->query->add_where_expression($this->options['group'],
        "($min > $field OR $field > $max)",
        array($min => $this->value['min'], $max => $this->value['max']));
    }
  }

  function value_form(&$form, &$form_state) {
    parent::value_form($form, $form_state);

    if(!empty($form['value']['max']) && $this->operator == 'between') {
      $form['value']['max']['#title'] = t('And less than');
    }

  }


}