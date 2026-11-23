v63 DIRECT EPG PASSTHROUGH — NO EPG PROCESSING
- XMLTV URL redirects directly to the first enabled Xtream EPG, or first enabled EPG source if no Xtream EPG is enabled.
- Xtream get_short_epg/get_simple_data_table forwards the provider request using the ORIGINAL provider live stream ID.
- No local guide file is required. No EPG refresh, cron, parsing, merging or background job required for delivery.
- M3U advertises the same passthrough EPG URL.
IMPORTANT: one XMLTV URL can redirect to ONE provider only. It cannot represent 57 independent feeds without merging. For other guides, add their URLs in the IPTV player directly.
- If the provider blocks direct downloads or cross-domain redirects, the player may not load the guide.
- Upload over /multi/ preserving storage/. No changes to stored sources.
