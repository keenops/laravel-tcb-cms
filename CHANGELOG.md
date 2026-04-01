# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.2] - 2026-04-01

### Changed

- Simplified `cancelReference()` to only require `referenceNo` parameter
- The `accountNo` parameter is now automatically injected from config `profile_id`

## [1.0.1] - 2026-03-31

### Changed

- Expanded PHP version support from `^8.4` to `^8.2` for broader compatibility
- Added Laravel 11 support alongside Laravel 12 and 13
- Updated `illuminate/*` packages to support `^11.0|^12.0|^13.0`
- Updated dev dependencies for wider version compatibility:
  - `orchestra/testbench`: `^9.0|^10.0`
  - `pestphp/pest`: `^2.0|^3.0|^4.0`
  - `pestphp/pest-plugin-laravel`: `^2.0|^3.0|^4.0`

## [1.0.0] - 2026-03-25

### Added

- Initial release
- Payment reference creation via `TcbCms::createReference()`
- Payment reference cancellation via `TcbCms::cancelReference()`
- Reconciliation support via `TcbCms::reconcile()`
- IPN (Instant Payment Notification) handling with automatic route registration
- Event dispatching for `PaymentReceived`, `ReferenceCreated`, `ReferenceCancelled`, `ReconciliationCompleted`
- Transaction logging to `tcb_cms_transactions` table
- IP verification for IPN callbacks
- Payment channel helper with instructions for TCB Mobile, Branch, ATM, USSD, Internet Banking, Agent Banking, and PesaLink
- Comprehensive exception handling with `TcbCmsException`, `ApiConnectionException`, `InvalidApiKeyException`, `InvalidReferenceException`
- Response status enum with `Success`, `Failure`, `ConnectionError`, `ApiKeyError`
