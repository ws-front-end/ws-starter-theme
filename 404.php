<?php
/**
 * Custom 404 error page.
 *
 * @package ws-starter-theme
 */

header( 'HTTP/1.1 301 Moved Permanently' );
header( 'Location: ' . get_bloginfo( 'url' ) );
exit();
