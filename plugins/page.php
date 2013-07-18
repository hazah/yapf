<?php

function page_title() {
  global $page_title;
  
  do_action('page_title');
  
  if (!empty($page_title)) {
    return $page_title;
  }
  
  return 'Default Title';
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

  theme('body', array(
    'plugin' => 'page',
    'content' => page_body(),
  ));
}


if (!function_exists('theme_alter_page')) {
  function theme_alter_page($type, $format, $content) {
    switch ($type) {
      case 'title':
        $content = array('head' => array('title' => $content));
        break;
      case 'body':
        $content = array('content' => $content);
        break;
    }
    return $content;
  }
}

if (!function_exists('render_alter_page_body')) {
  function render_alter_page_body($renderable) {
    return '<body>' . render($renderable) . '</body>';
  }
}

if (!function_exists('render_alter_page_head')) {
  function render_alter_page_head($renderable) {
    return '<head>' . render($renderable) . '</head>';
  }
}

if (!function_exists('render_alter_page_title')) {
  function render_alter_page_title($renderable) {
    return '<title>' . render($renderable) . '</title>';
  }
}

return array(
  'initialize' => 'page',
  'weight' => 10,
);
