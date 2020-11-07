php bin/magento cache:clean
rm -rf var/view_preprocessed/*
rm -rf var/cache/*

rm -rf generated/*
find pub/static/frontend -name "*.js" -exec rm {} \;
find pub/static/frontend -name "*.css" -exec rm {} \;
chmod -R 777 .
