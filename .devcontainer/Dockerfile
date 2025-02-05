FROM php:8.4-apache-bookworm

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Install composer
RUN curl -sSL https://getcomposer.org/installer | php \
    && chmod +x composer.phar \
    && mv composer.phar /usr/local/bin/composer

RUN apt-get update && apt-get install -y \
    librdkafka-dev

RUN install-php-extensions rdkafka

WORKDIR /app

