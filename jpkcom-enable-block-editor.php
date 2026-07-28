<?php
/*
Plugin Name: JPKCom Enable Block Editor
Plugin URI: https://github.com/JPKCom/jpkcom-enable-block-editor
Description: Enables the Block Editor for Admin (User ID:1).
Version: 2.0.6
Author: Jean Pierre Kolb <jpk@jpkc.com>
Author URI: https://www.jpkc.com
Contributors: JPKCom
Tags: Block, Blocks, Editor, Security, Gutenberg
Requires Plugins: blockstudio
Requires at least: 6.9
Tested up to: 7.0
Requires PHP: 8.3
Stable tag: 2.0.6
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

declare(strict_types=1);

if ( ! defined( constant_name: 'WPINC' ) ) {
	die;
}


/**
 * Plugin Constants
 *
 * @since 2.0.3
 */
if ( ! defined( 'JPKCOM_ENABLE_BLOCK_EDITOR_VERSION' ) ) {
    define( 'JPKCOM_ENABLE_BLOCK_EDITOR_VERSION', '2.0.6' );
}


/**
 * Initialize Plugin Updater
 *
 * Loads and initializes the GitHub-based plugin updater with SHA256 checksum verification.
 *
 * @since 2.0.3
 *
 * @return void
 */
add_action( 'init', static function (): void {
    $updater_file = plugin_dir_path( __FILE__ ) . 'includes/class-plugin-updater.php';

    if ( file_exists( $updater_file ) ) {
        require_once $updater_file;

        if ( class_exists( 'JPKComEnableBlockEditorGitUpdate\\JPKComGitPluginUpdater' ) ) {
            new \JPKComEnableBlockEditorGitUpdate\JPKComGitPluginUpdater(
                plugin_file: __FILE__,
                current_version: JPKCOM_ENABLE_BLOCK_EDITOR_VERSION,
                manifest_url: 'https://jpkcom.github.io/jpkcom-enable-block-editor/plugin_jpkcom-enable-block-editor.json'
            );
        }
    }
}, 5 );

/**
 * Restrict Blockstudio's block editor to the main administrator (user ID 1).
 *
 * @since 1.0.0
 *
 * @return int[] User IDs allowed to use the Blockstudio editor.
 */
add_filter( 'blockstudio/settings/users/ids', static function (): array {
  return [1];
});
