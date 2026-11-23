"""Build v85 XMLTV using the unchanged MultiClient engine from cached source fragments.

Only writes separate Python-owned files; never modifies PHP settings or caches.
"""
import hashlib
import json
import os
import time
from pathlib import Path
from xml.etree import ElementTree as ET

from epg_engine import build_merged_xmltv

STORE = Path(os.environ.get("IPTV_STORAGE", "/storage"))
INTERVAL = int(os.environ.get("EPG_INTERVAL_SECONDS", "300"))


def build_workspace(workspace_id, workspace):
    enabled = [s for s in workspace.get("epg", []) if s.get("enabled") and s.get("id") is not None]
    if not enabled:
        return
    sources = []
    for source in enabled:
        source_id = str(source["id"])
        path = STORE / ("epg-cache-" + hashlib.sha256(source_id.encode()).hexdigest() + ".xml")
        if path.is_file() and path.stat().st_size > 50:
            sources.append({"id": source_id, "path": str(path)})
    if not sources:
        return
    key = hashlib.sha256(workspace_id.encode()).hexdigest()
    target = str(STORE / ("epg-multiclient-" + key + ".xml"))
    staged = target + ".candidate"
    def source_path(source_id):
        return next(s["path"] for s in sources if s["id"] == source_id)
    try:
        build_merged_xmltv(sources, set(), False, staged, lambda *args: None, source_path, workspace_id)
        # Validate actual programme/channel counts before offering any file to PHP.
        channels = programmes = 0
        with open(staged, "rb") as stream:
            for _, elem in ET.iterparse(stream, events=("end",)):
                if elem.tag == "channel":
                    channels += 1
                elif elem.tag == "programme":
                    programmes += 1
                elem.clear()
        if not channels or not programmes:
            raise ValueError("Merged XMLTV lacks channels or programmes")
        # The engine publishes staged and staged.gz atomically; copy to final
        # names only after validation. PHP sees metadata written last.
        os.replace(staged, target)
        os.replace(staged + ".gz", target + ".gz")
        size = os.path.getsize(target + ".gz")
        meta = {"channels": channels, "programmes": programmes, "bytes": size, "published": int(time.time())}
        metadata = target + ".json"
        with open(metadata + ".tmp", "w", encoding="utf-8") as out:
            json.dump(meta, out)
        os.replace(metadata + ".tmp", metadata)
        print(f"EPG built workspace={workspace_id} channels={channels} programmes={programmes}", flush=True)
    except Exception as exc:
        print(f"EPG build failed workspace={workspace_id}: {exc}", flush=True)
    finally:
        for path in (staged, staged + ".gz"):
            if os.path.exists(path):
                os.unlink(path)


def main():
    while True:
        try:
            with (STORE / "data.json").open(encoding="utf-8") as handle:
                state = json.load(handle)
            for workspace_id, workspace in state.get("workspaces", {}).items():
                build_workspace(str(workspace_id), workspace)
        except Exception as exc:
            print(f"EPG worker: {exc}", flush=True)
        time.sleep(INTERVAL)


if __name__ == "__main__":
    main()
