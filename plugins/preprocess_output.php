<?php
/**
 * @file
 *
 * This plugin helps with preprocessing of renderable arrays before output.
 */

/**
 * Plugin initializer.
 */
function preprocess_output() {
  global $output;

  // All we do is call this hook, and this will allow all other plugins to
  // preprocess the array.
  do_action('preprocess_output');
}

// Plugin info:
// TODO: Include human readable information so that some other plugin can make
// use of that information.
return array(
  'initialize' => 'preprocess_output',
  // Set the weight higher than the page plugin so that the initializer runs
  // after it.
  'weight' => 20,
  'requires' => array('actions', 'output'),
);
