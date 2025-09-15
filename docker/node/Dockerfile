FROM node:20-bullseye

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
  python3 make g++ \
  && rm -rf /var/lib/apt/lists/*
