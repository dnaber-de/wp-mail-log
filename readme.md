# wp_mail()-Log
Wordpress-Plugin for developing environments. Logs every call of the `wp_mail()` function in a post of the custom post type `mail_log`.

## Blocking outgoing mails
Define `MAIL_LOG_BLOCK_MAILS` to `TRUE` (e.g. in `wp-config.php`) to block mails from beeing sent via `wp_mail()`.
