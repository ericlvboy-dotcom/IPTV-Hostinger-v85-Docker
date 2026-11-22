# IPTV Hostinger v85 Docker

This repository is being prepared for Docker deployment. **Do not deploy yet:** the original PHP website source files are not yet present under `website/`.

The source archive is `IPTV-Smart-Merger-Hostinger-v85-Full-EPG-Passthrough(1).zip`. Extract its contents directly into `website/`, preserving `.htaccess` and `assets/`.

Once source files are present, in Portainer choose Stacks → Add stack → Repository and set the repository URL to `https://github.com/ericlvboy-dotcom/IPTV-Hostinger-v85-Docker`, Compose path `docker-compose.yml`. The stack builds the Dockerfile and exposes port 8181.

Avoid committing provider credentials unless you intentionally want them publicly accessible.
