FROM ubuntu:18.04
LABEL maintainer="Michaël Perrin"

# Install VLC command line tool
RUN apt-get update && apt-get install -y \
    vlc-bin \
    vlc-plugin-base

RUN apt-get update && apt-get install -y \
    cron

# Allow user to run VLC
RUN groupadd -g 999 appuser && \
    useradd -r -u 999 -g appuser appuser

# Fix permissions
RUN usermod -u 1000 appuser

ADD crontab /etc/cron.d/stream-recorder
RUN chmod 0644 /etc/cron.d/stream-recorder

CMD ["cron", "-f"]
