# JPKCom Enable Block Editor – Developer Reference

## Plugin Overview

Restricts the Blockstudio block editor to the main administrator (user ID 1) by filtering `blockstudio/settings/users/ids` to `[1]`.

- **Text Domain:** `jpkcom-enable-block-editor` (no header declared, defaults to slug; only used by the shared updater)
- **Requires Plugins:** `blockstudio`
- **Min PHP:** 8.3 | **Min WP:** 6.9
- **Network:** not network-only (no `Network:` header)

---

## Architecture

```
Main file (jpkcom-enable-block-editor.php)
├── declare(strict_types=1)
├── Plugin header (Requires Plugins: blockstudio)
├── JPKCOM_ENABLE_BLOCK_EDITOR_VERSION constant
├── init @ priority 5: boot JPKComGitPluginUpdater
└── add_filter blockstudio/settings/users/ids → static fn(): array => [1]
```

---

## Behaviour

| Hook | Type | Effect |
|------|------|--------|
| `blockstudio/settings/users/ids` | filter | Returns `[1]` — only user ID 1 may use the Blockstudio editor |

---

## Constants

| Constant | Value | Purpose |
|----------|-------|---------|
| `JPKCOM_ENABLE_BLOCK_EDITOR_VERSION` | `'2.0.3'` | Plugin version (sync with header/README/phpdoc.xml) |

---

## File Structure

```
jpkcom-enable-block-editor/
├── jpkcom-enable-block-editor.php ← Main: header, constant, filter, updater bootstrap
├── includes/
│   └── class-plugin-updater.php  ← GitHub auto-updater (namespace: JPKComEnableBlockEditorGitUpdate)
├── .github/workflows/release.yml ← Build ZIP, manifest, PHPDoc, deploy to gh-pages (on tag push)
├── phpdoc.xml                    ← phpDocumentor config
├── README.md                     ← Public readme (source for the WP plugin modal)
├── CLAUDE.md                     ← This file
├── LICENSE                       ← GPL-2.0-or-later
└── .gitignore
```

---

## Plugin Updater

- **Namespace:** `JPKComEnableBlockEditorGitUpdate\JPKComGitPluginUpdater`
- **Manifest URL:** `https://jpkcom.github.io/jpkcom-enable-block-editor/plugin_jpkcom-enable-block-editor.json`
- Shared JPKCom updater (downstream copy of upstream `jpkcom-post-filter`; do not edit per-plugin). SHA256 verification, `wp_safe_remote_get()`, URL validation, race-condition lock, 24 h cache, timing-safe `hash_equals()`.
- Hooks: `plugins_api`, `site_transient_update_plugins`, `upgrader_process_complete`, `upgrader_pre_download`.

---

## Release Workflow

Triggered by **pushing a `v*` tag**; the workflow creates the GitHub release automatically. Pipeline: setup PHP/Python/Pandoc/GraphViz → README metadata → slug-named ZIP → SHA256 → upload ZIP + `.sha256` → `plugin_<slug>.json` manifest → PHPDoc → deploy to `gh-pages`.

---

## Security Checklist

- `declare(strict_types=1)` in every PHP file
- Editor access limited to user ID 1 via the Blockstudio filter
- Updater: SHA256 verification + URL validation (audited separately)

---

## Release Checklist

1. Bump version in: header `Version:` + `Stable tag:`, `JPKCOM_ENABLE_BLOCK_EDITOR_VERSION`, `README.md`, `phpdoc.xml`
2. Add a `### x.y.z` block to `## Changelog` in `README.md`
3. Commit, tag `vx.y.z`, push the tag → the workflow builds and publishes everything
