#!/bin/sh
wp config create --dbname=wordpress --dbuser=wordpress --dbpass=wordpress --dbhost=database --skip-check --force --path=/app/wordpress
if wp core is-installed --path=/app/wordpress; then
    echo "WordPress is already installed"
    exit
fi
echo "Installing WordPress..."
/bin/sh /app/setup/install.sh
/bin/sh /app/setup/configure.sh
/bin/sh /app/setup/load-custom-code.sh
/bin/sh /app/setup/install-safly-curl-patch.sh
/bin/sh /app/setup/activate-plugins.sh
