## Students Rest Api Example

## Requirements</h3>

* PHP 7.1+
* Apache with mod_rewrite
* PDO if using the Database

<h2> Database Connection </h2>

<p> Consider that for using database you should edit config.php file before start using database.</p>

<h2> Configure </h2>

<p> Ideally public folder should be mapped as your web root, It is recommended to avoid exposing unneeded files and folders.</p>

<h2>Prettify URLs</h2>

**Enable URL Rewriting**

Make sure all the requests are routed to api.php by enabling URL Rewriting for your website

If you are on Apache, you can use an .htaccess file such as

```
<IfModule mod_rewrite.c>
    RewriteEngine On

	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule ^(.+)$ api.php?$1 [QSA,L]
</IfModule>
```

Built by [Rodrigo Espinoza]()