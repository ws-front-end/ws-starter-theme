Dokumentatsioon asub nüüd Wiki's: https://github.com/ws-front-end/ws-starter-theme/wiki
```
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{HTTP_HOST} ^sitename\.test$
RewriteRule ^wp-content/uploads/(.*)$ https://wsys.ee/sitename/wp-content/uploads/$1 [NC,L]
</IfModule>
```
```
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /russkoeradio/
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^wp-content/uploads/(.*)$ https://www.russkoeradio.fm/wp-content/uploads/$1 [NC,L]
</IfModule>
```
```
mysql -u username -h hostname --ssl-mode=DISABLED --password=password databaseName < "D:\path\to\file\location.sql"
```