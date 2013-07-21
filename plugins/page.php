<?php
/*
    yapf: Yet Another PHP Framework
    Copyright (C) 2013 Ivgeni Slabkovski

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program. If not, see {http://www.gnu.org/licenses/}.
*/

/**
 * @file
 *
 * This plugin helps with creation of a complete page renderable array for output.
 */

/**
 * Plugin output functions.
 *
 * Delegate the actual content to other plugins using the actions plugin. If none
 * respond, reflect to the browser that this plugin is still perfectly functional.
 */
function page_title() {
  global $page_title;

  if (empty($page_title)) {
    do_action('page_title');
  }
  do_action('page_title_alter');

  if (!empty($page_title)) {
    return $page_title;
  }

  return 'Page Title Not Set';
}

function page_head() {
  global $output;
  global $page_head;

  if (empty($page_head)) {
    do_action('page_head');
  }
  do_action('page_head_alter');

  if (!empty($page_head)) {
    return $page_head;
  }

  return '<head><title>Head content not set</title></head>';
}

function page_body() {
  global $output;
  global $page_body;

  if (empty($page_body)) {
    do_action('page_body');
  }
  do_action('page_body_alter');

  if (!empty($page_body)) {
    return $page_body;
  }

  return 'The page plugin is functional, but it does not have any content to display';
}

/**
 * Plugin initializer.
 */
function page() {
  global $output;

  // Add the action first, because page_head() is called right below.
  add_action('page_head', function () {
    global $page_head;
    global $output;

    // Only set the header here if no other plugin had done so.
    if (empty($page_head) && isset($output['page']['title'])) {
      $page_head = array('title' => $output['page']['title']);

      unset($output['page']['title']);
    }
  // Increase the weight to delay this callback so that other plugins will have
  // a chance to set the head content
  }, 10);

  // Populate the renderable array
  output('title', array(
    'plugin' => 'page',
    'content' => page_title(),
  ));
  do_action('page_title_output_alter', array(&$output['page']['title']));

  output('head', array(
    'plugin' => 'page',
    'content' => page_head(),
  ));
  do_action('page_head_output_alter', array(&$output['page']['head']));

  output('body', array(
    'plugin' => 'page',
    'content' => page_body(),
  ));
  do_action('page_body_output_alter', array(&$output['page']['body']));
}

/** TODO: This is only for documentating the API of this plugin.
if (!function_exists('output_alter_page')) {
  function output_alter_page($type, $format, $content) {
    switch ($type) {
      case 'title':
        break;
      case 'body':
        break;
    }
    return $content;
  }
}
*/

// Overridable rendering callbacks.

/**
 * Implements render_alter_PLUGIN_HOOK().
 *
 * These only exist to make sure the rendered HTML is valid and to serve as an
 * example.
 *
 * @see render API plugin
 */
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


// Plugin info:
// TODO: Include human readable information so that some other plugin can make
//       use of it.
return array(
  'initialize' => function () {
    add_action('set_page_title', function ($title) {
      global $page_title;

      $page_title = $title;
      do_action('page_title_alter');
    });

    add_action('set_page_content', function ($content) {
      global $page_content;

      $page_content = $content;
      do_action('page_content_alter');
    });

    add_action('add_page_js', function ($js) {
      global $page_js;

      $page_js[] = $js;
    });

    add_action('add_page_css', function ($css) {
      global $page_css;

      $page_css[] = $css;
    });

  },
  'requires' => array('actions', 'output', 'render'),
);
