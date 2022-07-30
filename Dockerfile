FROM php:7.4-alpine

COPY db db
COPY files files
COPY htdocs htdocs
COPY logs logs

CMD ["php", "-S", "0.0.0.0:8080", "-t", "htdocs", "htdocs/dev/router.php"]