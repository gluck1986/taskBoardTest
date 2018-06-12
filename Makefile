install:
	chmod 0755 ./runtime
	cp ./config/settings/local.php.dist ./config/settings/local.php -n
	composer install
