v75 - AJAX/session and EPG publishing reliability
- JSON endpoints return HTTP 401 JSON for expired sessions rather than redirecting to HTML.
- EPG builder uses current CSRF token (not an embedded stale token).
- Browser reports server HTML errors explicitly.
- Empty EPG sources no longer replace existing good source fragments.
- Combined guide compresses the staged XML before replacing the published gzip.
- Legacy EPG finish publishes to the selected workspace.
- No storage directory included. Preserve your existing storage directory.
- Live Hostinger behavior cannot be tested locally.
