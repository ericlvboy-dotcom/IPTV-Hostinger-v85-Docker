IPTV SMART MERGER - HOSTINGER SHARED HOSTING TEST PORT
====================================================
Source architecture: ericlvboy-dotcom/IPTV-Smart-Merger-MultiClient (Python/Flask).
This is a NEW PHP test implementation, NOT a full feature-equivalent conversion.
It does not import the original Python SQLite database or Python backup ZIP.

INSTALL
1. Upload all files/folders to public_html/multimerger/ in Hostinger File Manager.
2. Ensure PHP 8.1+ with cURL, JSON, DOM, zlib extensions; enable HTTPS.
3. Open https://YOURDOMAIN/multimerger/ and create admin with 12+ character password.
4. Add M3U sources and refresh. Add XMLTV URLs separately.
5. Set output username and password (12+ characters) in the Output access section.
6. Feed endpoints are shown in the dashboard. They pass through original provider stream URLs.
7. Cron (optional): use a Hostinger scheduled URL or PHP CLI command to invoke cron.php
   with the secret stored in storage/cron-key.txt. Do not publish this secret.

FEATURES IN THIS TEST BUILD
- Admin login and independent client accounts/workspaces.
- M3U source add/disable/delete, priorities, prefix, synchronous refresh.
- Conservative merge by exact URL or matching TVG ID + name; alternates retained in data.
- XMLTV multi-source merging, output credential protection, group rename rules.
- JSON backup and restore of this PHP version only.
- Responsive smoky dark/orange-outline dashboard, no stream proxy.

NOT YET PORTED / LIMITATIONS
- Original Python backup/SQLite migration, Xtream player_api.php endpoints.
- Original duplicate-review UI, manual merge rules, stream scrubber, fallback playback.
- Original twelve themes, activity logs, fine-grained shared sources, granular client group permissions.
- Original async source progress/polling, streaming large file parsing, robust scheduled refresh.
- Hostinger request execution time/memory can prevent large playlist or EPG refreshes.
- Cron URL secret must be kept private. Set schedule in Hostinger hPanel.
- Source downloads do not follow redirects for security; redirecting providers may fail.
- EPG is assembled on demand and may time out for large XMLTV feeds.
- Source credentials and generated data are stored in storage/data.json, protected by Apache .htaccess.
  Verify storage/ URLs are blocked before adding credentials. Never host on a server ignoring .htaccess.
- Use only playlists/streams you are authorized to access.

SECURITY: Passwords hashed; CSRF on forms; sessions; private/local IP sources blocked;
HTTP redirect disabled; storage directory access denied by Apache .htaccess.
This is a testing build and should be evaluated before production deployment.

SINGLE USER TEST UPDATE
- Client creation UI removed. Use only the administrator account.
- Global scrubber: Start / Stop / Check next batch, progress and approximate ETA.
- Scheduled M3U and EPG refresh intervals on the dashboard.
- Set a Hostinger cron job to invoke https://YOURDOMAIN/multimerger/cron.php?key=CONTENTS_OF_storage/cron-key.txt every five minutes. Keep the key private.
- Cron runs due refreshes and one scrubber batch. Without Hostinger cron, automatic jobs will not run.
- Scrubber uses lightweight HEAD checks; some providers reject HEAD even for playable streams. Flagged channels are NOT automatically removed.
- Large EPG and M3U feeds may exceed shared-hosting memory/time limits.
- This is a functional test port, not full feature parity with the Python project.


XTREAM CODES OUTPUT (v3)
------------------------
Set Output username and Output password in the dashboard (12+ characters).
Xtream server URL: https://yourdomain.com/multimerger
Username/password: the output credentials you configured.
API: /player_api.php?username=USER&password=PASS
M3U: /get.php?username=USER&password=PASS&type=m3u_plus&output=ts
EPG: /xmltv.php?username=USER&password=PASS
Live stream: /live/USER/PASS/1.ts (Apache mod_rewrite required).

Only live TV is implemented. VOD and series return empty lists.
Xtream live URLs redirect directly to provider streams; no stream proxy.
EPG must be generated from the dashboard or cron before XMLTV output is available.
This remains a Hostinger TEST port; validate with your IPTV player.

V4 OUTPUT COPY CONTROLS
The dashboard shows a complete M3U Plus URL, Xtream server, username, password, and XMLTV EPG URL, each with a Copy button. Set output credentials first. The output password is encrypted at rest with an AES-256-GCM key in storage/output.key. Preserve that key and storage/data.json when migrating; never publish or expose the storage folder. The app needs the PHP OpenSSL extension. Existing v3 installations must save their output password again once to enable display. HTTPS is strongly recommended because Xtream credentials are passed in URL query parameters.

