### The "" file does not exist or is not readable.

Установить upload_max_filesize и post_max_size в php.ini большего размера чем файл.
Если используете php-fpm то в `/etc/php/{version}/fpm/php.ini`

И перезапустите службу 
```bash
service php-fpm restart
```
