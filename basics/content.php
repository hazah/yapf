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
    do_action('get_content');
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
  do_action('content_alter');

  return isset($content[++$post_index]);
}

function &post_cache() {
  static $cache = array();

  return $cache;
}

/**
 * Returns the content at the current processing poing if there is any.
 */
function get_content() {
  global $content, $post, $post_index;
  $cache = &post_cache();

  $post = $content[$post_index];

  // Make sure to alter only once per post.
  if (!isset($cache[$post_index]) || !$cache[$post_index]) {
    do_action('content_post_alter');
    $cache[$post_index] = true;
  }
}

function get_content_index() {
  global $post_index;

  return $post_index;
}

/**
 * Reset the current content position for processing.
 */
function reset_content() {
  global $content, $post, $post_index;
  $cache = &post_cache();

  $post_index = -1;
  $post = null;

  do_action('reset_content');
  $cache = array();
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

  $title = $post->title;
  do_action('post_title_alter', array(&$title));

  return $title;
}

function content_body() {
  global $post;

  $body = $post->body;
  do_action('post_body_alter', array(&$body));

  return $body;
}


// Plugin info:
// TODO: Include human readable information so that some other plugin can make
// use of that information.
return array(
  'initialize' => function () {

    add_action('set_content_handler', function () {
      global $content_handler;
      $content_handler->content = function () {

        // The content loop.
        while (more_content()) {
          get_content();

          output(array(
            'plugin' => 'content',
            'type' => 'title',
            'content' => content_title(),
            'index' => get_content_index(),
          ));
          output(array(
            'plugin' => 'content',
            'type' => 'body',
            'content' => content_body(),
            'format' => content_type(),
            'index' => get_content_index(),
          ));
        }

        do_action('set_page_title', content_title());
      };
    });

  },
  'requires' => array('actions', 'output', 'render'),
);
