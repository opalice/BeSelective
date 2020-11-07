#php bin/magento setup:upgrade
php -f bin/magento setup:static-content:deploy en_US --area adminhtml -f
php bin/magento setup:static-content:deploy en_US --theme Mimosa/mimc  -f
php bin/magento setup:static-content:deploy fr_FR --theme Mimosa/mimc   -f
php bin/magento setup:static-content:deploy nl_NL --theme Mimosa/mimc    -f
#php bin/magento setup:di:compile 

