<?php
namespace Ca;
use \DB;
class Logger {
	private $type = null;
	static public $instances = array();

	public function __construct($type)
	{
		$this->type = $type;
	}
	
	static public function start($type)
	{
		if (!isset(static::$instances[$type]))
		{
			static::$instances[$type] = new static($type);
		}

		return static::$instances[$type];
	}

	public function log($log)
	{
		DB::table('log')
			->insert(array(
				'type' => $this->type,
				'log' => $log
			));
	}
}