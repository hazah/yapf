<?php

/** YAPF Begins here */
if ($handle = opendir('./plugins')) {
  while (false !== ($entry = readdir($handle))) {
    if ($entry != "." && $entry != "..") {
      // Each plugin file could return plugin information. If so, record it.
      $info = require_once './plugins/' . $entry;
      if ($info !== false) {
        // Set defaults
        $info += array(
          'weight' => 0,
          'name' => str_replace('.php', '', $entry),
        );
        $plugins[$info['weight']] = $info;
      }
    }
  }
  closedir($handle);
  unset($handle);
  unset($entry);
  
  // We use a weight to sort the plugins, then initialize ones that require it.
  ksort($plugins);
  foreach ($plugins as $info) {
    if (array_key_exists('initialize', $info) && function_exists($info['initialize'])) {
      $info['initialize']();
    }
  }
  unset($info);
}

if (!isset($theme)) {
  $page_title = 'Failure';
  $page_content = '$theme variable was not set!';
}
/** End of YAPF */
print_r($theme);
// Below is the default output of the framework
?>
<html>
  <?= render($theme) ?>
</html>
