<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'u0203001_mosmall' );

/** Имя пользователя MySQL */
define( 'DB_USER', 'u0203001_mosmall' );

/** Пароль к базе данных MySQL */
define( 'DB_PASSWORD', '*Il6XZrk7y' );

/** Имя сервера MySQL */
define( 'DB_HOST', 'localhost' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '-V:?-plou?DV96.R36]M;!9w(os, ?6_ZjO$G?@Te8b{QTN`(K|0}&th>Bx$k/Rj' );
define( 'SECURE_AUTH_KEY',  '><F5o[|S>S][tCn/O-KiP{BpsuqgSxc2AXpd0NsGu_x8Z|#ADtn`3.Nh]7VUH4OB' );
define( 'LOGGED_IN_KEY',    '9L#uC6JxcmQWcQ]:FFgu+{Zl3HzAtaqHng%=-KkYH%]zRyY[B<t64GS0@OBTzLSh' );
define( 'NONCE_KEY',        'A^ses>tC74t$- 44z>497cmmLQDzny51}bZzUQ,lXN4~ B94XirI7);(LR({s:Td' );
define( 'AUTH_SALT',        '5NNy)+}AM:8Po(c8Y0og7m*;S(}K pxBpD*lVq]KeMZE-7tyq{%r}5UT[:K_@!gd' );
define( 'SECURE_AUTH_SALT', 'kDrB{z^8MIw+njjcLJjrpmvb;8/ZA%l;^XH(9xmnfxF{ekEk9FK7@@G-r60UTyg!' );
define( 'LOGGED_IN_SALT',   'j3.I5Sq5~N]>%rp(m!y>-2~>I:q2z9VJ$D!/)J.9fFY;2S`5{N;8BUG-{F(YDu: ' );
define( 'NONCE_SALT',       'IZL!?Pj==3u$oC$>%Ow6d/*:zuMM10ym>L(22HH5ES]^X6$yXF?>H|*U].~`UVIL' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );
define ( 'WP_ALLOW_REPAIR', true );

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once( ABSPATH . 'wp-settings.php' );


