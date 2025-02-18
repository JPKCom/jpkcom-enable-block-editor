<?php
/*
Plugin Name: JPKCom Enable Block Editor
Plugin URI: https://www.jpkc.com/
Description: Enables the Block Editor for Admin (User ID:1).
Version: 1.0.2
Author: Jean Pierre Kolb <jpk@jpkc.com>
Author URI: https://www.jpkc.com
Requires Plugins: blockstudio
Requires at least: 6.7
Requires PHP: 8.3
License: MIT
License URI: https://opensource.org/license/MIT
GitHub Plugin URI: JPKCom/jpkcom-enable-block-editor
Primary Branch: main
*/

add_filter( 'blockstudio/settings/users/ids', function(): array {
  return [1];
});
