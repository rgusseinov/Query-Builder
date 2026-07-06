<?php

abstract class Model {
	protected static string $table;
	protected bool $exists = false;
	protected array $attributes = [];
	protected array $fillable;
	protected static $container;

	public static function setContainer($container){
		self::$container = $container;
	}

	// Подумай, соответствует ли название тому, что метод делает.
	private function setAttributes($result){
		$this->attributes = $result;

		$this->exists = true;
	}

	public function __get(string $name){
		if (array_key_exists($name, $this->attributes)){
			return $this->attributes[$name];
		}

		throw new Exception("Property {$name} does not exist.");
	}

	public function __set($name, $value){
		if (!in_array($name, $this->fillable, true)) {
			throw new Exception("Property {$name} is not allowed.");
		}

		$this->attributes[$name] = $value;
	}

	public static function find(int $id){
		$queryBuilder = self::$container->get(QueryBuilder::class);
		$result = $queryBuilder->table(static::$table)->where('id', '=', $id)->first();

		if ($result == null){
			throw new Exception("Record ID {$id} not found.");
		}

		$childClassInstance = new static();
		$childClassInstance->setAttributes($result);

		return $childClassInstance;
	}

	public function save(){
		$queryBuilder = self::$container->get(QueryBuilder::class);

		if ($this->exists){
			$result = $queryBuilder->table(static::$table)->where('id', '=', $this->attributes['id'])->update($this->attributes);

			return (bool)$result;
		}

		$lastInsertId = $queryBuilder->table(static::$table)->insert($this->attributes);
		$this->attributes['id'] = $lastInsertId;

		$this->exists = true;

		return $this;
	}

	public static function all(){
		$queryBuilder = self::$container->get(QueryBuilder::class);

		return $queryBuilder->table(static::$table)->get();
	}
}