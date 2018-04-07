FROM debian:stretch-slim
LABEL maintainer="Michaël Perrin"

RUN apt-get update && apt-get install -y \
    vlc-nox

RUN groupadd -g 999 appuser && \
    useradd -r -u 999 -g appuser appuser
USER appuser

ENTRYPOINT ["cvlc"]
