<?php

function more_content() {
  global $content, $post_index;

  if (empty($content)) {
    if (file_exists('./site/content.php')) {
      $content = require_once './site/content.php';
    }
    else {
      $content = array(
        (object) array(
          'title' => 'Content',
          'body' => 'No Content',
        )
      );
    }
    $post_index = -1;
  }

  return isset($content[++$post_index]);
}

function get_content() {
  global $content, $post, $post_index;

  $post = $content[$post_index];
}

function reset_content() {
  global $post, $post_index;

  $post_index = -1;
  $post = null;
}

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

function content() {
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
  
  add_action('page_title', function () {
    global $page_title;
    
    $page_title = content_title();
  });
}

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

return array(
  'initialize' => 'content',
  'requires' => array('actions'),
);
