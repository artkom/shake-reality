<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

/**
 * Основные параметры WordPress.
 *
 * Этот файл содержит следующие параметры: настройки MySQL, префикс таблиц,
 * секретные ключи, язык WordPress и ABSPATH. Дополнительную информацию можно найти
 * на странице {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Кодекса. Настройки MySQL можно узнать у хостинг-провайдера.
 *
 * Этот файл используется сценарием создания wp-config.php в процессе установки.
 * Необязательно использовать веб-интерфейс, можно скопировать этот файл
 * с именем "wp-config.php" и заполнить значения.
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'shake-reality');

/** Имя пользователя MySQL */
define('DB_USER', 'root');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', 'root');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется снова авторизоваться.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '/_$K!xwt[xE)BEz~FLWRQ+wQ6n%y<pdr6Dj*M4jxKs8Z++xAv}|/I$?ib%/b0{_c');
define('SECURE_AUTH_KEY',  'qS>DK,#t[&p+a}C,OZNQ%)EaFtgIgB[S]rYOti6FoH| NJ[c@.8W~Np5!G)3Ejr<');
define('LOGGED_IN_KEY',    'qR55jH~kXAj;B*82S>eGI-up|sv[7x7Q|eEl%3o`MCg8-~DS$n(>+]&2J5|E`M$@');
define('NONCE_KEY',        'dd9bme|>)!;DrN&t%jPA8!L9oXsx*0: G?:hCx/5ZzSv_J5_IlgfgL|+=<.{z`Fi');
define('AUTH_SALT',        '}bA+w:X+afW33$Du-NUCf?rX^MyP1MuXO-XPG}[&A+oFt48h--e(,+E]JwVKBZe-');
define('SECURE_AUTH_SALT', 'oWX~=J&SU HN.H;K+g!OC;+zi8n<c o39)K1M<F}6Bd$l}~Y(/{W</(%[e9aW6Y4');
define('LOGGED_IN_SALT',   'r[bt!~p|XE5^rraDLG!GjRm?WX*On<7{btZwJC$k#iAZnu5Mzu$}_mhNLE*_-P={');
define('NONCE_SALT',       'e|+A;e$~SiKSEozSut]l<sPZUJ$/Nmr0b><<rmge*sppt~quy-0iSQ5mu[6xzbgE');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько блогов в одну базу данных, если вы будете использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 's2r_';

/**
 * Язык локализации WordPress, по умолчанию английский.
 *
 * Измените этот параметр, чтобы настроить локализацию. Соответствующий MO-файл
 * для выбранного языка должен быть установлен в wp-content/languages. Например,
 * чтобы включить поддержку русского языка, скопируйте ru_RU.mo в wp-content/languages
 * и присвойте WPLANG значение 'ru_RU'.
 */
define('WPLANG', 'ru_RU');

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Настоятельно рекомендуется, чтобы разработчики плагинов и тем использовали WP_DEBUG
 * в своём рабочем окружении.
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');
