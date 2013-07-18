<?php
/**
 * @file
 *
 * This plugin helps with creation of a complete page renderable array for output.
 */

function page_title() {
  global $page_title;

  do_action('page_title');

  if (!empty($page_title)) {
    return $page_title;
  }

  return 'Page Title Not Set';
}

function page_head() {
  global $output;
  global $page_head;

  do_action('page_head');

  if (!empty($page_head)) {
    return $page_head;
  }

  return $content;
}

function page_body() {
  global $output;
  global $page_body;

  do_action('page_body');

  if (!empty($page_body)) {
    return $page_body;
  }

  return 'The page plugin is functional, but it does not have any content to display';
}


function page() {
  add_action('page_head', function () {
    global $page_head;
    global $output;

    $page_head = array('title' => $output['page']['title']);

    unset($output['page']['title']);
  });

  output('title', array(
    'plugin' => 'page',
    'content' => page_title(),
  ));

  output('head', array(
    'plugin' => 'page',
    'content' => page_head(),
  ));

  output('body', array(
    'plugin' => 'page',
    'content' => page_body(),
  ));
}


if (!function_exists('output_alter_page')) {
  function output_alter_page($type, $format, $content) {
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
  'requires' => array('actions', 'output', 'render'),
);
