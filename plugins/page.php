<?php

function page_title() {
  global $page_title;
  
  do_action('page_title');
  
  if (!empty($page_title)) {
    return $page_title;
  }
  
  return 'Default Title';
}

function page_head() {
  global $theme;
  //print_r($theme['page']['title']); die;
  $content = array('title' => $theme['page']['title']);
  unset($theme['page']['title']);
  
  return $content;
}

function page_body() {
  global $theme;
  global $page_body;
  
  do_action('page_body');
  
  if (!empty($page_body)) {
    return $page_body;
  }
  elseif (array_key_exists('content', $theme)) {
    $content = $theme['content'];
    unset($theme['content']);
    return $content;
  }
  
  return 'No page content';
}


function page() {
  theme('title', array(
    'plugin' => 'page',
    'content' => page_title(),
  ));
  
  theme('head', array(
    'plugin' => 'page',
    'content' => page_head(),
  ));

  theme('body', array(
    'plugin' => 'page',
    'content' => page_body(),
  ));
}


if (!function_exists('theme_alter_page')) {
  function theme_alter_page($type, $format, $content) {
    switch ($type) {
      case 'title':
        break;
      case 'body':
        // Retain the plugin information so that the content plugin can take
        // advantage of it (if it exists).
        $content = array('content' => $content);
        break;
    }
    return $content;
  }
}

if (!function_exists('render_alter_page_title')) {
  function render_alter_page_title($renderable) {
    return '<title>' . render($renderable, 'page') . '</title>';
  }
}

if (!function_exists('render_alter_page_body')) {
  function render_alter_page_body($renderable) {
    return '<body>' . render($renderable, 'page') . '</body>';
  }
}

if (!function_exists('render_alter_page_head')) {
  function render_alter_page_head($renderable) {
    return '<head>' . render($renderable, 'page') . '</head>';
  }
}

return array(
  'initialize' => 'page',
  'weight' => 10,
);
