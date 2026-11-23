v62 EPG player delivery
Compared with GitHub IPTV-Smart-Merger-MultiClient epg_engine.py and app.py.
- M3U output advertises authenticated XMLTV URL with url-tvg and x-tvg-url.
- Xtream API get_short_epg and get_simple_data_table (plus date typo alias) now read published merged XMLTV and return programme listings instead of empty arrays.
- Existing XMLTV endpoint and EPG refresh are unchanged.
- Does not alter sources, storage, stream playback or background refresh.
- Xtream EPG responses require a completed published EPG and matching tvg-id.
Upload over /multi/ preserving storage/.
