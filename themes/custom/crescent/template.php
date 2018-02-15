<?php

/**
 * @file
 * template.php
 *
 * Contains theme override functions and preprocess functions for the theme.
 */


define("CRESCENT_CONTACT_NID", 13);
define("CRESCENT_CAREERS_WEBFORM_NID", 92);
define("CRESCENT_CONTACT_VOC_NAME", "locations");


//define("CRESCENT_BLOG_CATEGORY_SERVICES_TID", 65);
//define("CRESCENT_BLOG_CATEGORY_AMERIFLOW_TID", 66);
//define("CRESCENT_BLOG_CATEGORY_SJB_TID", 67);


define("CRESCENT_BLOG_CATEGORY_SERVICES_TID", 5);
define("CRESCENT_BLOG_CATEGORY_AMERIFLOW_TID", 6);
define("CRESCENT_BLOG_CATEGORY_SJB_TID", 7);


/**
 * Implements hook_preprocess_html().
 */
function crescent_preprocess_html(&$vars) {
  $html5 = array(
    '#tag' => 'script',
    '#attributes' => array(
      'src' => base_path() . drupal_get_path('theme', 'crescent') . '/js/lib/html5.js',
    ),
    '#prefix' => '<!--[if (lt IE 9) & (!IEMobile)]>',
    '#suffix' => '</script><![endif]-->',
  );
  drupal_add_html_head($html5, 'crescent_html5');

  $vars['classes_array'][] = 'page';
  if ($node = menu_get_object()) {
    switch ($node->type) {
      case 'home':
        $vars['classes_array'][] = 'page-home';
        break;
      case 'health_safety':
        $vars['classes_array'][] = 'page-healthsafety';
        break;
      case 'fblog_post':
        $vars['classes_array'][] = 'page-news';
        break;
      case 'contact':
        $vars['classes_array'][] = 'page-contact';
        break;
      case 'enviroedge':
        $vars['classes_array'][] = 'page-enviro';
        break;
      case 'careers_webform':
        $vars['classes_array'][] = 'page-careers';
        break;
      case 'careers_landing':
        $vars['classes_array'][] = 'page-careers';
        break;
      case 'who_we_are':
        $vars['classes_array'][] = 'page-about page-who-we-are';
        break;
    }
  }

  if (in_array("html__blog", $vars['theme_hook_suggestions'])) {
    $vars['classes_array'][] = 'page-news';
  }

  if (in_array("html__remote_blog", $vars['theme_hook_suggestions'])) {
    $vars['classes_array'][] = 'page-news';
  }

  if (in_array("html__remote_blog_post", $vars['theme_hook_suggestions'])) {
    $vars['classes_array'][] = 'page-news';
  }
}


/**
 * Implements hook_preprocess_page().
 */
function crescent_preprocess_page(&$vars) {
  $vars['main_menu'] = (module_exists("fmenu")) ? fmenu_get_menu_tree('main-menu') : "";

  $vars['top_menu'] = theme('links__menu_top_menu', array(
    'links' => menu_navigation_links('menu-top-menu'),
  ));

  $vars['footer_menu'] = theme('links__menu_footer_menu', array(
    'links' => menu_navigation_links('menu-footer-menu'),
  ));

  if ($node = menu_get_object()) {
    switch ($node->type) {
      case 'home':
    }
  }
}


/**
 * Implements hook_preprocess_node().
 */
function crescent_preprocess_node(&$vars) {
  $node = $vars['node'];
  switch ($node->type) {
    case "fblog_post":
      $category = (isset($node->field_fblog_category[LANGUAGE_NONE][0]['tid'])) ? $node->field_fblog_category[LANGUAGE_NONE][0]['tid'] : NULL;
      $blog_category = "";
      switch ($category) {
        case CRESCENT_BLOG_CATEGORY_SERVICES_TID:
          $blog_category = '<div class="blog-post-line blog-post-line-services">' . t("Crescent Services") . '</div>';
          break;
        case CRESCENT_BLOG_CATEGORY_AMERIFLOW_TID:
          $blog_category = '<div class="blog-post-line blog-post-line-ameriflow">' . t("AmeriFlow") . '</div>';
          break;
        case CRESCENT_BLOG_CATEGORY_SJB_TID:
          $blog_category = '<div class="blog-post-line blog-post-line-sjb">' . t("SJB Linings") . '</div>';
          break;
      }
      $vars['content']['blog_category'] = array(
        '#type' => 'markup',
        '#markup' => $blog_category,
      );
      break;
    case 'contact':
      $vars['title_prefix'] = array(
        '#type' => 'markup',
        '#markup' => '<span class="bg"></span>',
      );
      break;
    case 'enviroedge_items':
      if (isset($vars['content']['field_ee_items_gallery']) && !empty($vars['content']['field_ee_items_gallery'])) {
        $vars['add_class'] = 'text-slider';
      }
      break;
  }
}


/**
 * Implements hook_preprocess_block().
 */
function crescent_preprocess_block(&$vars) {
  //kpr($vars);
}


/**
 * Implements hook_preprocess_views_view().
 */
function crescent_preprocess_views_view(&$vars) {
  if (isset($vars['view']->name) && $vars['view']->name == "contact") {
    crescent_prepare_contact_map($vars);
  }
}


/**
 * Implements template_preprocess_taxonomy_term().
 */
function crescent_preprocess_taxonomy_term(&$vars) {
  if ($vars['vocabulary_machine_name'] == CRESCENT_CONTACT_VOC_NAME) {
    if ($vars['view_mode'] == "full") {
      drupal_goto("node/" . CRESCENT_CONTACT_NID);
    }
  }

}


