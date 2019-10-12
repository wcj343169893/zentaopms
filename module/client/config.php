<?php
$config->client->require = new stdclass();
$config->client->require->creat = 'version, strategy, downloads, status';
$config->client->require->edit  = 'version, strategy, downloads, status';

$config->client->upgradeApi = 'https://xuan.im/xxbversion-api-$.json';