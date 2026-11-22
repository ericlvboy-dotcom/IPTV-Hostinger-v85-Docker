IPTV v85 Docker deployment for DietPi 192.168.2.101

1. Copy this entire extracted folder to DietPi (e.g. /root/IPTV-v85-DietPi-Docker).
2. SSH into DietPi and run: sudo sh /root/IPTV-v85-DietPi-Docker/install-on-dietpi.sh
3. In Portainer, replace your previous stack YAML with portainer-stack.yml and redeploy.
4. Wait several minutes on first boot while PHP extensions compile.
5. Open http://192.168.2.101:8181

The original PHP website files are copied unchanged.
Existing storage is NOT deleted; copying an archive without storage preserves existing server data.
If migrating an existing installation, copy its storage/ folder separately to preserve users, source settings, and EPG.
Port 8181 should be restricted to your trusted LAN or protected by HTTPS/reverse proxy.
This Portainer stack uses an image directly, not build:, so no Dockerfile is required.
