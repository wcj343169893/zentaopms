# zentaopms
zentaopms

### 增加定时器post参数字段

``` bash
ALTER TABLE `zt_cron`   
  ADD COLUMN `data` TEXT NULL   COMMENT 'post参数,&符号连接' AFTER `lastTime`;
```
