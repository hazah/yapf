<?php

// Plugin info:
// TODO: Include human readable information so that some other plugin can make
// use of that information.
return array(
  'initialize' => function () {
    do_action('set_content_handler');

    do_action('set_active_handler');

    do_action('run_active_handler');
  },
);
