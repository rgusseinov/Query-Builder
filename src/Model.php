<?php

abstract class Model {
	protected static string $table;
	protected static $container;

	public static function setContainer($container){
		self::$container = $container;
	}

	public static function find(int $id){
		// print_r(get_class_methods(self::$container)); exit;
		$queryBuilder = self::$container->get(QueryBuilder::class);


		return $queryBuilder->table(static::$table)->where('id', '=', $id)->first();
	}
}