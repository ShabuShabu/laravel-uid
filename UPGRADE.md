# Upgrade Guide

## Upgrading To 0.6.0 From 0.7.0

### Changes

- Any uid-enabled model must now implement the `\ShabuShabu\Uid\Contracts\Identifiable` interface
- Morph map handling can now be enabled in the config file

## Upgrading To 0.5.0 From 0.6.0

### Changes

- The static `\ShabuShabu\Uid\Service\Uid::alias()` method was made non-static, so either change your usage to `\ShabuShabu\Uid\Service\Uid::make()->alias()` or use the new facade `\ShabuShabu\Uid\Facades\Uid::alias()`
- The `resolve_model` helper function is now in the global namespace 
