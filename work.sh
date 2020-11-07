#php bin/magento setup:upgrade
rm -rf generated/*
php bin/magento cache:clean
#rm -rf var/*
find pub/static -name "*.css" -exec rm {} \;
find pub/static -name "*.js" -exec rm {} \;
find pub/static -name "*.js" -delete
find pub/static -name ".html" -delete
php -f bin/magento setup:static-content:deploy  --area adminhtml -f
php bin/magento setup:static-content:deploy en_US --theme Mimosa/mimc -f
php bin/magento setup:static-content:deploy fr_FR --theme Mimosa/mimc -f
php bin/magento setup:static-content:deploy nl_NL --theme Mimosa/mimc -f
php bin/magento setup:di:compile 

