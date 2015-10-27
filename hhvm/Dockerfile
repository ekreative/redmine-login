FROM ubuntu:14.04

ENV HOME /root

RUN apt-get update -qq && apt-get install -y -qq git curl wget

# Install HHVM
RUN wget -O - http://dl.hhvm.com/conf/hhvm.gpg.key | apt-key add -
RUN echo deb http://dl.hhvm.com/ubuntu trusty main | tee /etc/apt/sources.list.d/hhvm.list
RUN apt-get update -qq && apt-get install -y -qq hhvm

COPY hhvm.ini hhvm.ini
RUN cat hhvm.ini >> /etc/hhvm/php.ini

RUN mkdir /src
VOLUME /src
WORKDIR /src
ENV PHP_IDE_CONFIG=serverName=docker
CMD ./vendor/bin/phpunit
