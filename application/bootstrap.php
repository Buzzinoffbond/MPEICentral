<?php defined('SYSPATH') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
require SYSPATH.'classes/Kohana/Core'.EXT;

if (is_file(APPPATH.'classes/Kohana'.EXT))
{
	// Application extends the core
	require APPPATH.'classes/Kohana'.EXT;
}
else
{
	// Load empty core extension
	require SYSPATH.'classes/Kohana'.EXT;
}

/**
 * Set the default time zone.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/timezones
 */
date_default_timezone_set('Europe/Moscow');

/**
 * Set the default locale.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/function.setlocale
 */
setlocale(LC_ALL, 'ru_RU.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @link http://kohanaframework.org/guide/using.autoloading
 * @link http://www.php.net/manual/function.spl-autoload-register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Optionally, you can enable a compatibility auto-loader for use with
 * older modules that have not been updated for PSR-0.
 *
 * It is recommended to not enable this unless absolutely necessary.
 */
//spl_autoload_register(array('Kohana', 'auto_load_lowercase'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @link http://www.php.net/manual/function.spl-autoload-call
 * @link http://www.php.net/manual/var.configuration#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
I18n::lang('ru-ru');

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
Kohana::$environment = Kohana::PRODUCTION;      // DEVELOPMENT | PRODUCTION
if (isset($_SERVER['KOHANA_ENV']))
{
	Kohana::$environment = constant('Kohana::'.strtoupper($_SERVER['KOHANA_ENV']));
}



/**
 * Cookie
 */
// Set the magic salt to add to a cookie
Cookie::$salt = '/***MAGIC SALT HERE***/';
// Set the number of seconds before a cookie expires
Cookie::$expiration = Date::WEEK*4; // by default until the browser close
// Restrict the path that the cookie is available to
//Cookie::$path = '/';
// Restrict the domain that the cookie is available to
//Cookie::$domain = 'www.mydomain.com';
// Only transmit cookies over secure connections
//Cookie::$secure = TRUE;
// Only transmit cookies over HTTP, disabling Javascript access
//Cookie::$httponly = TRUE;





/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - integer  cache_life  lifetime, in seconds, of items cached              60
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 * - boolean  expose      set the X-Powered-By header                        FALSE
 */
Kohana::init(array(
	'base_url'   => '/',
	'index_file' => FALSE,
	'errors' 	 => FALSE
));

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */




Session::$default = 'database';

Kohana::modules(array(
	 'auth'       => MODPATH.'auth',       // Basic authentication
	// 'cache'      => MODPATH.'cache',      // Caching with multiple backends
	// 'codebench'  => MODPATH.'codebench',  // Benchmarking tool
	 'database'   => MODPATH.'database',   // Database access
	 'image'      => MODPATH.'image',      // Image manipulation
	// 'minion'     => MODPATH.'minion',     // CLI Tasks
	 'orm'        => MODPATH.'orm',        // Object Relationship Mapping
	// 'unittest'   => MODPATH.'unittest',   // Unit testing
	 //'userguide'  => MODPATH.'userguide',  // User guide and API documentation
	 'pagination' => MODPATH.'pagination', // Pagination
	));

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */
Route::set('calendar', 'events/calendar(/<year>/<month>)', array('year' => '[0-9]+'), array('month' => '[0-9]+'))
	->defaults(array(
		'controller' => 'events',
		'action'     => 'calendar',		
	));
Route::set('comments', 'comments/<section>/<id>', array('id' => '.+'),array('section' => 'events|articles '))
	->defaults(array(
		'controller' => 'comments',
		'action'     => 'index',		
	));
Route::set('admin_user_edit', 'admin/user/<action>(/<username>)')
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'user',
	));
Route::set('admin_users', 'admin/user(/page/<page>)', array('page' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'user',
		'action'     => 'index',
		'page'       => '',
	));
Route::set('admin_events', 'admin/event(/page/<page>)', array('page' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'event',
		'action'     => 'index',
		'page'       => '',
	));
Route::set('admin_articles', 'admin/article(/page/<page>)', array('page' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'article',
		'action'     => 'index',
		'page'       => '',
	));
Route::set('articles', 'articles(/page/<page>)', array('page' => '[0-9]+'))
	->defaults(array(
		'controller' => 'articles',
		'action'     => 'index',
		'page'       => '',
	));
Route::set('user', '<action>(/<id>)', array('action' => 'login|register|logout|edit|delete_me|vkauth|mergevk|reset_pass|request_pass_reset'))
        ->defaults(array(
            'controller' => 'user',
    ));
Route::set('pages', '<action>', array('action' => 'about'))
        ->defaults(array(
            'controller' => 'pages',
    ));
Route::set('username', 'user/<username>', array('username' => '.+'))
        ->defaults(array(
            'controller' => 'user',
            'action'     => 'index',
    ));
Route::set('competitor', 'contest/<id>(-<url_title>)/<competitor_id>(-<url_name>)', array('id' => '[0-9]+'), array('url_title' => '.+'), array('competitor_id' => '[0-9]+'), array('url_name' => '.+'))
	->defaults(array(
		'controller' => 'contest',
		'action'     => 'competitor',		
	));
Route::set('contest', 'contest/<id>(-<url_title>)', array('id' => '[0-9]+'), array('url_title' => '.+'))
	->defaults(array(
		'controller' => 'contest',
		'action'     => 'contest',		
	));
Route::set('article', 'articles/<id>(-<url_title>)', array('id' => '[0-9]+'), array('url_title' => '.+'))
	->defaults(array(
		'controller' => 'articles',
		'action'     => 'article',		
	));
Route::set('events', 'events(/page/<page>)', array('page' => '[0-9]+'))
	->defaults(array(
		'controller' => 'events',
		'action'     => 'index',		
	));
Route::set('event', 'event(/<id>(-<url_title>))', array('id' => '[0-9]+'), array('url_title' => '.+'))
	->defaults(array(
		'controller' => 'events',
		'action'     => 'event',		
	));
Route::set('error', 'error/<action>', array('action' => '[0-9]++'))
    ->defaults(array(
        'controller' => 'error'
    ));
Route::set('admin', 'admin(/<controller>(/<action>(/<id>(/<urlname>))))')
            ->defaults(array(
            'directory'  => 'admin',
            'controller' => 'dashboard',
            'action'     => 'index',
            ));
Route::set('default', '(<controller>(/<action>(/<id>)))')
	->defaults(array(
		'controller' => 'index',
		'action'     => 'index',
	));