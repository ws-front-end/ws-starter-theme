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