/**
 * Prepare map for contact page.
 * @param $vars
 */
function crescent_prepare_contact_map(&$vars) {
  $results = isset($vars['view']->result) ? $vars['view']->result : array();
  $states = array();
  $term_id = NULL;
  $term = NULL;
  $term_color = NULL;
  $term_map_image = NULL;
  $term_icon = NULL;
  $term_map_icon = NULL;
  //kpr($results);
  foreach ($results as $key => $value) {
    $state_key = isset($value->field_field_location_state[0]['raw']['value']) ? $value->field_field_location_state[0]['raw']['value'] : NULL;
    $state_value = isset($value->field_field_location_state[0]['rendered']['#markup']) ? $value->field_field_location_state[0]['rendered']['#markup'] : NULL;
    $city_value = isset($value->field_field_location_city[0]['rendered']['#markup']) ? $value->field_field_location_city[0]['rendered']['#markup'] : NULL;
    $city_marker_left = isset($value->field_field_location_marker_left[0]['raw']['value']) ? $value->field_field_location_marker_left[0]['raw']['value'] : NULL;
    $city_marker_top = isset($value->field_field_location_marker_top[0]['raw']['value']) ? $value->field_field_location_marker_top[0]['raw']['value'] : NULL;


    $nid = isset($value->nid) ? $value->nid : NULL;
    $tid = isset($value->taxonomy_term_data_node_tid) ? $value->taxonomy_term_data_node_tid : NULL;
    if ($term_id != $tid) {
      $term_id = $tid;
      $term = taxonomy_term_load($term_id);
      $term_color = field_get_items('taxonomy_term', $term, 'field_locations_color');
      $term_bg_color = field_get_items('taxonomy_term', $term, 'field_locations_bg_color');
      $term_map_image = field_get_items('taxonomy_term', $term, 'field_locations_map_image');
      $term_icon = field_get_items('taxonomy_term', $term, 'field_locations_icon');
      $term_map_icon = field_get_items('taxonomy_term', $term, 'field_locations_map_icon');
    }

    if (!empty($state_key) && !empty($state_value) && !empty($city_value) && !empty($nid)) {
      $states[$state_key]['state_name'] = $state_value;
      $states[$state_key]['city'][] = array(
        'nid' => $nid,
        'name' => $city_value,
        'left' => $city_marker_left,
        'top' => $city_marker_top
      );
    }
  }

  $map_color = isset($term_color[0]['rgb']) ? $term_color[0]['rgb'] : NULL;
  $map_bg_color = isset($term_bg_color[0]['rgb']) ? $term_bg_color[0]['rgb'] : NULL;
  $map_image_uri = isset($term_map_image[0]['uri']) ? $term_map_image[0]['uri'] : NULL;
  $map_image = file_create_url($map_image_uri);
  $icon_uri = isset($term_icon[0]['uri']) ? $term_icon[0]['uri'] : NULL;
  $icon = file_create_url($icon_uri);
  $map_icon_uri = isset($term_map_icon[0]['uri']) ? $term_map_icon[0]['uri'] : NULL;
  $map_icon = file_create_url($map_icon_uri);

  $vars['view']->map = array(
    'map_color' => $map_color,
    'map_bg_color' => $map_bg_color,
    'map_image' => $map_image,
    'icon' => $icon,
    'map_icon' => $map_icon,
    'states' => $states
  );
}


/**
 * Theme function to output tablinks for classic Quicktabs style tabs.
 *
 * @ingroup themeable
 */
function crescent_qt_quicktabs_tabset($vars) {
  $variables = array(
    'attributes' => array(
      'class' => 'quicktabs-tabs quicktabs-style-' . $vars['tabset']['#options']['style'],
    ),
    'items' => array(),
  );
  $c = 1;

  foreach (element_children($vars['tabset']['tablinks']) as $key) {
    $item = array();
    if (is_array($vars['tabset']['tablinks'][$key])) {
      $tab = $vars['tabset']['tablinks'][$key];
      if ($key == $vars['tabset']['#options']['active']) {
        $item['class'] = array('active');
      }
      $item['class'][] = 'quicktabs-tabs-item-' . $c;
      $c++;
      $item['data'] = drupal_render($tab);
      $variables['items'][] = $item;
    }
  }
  return theme('item_list', $variables);
}

/**
 * Implements theme_qt_quicktabs().
 */
function crescent_qt_quicktabs($variables) {
  drupal_add_js(drupal_get_path('theme', 'crescent') . '/js/quicktabs_dlink.js');
  return theme_qt_quicktabs($variables);
}

/**
 * Implements hook_quicktabs_alter().
 */
function crescent_quicktabs_alter($info) {
  $param_name = isset($info->machine_name) ? $info->machine_name : '';
  $parametr = isset($_GET['qt']) ? $_GET['qt'] : '';
  if (is_numeric($parametr) && $param_name) {
    $_GET['qt-' . $param_name] = $parametr;
    unset($_GET['qt']);
  }
}

/**
 * Implements hook_form_alter().
 */
function crescent_form_alter(&$form, &$form_state, $form_id) {

  switch ($form_id) {
    case 'webform_client_form_' . CRESCENT_CAREERS_WEBFORM_NID:
      //$form['#attributes']['class'][] = '';
      //dsm($form);
      break;
  }
}

/**
 * Implement hook_preprocess_webform_element().
 */
function crescent_preprocess_webform_element(&$vars, $hook) {

  if($vars['element']['#type'] === 'date') {
    $childs = element_children($vars['element']);
    if(count($childs) === 3) {
      $vars['element']['#wrapper_attributes']['class'][] = 'three-elements';
    }

  }
}
