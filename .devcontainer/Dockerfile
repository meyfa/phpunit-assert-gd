FROM mcr.microsoft.com/devcontainers/php:8.2-bullseye

# Install libraries needed to compile GD
RUN apt-get update \
 && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install gd \
 && rm -rf /var/lib/apt/lists/*
