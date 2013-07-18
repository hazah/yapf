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
    theme('title', array(
      'plugin' => 'content',
      'content' => content_title(),
    ));
    theme('body', array(
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


if (!function_exists('theme_alter_content')) {
  function theme_alter_content($type, $format, $content) {
    switch ($type) {
      case 'title':
        $content = '<h1>' . $content . '</h1>';
        break;
      case 'body':
        $content = '<div id="body">' . $content . '</div>';
        break;
    }
    return $content;
  }
}

return array(
  'initialize' => 'content',
);
