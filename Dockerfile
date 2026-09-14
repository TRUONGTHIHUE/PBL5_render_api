FROM php:8.3-cli

WORKDIR /app

COPY public ./public
COPY data ./data

EXPOSE 10000

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-10000} -t public"]