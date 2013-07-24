<?php

// Plugin info:
// TODO: Include human readable information so that some other plugin can make
// use of that information.
return array(
  'initialize' => function () {
    add_action('set_active_handler', function () {
      global $active_handler;

      $active_handler = 'content';
    });

    add_action('run_active_handler', function () {
      global $content_handler, $active_handler;

      $content_handler->{$active_handler}();
    });
  },
);
