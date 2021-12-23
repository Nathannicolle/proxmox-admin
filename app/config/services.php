<?php
use Ubiquity\controllers\Router;

\Ubiquity\cache\CacheManager::startProd($config);
\Ubiquity\orm\DAO::start();
Router::start();
\Ubiquity\assets\AssetsManager::start($config);
\Ubiquity\security\acl\AclManager::start();
\Ubiquity\security\acl\AclManager::initFromProviders([new \Ubiquity\security\acl\persistence\AclCacheProvider()]);