V5 FIXES:
- Output password can be any non-empty length (admin password rules unchanged).
- Dashboard automatically advances scrubber in short AJAX batches while open.
- Admin-only API diagnostic checks channel count and output setup without leaking credentials.
- Xtream authentication failures return JSON auth=0 for better player compatibility.
- Do NOT overwrite the existing storage directory during upgrade.
- The player uses the OUTPUT username/password, not your administrator login.
- Shared hosting requires cron to continue scrubber when dashboard is closed.

V6 UPGRADE: On first request after uploading, existing administrator Xtream OUTPUT credentials are set to admin / admin once. This does NOT change the dashboard administrator login. Change the Xtream output password in the dashboard immediately after testing. Existing storage/data.json, sources and channels are preserved. Do not overwrite your storage folder.

V7 XTREAM COMPATIBILITY AND SCRUBBER:
- server_info.url now includes the installation directory (for example egrbvr.xyz/multi) to help players that build Xtream URLs from server_info. Some clients may not accept a path in this field; use the complete server URL https://egrbvr.xyz/multi in OTT Navigator.
- Additional common live stream metadata and a non-null expiry value.
- Browser scrub batches limited to 3 requests per tick, with short per-channel timeouts to avoid PHP request timeouts; progress advances while the dashboard remains open. Scrub checks can mark valid providers for review when HEAD is blocked; it does not remove streams.
- Verify /player_api.php?username=...&password=... (auth=1), then action=get_live_categories and action=get_live_streams. The latter should return objects with stream_id and category_id.
- This is not a verified OTT Navigator compatibility fix; test with OTT Navigator after deployment. If it still shows zero, inspect server access logs for which Xtream paths OTT requests.
- Keep the existing storage directory (including output.key). Do not replace it.

V8: Scrubber batch size can be set in Schedule & scrubber settings (3, 5, 10, 25, or 50; default 10). It affects manual steps, AJAX, and cron. Existing saved settings and storage are preserved.

V9 compatibility: mirrors original GitHub MultiClient master API server_info.url as full base URL, restores direct_source URL, and adds /stream/{id} passthrough route. The source implementation is Python/Flask; this is a PHP port, not a binary copy. Test with OTT Navigator after deployment.

v17 — XTREAM SOURCE IMPORT
- Sources > Add playlist > Xtream Codes API. Enter provider server URL, username and password.
- Source importer authenticates with player_api.php, loads live categories and requests streams per category, following the flow used by m3u-editor's XtreamService.
- Live channels only. VOD and series are not imported.
- Provider stream URLs remain direct; no streaming proxy is introduced.
- Xtream passwords are AES-256-GCM encrypted in storage/data.json using storage/output.key. Preserve both when backing up/migrating. Backups of data.json alone cannot decrypt source passwords without output.key.
- A failed source refresh retains previously published channels for that source. A failed first import has no channels to preserve.
- If provider API requires custom HTTP redirects or user-agent, additional compatibility may be necessary. Test with your own authorized provider.
- Xtream output routing from v9 is not modified.


v19: Xtream VOD and Series
Xtream sources now import movie and series catalogs in addition to live channels.
Series episodes are loaded on demand via get_series_info and redirected to the original provider.
Provider failures retain previously imported media catalogs.
Live channels, group rules and existing storage are preserved.
Importing large catalogs can exceed shared-hosting PHP execution limits; test with your provider.

V21 RESUMABLE SOURCE REFRESH
- The Playlists refresh button starts a persistent refresh job instead of waiting for all Xtream categories in one PHP request.
- Browser polls refresh_ajax.php every 2.5 seconds; each request processes at most one provider category.
- Use the refresh mode dropdown for Live, Movies, Series, or All. A source-row Refresh button limits the job to that source.
- Existing published channels and media stay available until the corresponding source/content import completes.
- If any category fails, that source/content type keeps its prior catalog; errors are displayed in progress.
- The current job is stored in storage/refresh-job.json; it must remain inaccessible over HTTP.
- If you close the browser, processing resumes when the dashboard is reopened. To keep it running with no browser open, configure Hostinger cron.php with its secret key and an appropriate frequency.
- Progress is category-based, not a precise estimate of total item transfer. Each provider request can still take up to 40 seconds, with retries.
- Large catalogs still consume PHP memory and storage; shared-hosting limits apply.
- Keep the existing storage directory when uploading; do not replace it with the package storage directory.


v23 publishing policy: Live TV imports go to the input catalog only. Player-facing Live TV uses published_channels, replaced only after the entire scrub finishes and its input fingerprint matches. Only HEAD-confirmed 'ok' channels are published; unknown/failed channels are excluded. Partial, stopped or failed scrub jobs never change the output. Movies and series are published directly without scrubbing. A fresh installation without a completed scrub has no player-facing Live TV until its first completed scrub. Existing installations similarly need one complete scrub to establish a published snapshot.

V50: Series details use numeric episode IDs compatible with Xtream players; server-side ID mapping stored in storage/episode-id-map.json. Keep storage when upgrading.
