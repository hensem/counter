# Changelog

## [2026-09-29]

### Added
- Store visitor IP and country code in `visitor_logs` table on each unique visit
- Country lookup via `ip-api.com` (no API key required, silent on failure)

## [2026-09-26 06:52]

### Changed
- Improved README: fenced code block for `COUNTER_ALLOWED_DOMAINS` example

## [2026-09-26 06:47]

### Changed
- Moved allowed domains list to `config/config_counter.php` as `COUNTER_ALLOWED_DOMAINS` constant

## [2026-09-26 06:41]

### Added
- README.md
