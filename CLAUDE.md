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
| `JPKCOM_ENABLE_BLOCK_EDITOR_VERSION` | matches the header `Version:` | Plugin version (sync with header/README/phpdoc.xml) |

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
- Shared JPKCom updater (downstream copy of upstream `jpkcom-post-filter`; do not edit per-plugin). SHA256 verification, `wp_safe_remote_get()`, URL validation, race-condition lock, 24 h cache, timing-safe `hash_equals()`. Checksum verification is **mandatory**: a missing or unfetchable `checksum_sha256` aborts the update instead of installing unverified code. The verified temp file is returned from `upgrader_pre_download`, so WordPress installs exactly the bytes that were hashed (no second download). Failed manifest fetches are negatively cached for 1 h.
- Hooks: `plugins_api`, `site_transient_update_plugins`, `upgrader_process_complete`, `upgrader_pre_download`.

---

## Release Workflow

**Actions are pinned to commit SHAs.** Every `uses:` line in `.github/workflows/` references a 40-character commit SHA instead of a tag (`@v4`), with the version as a trailing comment. A tag is a movable pointer and can be repointed; a SHA cannot. Since the release workflow builds the plugin ZIP **and** the SHA256 checksum the auto-updater trusts, a compromised action would ship a tampered ZIP together with a matching checksum — the checksum secures the transport, the pinning secures the build. `.github/dependabot.yml` keeps the pins current weekly in one combined PR; when updating, always change the SHA *and* the version comment together.

**CI** (`.github/workflows/ci.yml`) runs on every pull request *and* on every push to `main` — a required status check only covers pull requests, so a direct push with bypass rights would otherwise skip the checks entirely. It runs `php -l` over all PHP files; flags invalid named arguments to internal PHP functions (catches `sprintf(format:, values:)` → `ArgumentCountError`, which `php -l` does not see); validates the YAML of every `.github` file; asserts every action is pinned to a 40-character commit SHA; and executes `tests/test-*.php` where present.

**Dependabot auto-merge** (`.github/workflows/dependabot-auto-merge.yml`) merges only `semver-patch` and `semver-minor`, and only PRs from `dependabot[bot]` in this repo — never from forks. Major updates get a comment and stay manual. Two repo settings are prerequisites, otherwise this is useless or outright dangerous: "Allow auto-merge" must be enabled, and branch protection must list `CI / Lint & Guards` as a **required status check** — without it `gh pr merge --auto` merges *immediately*, since there is nothing left to wait for. Together with `cooldown: default-days: 7` no action release is adopted during its first week.

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
