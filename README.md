Dokumentatsioon asub nüüd Wiki's: https://github.com/ws-front-end/ws-starter-theme/wiki
```
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{HTTP_HOST} ^sitename\.test$
RewriteRule ^wp-content/uploads/(.*)$ https://arendus.wsys.ee/sitename/wp-content/uploads/$1 [NC,L]
</IfModule>
```
```
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /sitename/
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^wp-content/uploads/(.*)$ https://www.sitename.ee/wp-content/uploads/$1 [NC,L]
</IfModule>
```
```
mysql -u username -h hostname --ssl-mode=DISABLED --password=password databaseName < "D:\path\to\file\location.sql"
```
