# IPTV Hostinger v85 Docker

This repository builds the original v85 PHP website directly from `website/IPTV-v85-DietPi-Docker-Ready.zip`. No manual extraction or website upload is needed.

## Portainer deployment

1. Open **Stacks → Add stack → Repository**.
2. Repository URL: `https://github.com/ericlvboy-dotcom/IPTV-Hostinger-v85-Docker`
3. Repository reference: `refs/heads/main` (if required).
4. Compose path: `docker-compose.yml`.
5. Enable **build** support if available, then deploy.
6. Browse to `http://192.168.2.101:8181`.

The first build installs PHP curl, dom, and mbstring extensions. A named volume (`iptv-v85-storage`) persists runtime storage between container recreations. This is a new empty storage volume, not an import of your Hostinger configuration. Port 8181 should not be exposed directly to the internet without authentication and HTTPS.

If Portainer's Git repository stack deployment does not support building images in your setup, build the image with Docker Compose on the host first, or publish the image to a container registry.
