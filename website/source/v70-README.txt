v70 SMART EPG REFRESH
- Cached guide served immediately on XMLTV player requests.
- Post-response worker checks age (12 hours) and measured upcoming 24-hour programme coverage (70%).
- HTTP conditional GET sends ETag/Last-Modified validators for previously cached source fragments; 304 reuses fragments.
- Last success, coverage and refresh reason available in epg_delivery_status.php JSON.
- Work advances one source per player request on PHP-FPM; no guaranteed completion without subsequent requests.
- No cron, no stream proxy. Preserve existing storage/ directory.
