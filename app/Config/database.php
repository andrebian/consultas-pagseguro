<?php
class DATABASE_CONFIG {

	public $default = array(
		'datasource' => 'Database/Sqlite',
		'persistent' => false,
		'host' => 'localhost',
		'login' => '',
		'password' => '',
		'database' => ':memory:',
		'prefix' => '',
		'encoding' => 'utf8'
	);
}
