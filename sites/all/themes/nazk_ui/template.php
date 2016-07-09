<?php
/**
 * @file
 * The primary PHP file for this theme.
 */

function nazk_ui_preprocess_page(&$vars) {
    // - page--example.tpl.php  

    if (isset($vars['node'])) {
        $vars['theme_hook_suggestion'] = 'page__'.$vars['node']->type; //
    }
    
    global $language;
    $lang = $language->language;

    if ($lang == 'en') {
        $vars['logo'] = '/sites/default/files/NAZK_logo_en.png';
    }
    
    if ($lang == 'uk') {
        $vars['logo'] = '/sites/default/files/NAZK_logo.png';
    }
}

function nazk_ui_preprocess_node(&$vars) {
  if (variable_get('node_submitted_' . $vars['node']->type, TRUE)) {
    $date = format_date($vars['node']->created, 'date_type');
    $vars['submitted'] = t('Submitted by !username on !datetime', array('!username' => $vars['name'], '!datetime' => $date));
  }
}