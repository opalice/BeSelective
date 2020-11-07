php bin/magento deploy:mode:set developer
find pub/static -name "*.js" -exec rm {} \;
rm -rf generated/*
rm -rf var/view_preprocessed
rm -rf var/di/*

echo 'start'
find . -type f -exec chmod 644 {} \;
echo '644'
find . -type d -exec chmod 755 {} \;
echo '755'
find ./var -type d -exec chmod 777 {} \;
echo 'var 777'
find ./pub/media -type d -exec chmod 777 {} \;
echo 'pub/media 777'
find ./pub/static -type d -exec chmod 777 {} \;
echo 'pub static 777'
chmod 777 ./app/etc
echo 'app/etc 777'
chmod 644 ./app/etc/*.xml
echo 'app/etc/*.xml 644'
chmod -R g+w *
echo '-R g+x all'
chown -R beselective:www-data .
echo 'beselective:www-data'
echo 'RESET PERMISSIONS DONE'
