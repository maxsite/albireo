<?php

/**
 * (c) Albireo Framework, https://maxsite.org/albireo, 2021
 *
 * Добавление лога в базу sqlite
 * 
 * Использование:
 * Logging\Log::log('text', ['log_level' => 'warning', 'log_group' => 'admin']);
 * Logging\Log::emergency('text', ['log_group' => 'admin']);
 * 
 * Logging\Log::emergency('text');
 * Logging\Log::alert('text');
 * Logging\Log::critical('text');
 * Logging\Log::error('text');
 * Logging\Log::warning('text');
 * Logging\Log::notice('text');
 * Logging\Log::info('text');
 * Logging\Log::debug('text');
 * 
 * log_level — уровень см. https://www.php-fig.org/psr/psr-3/
 * log_group — группа сообщений (по умолчанию general)
 * 
 */

namespace Logging;

class Log
{
	/** 
	 * Добавление записи в лог
	 * @param array $message - текст сообщения
	 * @param array $context - массив данных для добавления
	 * @return void
	 */
	public static function log(string $message, array $context = [])
	{
		if ($configDB = getConfigFile(CONFIG_DIR . 'dbase.php', 'log')) {
			$pdo = \Pdo\PdoConnect::getInstance();
			$db = $pdo->connect($configDB);
		}

		if (empty($db)) return;

		// проверка существования таблицы log
		if (!\Pdo\PdoQuery::tableExists($db, 'log')) {
			// если нет, то создаём новую
			$sql = '
				CREATE TABLE IF NOT EXISTS log (
				log_id INTEGER PRIMARY KEY AUTOINCREMENT,
				log_date DATETIME DEFAULT CURRENT_TIMESTAMP,
				log_group TEXT DEFAULT "general",
				log_level TEXT DEFAULT "info",
				log_message TEXT DEFAULT ""
			);';

			\Pdo\PdoQuery::query($db, $sql);
		}

		$context['log_message'] = $message;

		\Pdo\PdoQuery::insert($db, 'log', $context);
	}

	public static function emergency(string $message, array $context = [])
	{
		$context['log_level'] = 'emergency';
		self::log($message, $context);
	}

	public static function alert(string $message, array $context = [])
	{
		$context['log_level'] = 'alert';
		self::log($message, $context);
	}

	public static function critical(string $message, array $context = [])
	{
		$context['log_level'] = 'critical';
		self::log($message, $context);
	}

	public static function error(string $message, array $context = [])
	{
		$context['log_level'] = 'error';
		self::log($message, $context);
	}

	public static function warning(string $message, array $context = [])
	{
		$context['log_level'] = 'warning';
		self::log($message, $context);
	}

	public static function notice(string $message, array $context = [])
	{
		$context['log_level'] = 'notice';
		self::log($message, $context);
	}

	public static function info(string $message, array $context = [])
	{
		$context['log_level'] = 'info';
		self::log($message, $context);
	}

	public static function debug(string $message, array $context = [])
	{
		$context['log_level'] = 'debug';
		self::log($message, $context);
	}
}

# end of file
