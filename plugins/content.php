<?php
/**
 * @file
 *
 * This plugin helps with creation of the main page content renderable array for
 * output.
 */

/**
 * Plugin iteration functions.
 *
 * Delegate the actual content to other plugins using the actions plugin. If none
 * respond, reflect to the browser that this plugin is still perfectly functional.
 */

/**
 * Determines if there is any more content to process.
 */
function more_content() {
  global $content, $post_index;

  if (empty($content)) {
    do_action('content');
    if (empty($content)) {
      $content = array(
        // This is the basic content object used by this plugin.
        (object) array(
          'title' => 'No Content available',
          'body' => 'The content plugin is functional, but it does not have any content to display',
        )
      );
    }
    $post_index = -1;
  }

  return isset($content[++$post_index]);
}

/**
 * Returns the content at the current processing poing if there is any.
 */
function get_content() {
  global $content, $post, $post_index;

  $post = $content[$post_index];
}

/**
 * Reset the current content position for processing.
 */
function reset_content() {
  global $post, $post_index;

  $post_index = -1;
  $post = null;
}

/**
 * Plugin output functions.
 *
 * Parse the content into data structures to use for rendering.
 */
function content_type() {
  global $post;

  return isset($post->type) ? $post->type : 'default';
}

function content_title() {
  global $post;

  return $post->title;
}

function content_body() {
  global $post;

  return $post->body;
}

/**
 * Plugin initializer.
 */
function content() {
  // The content loop.
  while (more_content()) {
    get_content();
    output('title', array(
      'plugin' => 'content',
      'content' => content_title(),
    ));
    output('body', array(
      'plugin' => 'content',
      'content' => content_body(),
      'format' => content_type(),
    ));
  }
  do_action('content_title_alter', array(&$output['content']['title']));
  do_action('content_body_alter', array(&$output['content']['body']));

  // Supply the title & to any page plugin
  add_action('page_title', function () {
    global $page_title;

    // FIXME: This is asumming only one result, needs something for dynamic lists.
    $page_title = content_title();
  });

  add_action('page_body', function () {
    global $page_body;
    global $output;

    // FIXME: This is asumming only one result, needs something for dynamic lists.
    $page_body = array('content' => $output['content']);

    unset($output['content']);
  });
}

/** TODO: This is only for documentating the API of this plugin.
if (!function_exists('output_alter_content')) {
  function output_alter_content($type, $format, $content) {
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

/**
 * Implements render_alter_PLUGIN_HOOK().
 *
 * @see render API plugin
 */
if (!function_exists('render_alter_content_title')) {
  function render_alter_content_title($renderable) {
    return '<h1>' . render($renderable, 'content') . '</h1>';
  }
}

if (!function_exists('render_alter_content_body')) {
  function render_alter_content_body($renderable) {
    return '<div class="content">' . render($renderable, 'content') . '</div>';
  }
}

// Plugin info:
// TODO: Include human readable information so that some other plugin can make
// use of that information.
return array(
  'initialize' => 'content',
  'requires' => array('actions', 'output', 'render'),
);
