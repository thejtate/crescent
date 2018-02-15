<?php

/**
 * @file
 * Default simple view template to display a group of summary lines.
 *
 * This wraps items in a span if set to inline, or a div if not.
 *
 * @ingroup views_templates
 */
?>
<?php foreach ($rows as $id => $row): ?>
  <?php print (!empty($options['inline']) ? '<span' : '<div') . ' class="views-summary views-summary-unformatted">'; ?>
    <?php if (!empty($row->separator)) { print $row->separator; } ?>

    <?php
      $additional_class = isset($row->taxonomy_term_data_name) ? $row->taxonomy_term_data_name : "";
      $additional_class = strtolower($additional_class);
      $additional_class = preg_replace('@[^a-z0-9_]+@','_',$additional_class);
      $row_classes[$id] = (!empty($row_classes[$id])) ? ($row_classes[$id] . ' ' . $additional_class) : $additional_class;

      $active_url = "blog/" . arg(1) . arg(2) . arg(3);
      $active = ($row->url == $active_url) ? "active" : "";
      $row_classes[$id] = (!empty($row_classes[$id])) ? ($row_classes[$id] . ' ' . $active) : $active;
    ?>

    <a href="<?php print $row->url; ?>"<?php print !empty($row_classes[$id]) ? ' class="' . $row_classes[$id] . '"' : ''; ?>><?php print $row->link; ?></a>
    <?php if (!empty($options['count'])): ?>
      (<?php print $row->count; ?>)
    <?php endif; ?>
  <?php print !empty($options['inline']) ? '</span>' : '</div>'; ?>
<?php endforeach; ?>