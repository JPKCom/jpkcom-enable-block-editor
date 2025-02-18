<?php
/*
Plugin Name: JPKCom Enable Block Editor
Description: Enables the Block Editor for Admin (User ID:1).
Version: 1.0.1
Author: Jean Pierre Kolb <jpk@jpkc.com>
Author URI: https://www.jpkc.com
Requires Plugins: blockstudio
GitHub Plugin URI: JPKCom/jpkcom-enable-block-editor
Primary Branch: main
*/

add_filter( 'blockstudio/settings/users/ids', function(): array {
  return [1];
});
