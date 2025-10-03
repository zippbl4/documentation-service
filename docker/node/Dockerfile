FROM node:22-alpine3.19

RUN apk update
RUN apk add --update linux-headers

RUN apk add --no-cache \
    bash \
    nano

ARG UNAME

ENV USR=$UNAME
ENV GRP=$UNAME

RUN addgroup \
    -S "$GRP" && \
    adduser  \
    -S \
    -G "$GRP" "$USR" \
    && mkdir -p /home/$USR/downloads  /var/www \
    && chown -R $USR:$GRP /home/$USR \
    && chown -R $USR:$GRP /var/www

# Run everything after as non-privileged user.
USER $USR