# zentaopms
zentaopms

### 增加定时器post参数字段

``` bash
ALTER TABLE `zt_cron`   
  ADD COLUMN `data` TEXT NULL   COMMENT 'post参数,&符号连接' AFTER `lastTime`;
```

如果get方式或者post方式是https的链接地址，必须保证
1.php安装了openssl扩展
  ``` bash
  [root@bbbb ~]# echo '<?php phpinfo(); ?>' | php 2>&1 |grep -i ssl
Registered Stream Socket Transports => tcp, udp, unix, udg, ssl, sslv3, tls, tlsv1.0, tlsv1.1, tlsv1.2
SSL => Yes
SSL Version => NSS/3.21 Basic ECC
openssl
OpenSSL support => enabled
OpenSSL Library Version => OpenSSL 1.0.2k-fips  26 Jan 2017
OpenSSL Header Version => OpenSSL 1.0.2k-fips  26 Jan 2017
Openssl default config => /etc/pki/tls/openssl.cnf
openssl.cafile => no value => no value
openssl.capath => no value => no value
Native OpenSSL support => enabled
  ```
2.php能执行/usr/bin/curl,直接shell执行curl和php执行返回的内容一致
``` bash
[root@bbbb ~]# echo '<?php is_executable(`/usr/bin/curl`); ?>' | php
curl: try 'curl --help' or 'curl --manual' for more information
```
