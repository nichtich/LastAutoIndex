<?php
/**
 * Main Class
 */

namespace projectcleverweb\lastautoindex;

/**
 * Main Class (controller)
 * =======================
 * This is the "controller" of LAI's MVC framework
 * 
 * Here is a visual representation the "chain of command" within this system
 * //===VISUAL MAP==============================\\
 * ||                                           ||
 * ||        [Controller]                       ||
 * ||           |    |                          ||
 * ||       -----    -------                    ||
 * ||       |              |                    ||
 * ||       V              V                    ||
 * ||  [View-Model]<--->[Model]<--->[Database]  ||
 * ||       |                                   ||
 * ||       V                                   ||
 * ||     [View]                                ||
 * ||                                           ||
 * \\===========================================//
 * 
 * @see http://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller
 */
class main {
	
	const VERSION = '1.1.0';
	
	public static $error;
	public static $config_file;
	public static $config;
	public static $has_update;
	public static $update;
	public static $old_releases;
	public static $base_dir;
	public static $public_dir;
	public static $system_dir;
	public static $themes_dir;
	public static $theme_dir;
	public static $base_uri;
	public static $public_uri;
	public static $themes_uri;
	public static $theme_uri;
	public static $db = FALSE;
	public static $cookie;
	public static $github;
	public static $login;
	public static $server;
	
	/**
	 * Configure the system and display the theme
	 * 
	 * @param  string $config_file Custom path to the config file
	 * @return void
	 */
	public static function init ($config_file = '') {
		if (empty($config_file)) {
			self::$config_file = __DIR__.'/../config.php';
		}
		self::$error  = new error();
		self::$config = self::_get_config($config_file);
		self::_check_config(self::$config);
		self::set_vars(self::$config);
		debug::init();
		self::update_check();
		theme::init();
		theme::display();
	}
	
	/**
	 * Read the configuration
	 * 
	 * @param  string $config_file Path to the config file to read
	 * @return array               The configuration array
	 */
	private static function _get_config($config_file = '') {
		if (!empty($config_file)) {
			if (is_file($config_file)) {
				self::$config_file = realpath($config_file);
				return require_once $config_file;
			}
			self::$error->standard('Configuration file "'.$config_file.'" doesn\'t exist!');
		}
		
		if (!is_file(self::$config_file)) {
			self::$error->fatal('No configuration file exists!');
		}
		self::$config_file = realpath(self::$config_file);
		return require_once self::$config_file;
	}
	
	/**
	 * Check the configuration for errors
	 * 
	 * @param  array  $config The configuration array to check
	 * @return void
	 */
	private static function _check_config($config) {
		$required_configs = array(
			'use_login'
		);
		
		$error_fmt = 'Configuration Option "%1$s" doesn\'t exist!';
		$errors = 0;
		foreach ($required_configs as $required_config) {
			if (!isset($config[$required_config])) {
				self::$error->standard(sprintf($error_fmt, $required_config));
			}
		}
		if ($errors) {
			self::$error->fatal('Configuration file is broken! (options are missing)');
		}
	}
	
	/**
	 * Set this class' variables
	 * 
	 * @param array $config The configuration array
	 */
	protected static function set_vars($config) {
		self::$server         = $_SERVER;
		self::$base_dir       = self::get_base_dir();
		self::$system_dir     = self::$base_dir.DIRECTORY_SEPARATOR.'system';
		self::$public_dir     = self::$base_dir.DIRECTORY_SEPARATOR.'public';
		self::$themes_dir     = self::$public_dir.DIRECTORY_SEPARATOR.'themes';
		self::$theme_dir      = self::$themes_dir.DIRECTORY_SEPARATOR.$config['theme'];
		if (empty($config['theme'])) {
			self::$theme_uri = self::$themes_uri.DIRECTORY_SEPARATOR.'default';
		}
		self::$base_uri   = new uri(self::get_base_uri());
		self::$public_uri = new uri(self::$base_uri.'/public');
		self::$themes_uri = new uri(self::$public_uri.'/themes');
		self::$theme_uri  = new uri(self::$themes_uri.'/'.$config['theme']);
		if (empty($config['theme'])) {
			self::$theme_uri = self::$themes_uri.'/default';
		}
		if ($config['use_login']) {
			if (!isset($config['database_host'], $config['database_user'], $config['database_pass'], $config['database_name'])) {
				self::$error->fatal('Database var(s) are not set');
			}
			self::$db = new database($config['database_host'], $config['database_user'], $config['database_pass'], $config['database_name']);
		}
		self::$cookie = new cookie;
		self::$login  = new login;
		self::$github = new github;
	}
	
	/**
	 * Get the above directory
	 * 
	 * @return string Path to base directory
	 */
	protected static function get_base_dir() {
		return realpath(__DIR__.'/..');
	}
	
	/**
	 * Generate the URI to the base directory
	 * @return string URI to the base directory
	 */
	protected static function get_base_uri() {
		$uri = substr(self::$base_dir, strlen($_SERVER['DOCUMENT_ROOT']));
		return sprintf(
			'%1$s://%2$s%3$s%4$s',
			self::$server['REQUEST_SCHEME'],
			self::$server['HTTP_HOST'],
			((int) self::$server['SERVER_PORT'] == 80 ? '' : ':'.self::$server['SERVER_PORT']),
			str_replace('\\', '/', $uri)
		);
	}
	
	/**
	 * Simple check for newer release of LastAutoIndex. In the future, this
	 * will only check while admin users are logged in.
	 * 
	 * Note: sets self::$has_update, self::$update, and self::$old_releases
	 * 
	 * @param  string $version The version to check against
	 * @return void
	 */
	private static function update_check($version = self::VERSION) {
		self::$has_update = FALSE;
		// Is this request saying that we should ignore an update?
		if (!empty($_GET['ignore_release'])) {
			self::$cookie->set('ignore_release', $_GET['ignore_release']);
		}
		
		// Should we check for an update?
		if (!self::$cookie->exists('update_check') || self::$cookie->exists('has_update')) {
			// Yes we should, connect to github.
			self::$cookie->set('update_check', (string) time(), cookie::WEEK);
			$response = self::$github->get('/repos/:owner/:repo/releases', [
				'owner' => 'project-cleverweb',
				'repo'  => 'LastAutoIndex'
			]);
			$releases           = json_decode($response->getContent());
			$latest_release     = array_shift($releases);
			
			// Are we ignoring this update?
			if (
				self::$cookie->exists('ignore_release') &&
				version_compare(self::$cookie->get('ignore_release'), $latest_release->tag_name, '>=')
			) {
				// Yes
				return;
			}
			
			// Is the update provided newer than the current version?
			self::$has_update   = version_compare($latest_release->tag_name, $version, '>');
			if (self::$has_update) {
				// Only check update info once a week
				self::$cookie->set(
					'has_update',
					$latest_release->tag_name,
					cookie::WEEK
				);
				self::$update       = $latest_release;
				self::$old_releases = $releases;
			} elseif (self::$cookie->exists('has_update')) {
				// Already Updated, remove the cookie that says otherwise
				self::$cookie->delete('has_update');
			}
		}
	}
}
