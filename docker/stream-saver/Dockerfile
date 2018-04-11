FROM ubuntu:latest
LABEL maintainer="Michaël Perrin"

# Install VLC
RUN apt-get update && apt-get install -y \
    vlc-nox

RUN apt-get update && apt-get install -y \
    cron

# Add user to run VLC
RUN groupadd -g 999 appuser && \
    useradd -r -u 999 -g appuser appuser

ADD crontab /etc/cron.d/stream-saver
RUN chmod 0644 /etc/cron.d/stream-saver

CMD ["cron", "-f"]
