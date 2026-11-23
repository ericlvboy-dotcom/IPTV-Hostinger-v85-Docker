"""XMLTV file validation and deterministic merged-feed generation."""
import gzip
import os
from xml.etree import ElementTree as ET


def open_epg_file(path):
    with open(path, "rb") as source:
        magic = source.read(2)
    return gzip.open(path, "rb") if magic == b"\x1f\x8b" else open(path, "rb")


def validate_xmltv(path):
    seen_root = False
    with open_epg_file(path) as source:
        for event, element in ET.iterparse(source, events=("start", "end")):
            if event == "start" and not seen_root:
                seen_root = True
                if element.tag != "tv":
                    raise ValueError("XMLTV root is not <tv>")
            if event == "end":
                element.clear()
    if not seen_root:
        raise ValueError("Empty XMLTV")


def build_merged_xmltv(sources, allowed_channel_ids, filter_ids, target, set_setting, source_path, workspace):
    temporary = target + ".tmp"
    seen_channels = set()
    with open(temporary, "wb") as output:
        output.write(b"<?xml version='1.0' encoding='utf-8'?>\n<tv>")
        for source in sources:
            path = source_path(source["id"])
            if not os.path.exists(path):
                continue
            try:
                with open_epg_file(path) as stream:
                    stack = []
                    for event, element in ET.iterparse(stream, events=("start", "end")):
                        if event == "start":
                            stack.append(element)
                            continue
                        if len(stack) == 2:
                            if element.tag == "channel":
                                channel_id = element.get("id")
                                if channel_id and (not filter_ids or channel_id in allowed_channel_ids) and channel_id not in seen_channels:
                                    output.write(ET.tostring(element, encoding="utf-8"))
                                    seen_channels.add(channel_id)
                            elif element.tag == "programme" and (not filter_ids or element.get("channel") in allowed_channel_ids):
                                output.write(ET.tostring(element, encoding="utf-8"))
                            element.clear()
                        stack.pop()
            except Exception:
                # Preserve existing behavior: skip an unavailable or malformed
                # individual source and still publish the valid feeds.
                continue
        output.write(b"</tv>")
    os.replace(temporary, target)
    compressed = target + ".gz"
    compressed_temporary = compressed + ".tmp"
    with open(target, "rb") as source, open(compressed_temporary, "wb") as raw:
        with gzip.GzipFile(fileobj=raw, mode="wb", mtime=0) as archive:
            while True:
                chunk = source.read(1024 * 1024)
                if not chunk:
                    break
                archive.write(chunk)
    os.replace(compressed_temporary, compressed)
    set_setting("epg_output_size", os.path.getsize(target), workspace)
    set_setting("epg_output_gzip_size", os.path.getsize(compressed), workspace)
    return target, compressed
