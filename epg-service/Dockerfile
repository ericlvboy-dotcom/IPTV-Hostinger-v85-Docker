FROM python:3.12-slim
WORKDIR /opt/epg
COPY epg_engine.py worker.py ./
ENV PYTHONUNBUFFERED=1 IPTV_STORAGE=/storage EPG_INTERVAL_SECONDS=300
CMD ["python","-u","worker.py"]
