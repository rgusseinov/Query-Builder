<?php

abstract class Model {
	protected static string $table;
	protected static $container;

	public static function setContainer($container){
		self::$container = $container;
	}

	public static function find(int $id){

		$queryBuilder = self::$container->get(QueryBuilder:class);

	}
}