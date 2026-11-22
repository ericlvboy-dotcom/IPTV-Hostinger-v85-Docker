#!/bin/sh
set -eu
HERE=$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)
test -f "$HERE/website/index.php" || { echo "Website files missing"; exit 1; }
mkdir -p /opt/iptv-website
cp -a "$HERE/website/." /opt/iptv-website/
mkdir -p /opt/iptv-website/storage
chown -R 33:33 /opt/iptv-website/storage
chmod 700 /opt/iptv-website/storage
rm -f /opt/iptv-website/index.html
echo "Website installed at /opt/iptv-website"
echo "Now paste portainer-stack.yml into Portainer and deploy."
