<?php
/*
Plugin Name: JPKCom Enable Block Editor
Plugin URI: https://github.com/JPKCom/jpkcom-enable-block-editor
Description: Enables the Block Editor for Admin (User ID:1).
Version: 2.0.1
Author: Jean Pierre Kolb <jpk@jpkc.com>
Author URI: https://www.jpkc.com
Contributors: JPKCom
Tags: Block, Blocks, Editor, Security, Gutenberg
Requires Plugins: blockstudio
Requires at least: 6.7
Tested up to: 6.7
Requires PHP: 8.3
Stable tag: 2.0.1
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
GitHub Plugin URI: JPKCom/jpkcom-enable-block-editor
Primary Branch: main
*/

if ( ! defined( constant_name: 'WPINC' ) ) {
	die;
}

add_filter( 'blockstudio/settings/users/ids', function(): array {
  return [1];
});
