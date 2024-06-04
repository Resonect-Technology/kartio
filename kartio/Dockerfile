FROM dunglas/frankenphp

RUN apt-get update && apt-get install -y --no-install-recommends \
	nodejs \
	npm \
	&& rm -rf /var/lib/apt/lists/*

RUN install-php-extensions \
	mongodb

# Be sure to replace "your-domain-name.example.com" by your domain name
# ENV SERVER_NAME=kartio.resonect.cz
# If you want to disable HTTPS, use this value instead:
ENV SERVER_NAME=:80

# Enable PHP production settings
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Copy the PHP files of your project in the public directory
# COPY . /app/public
# If you use Symfony or Laravel, you need to copy the whole project instead:
WORKDIR /app
COPY . .

# Install Tailwind CSS and DaisyUI
RUN npm i -D daisyui@latest
RUN php /app/bin/console tailwind:build

# Run the application with FrankenPHP
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
