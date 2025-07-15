# Upgrade Guide

## Upgrading To 0.5.0 From 0.6.0

### Changes

- The static `\ShabuShabu\Uid\Service\Uid::alias()` method was made non-static, so either change your usage to `\ShabuShabu\Uid\Service\Uid::make()->alias()` or use the new facade `\ShabuShabu\Uid\Facades\Uid::alias()`
- The `resolve_model` helper function is now in the global namespace 
