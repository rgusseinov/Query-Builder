<?php

class QueryBuilder {
  private Connection $connection;
  private string $table;
  private array $columns = ["*"];
  private array $wheres = [];

  public function __construct(Connection $connection){
    $this->connection = $connection;
  }

  public function table(string $table): self {
    $this->table = $table;

    return $this;
  }

  public function select(array|string $columns): self {
    $this->columns = is_array($columns) ? $columns : [$columns];

    return $this;
  }

  public function where(string $column, string $operator, mixed $value): self {
    $this->wheres[] = [
      'column' => $column,
      'operator' => $operator,
      'value' => $value
    ];

    return $this;
  }

  private function buildColumns(): array {
    return $this->columns;
  }

  private function buildWheresCondition(): array {
    $wheres = $this->wheres;

    if (empty($wheres)){
      return [];
    }

    return $wheres;
  }

  private function buildSelect(): array {
      $params = [];

      $table = $this->table;
      $columns = $this->buildColumns();
      $wheres = $this->buildWheresCondition();


      $columnsString = implode(',', $columns);
      $sql = "SELECT {$columnsString}";
          
      $sql .= " FROM {$table}";

      if (!empty($wheres)){
        $condition = [];
        
        foreach ($wheres as $where){
          $column = $where['column'];
          $operator = $where['operator'];
          $value = $where['value'];

          $condition[] = "{$column} {$operator} ?";
          $params[] = $value;
        }

        $conditionString = implode(' AND ', $condition);

        $sql .= " WHERE {$conditionString}";
      }

      return ['sql' => $sql, 'params' => $params];
  }

  public function get() {
    ['sql' => $sql, 'params' => $params] = $this->buildSelect();

    $result = $this->connection->fetchAll($sql, $params);

    return $result;
  }

  public function first(): ?array {
    ['sql' => $sql, 'params' => $params] = $this->buildSelect();

    return $this->connection->fetch($sql, $params);
  }

  private function buildInsert(array $data): array {
    $table = $this->table;
    
    $fields = implode(',', array_keys($data));
    $params = array_values($data);
    $placeholders = implode(',', array_fill(0, count($params), '?'));

    $sql = "INSERT INTO {$table} ($fields) VALUES ($placeholders)";

    return ['sql' => $sql, 'params' => $params];
  }

  public function insert(array $data): string|int {
    if (empty($data)){
      throw new InvalidArgumentException('Inserted data can"t be empty');
    }

    ['sql' => $sql, 'params' => $params] = $this->buildInsert($data);

    return $this->connection->insert($sql, $params);
  }

  private function buildUpdate(array $data): array {
    $table = $this->table;
    $wheres = $this->buildWheresCondition();

    if (empty($wheres)){
      throw new InvalidArgumentException('Where condition must me specified');
    }

    $sql = "";
    $clauses = [];
    $params = [];
    
    foreach (array_keys($data) as $key){
      $clauses[] = "{$key} = ?";

      $params[] = $data[$key];
    }

    ['whereCondition' => $whereConditionString, 'whereParams' => $whereParams] = $this->prepareWhereParams($params);
    

    $clausesString = implode(',', $clauses);
    $sql = "UPDATE {$table} SET {$clausesString} WHERE {$whereConditionString}";

    return ['sql' => $sql, 'params' => $whereParams];
  }

  private function prepareWhereParams(array $params): array {
    $wheres = $this->buildWheresCondition();

    $condition = [];
    $whereConditionString = "";
    
    foreach ($wheres as $where){
      $column = $where['column'];
      $operator = $where['operator'];
      $value = $where['value'];

      $condition[] = "{$column} {$operator} ?";
      $params[] = $value;
    }

    $whereConditionString = implode(' AND ', $condition);

    return ['whereCondition' => $whereConditionString, 'whereParams' => $params];
  }

  public function update(array $data): string|int {
    if (empty($data)){
      throw new InvalidArgumentException('Updated data can\'t be empty');
    }

    ['sql' => $sql, 'params' => $params] = $this->buildUpdate($data);

    return $this->connection->execute($sql, $params);
  }
}

/* $db->table('users')->insert([
    'name' => 'Ruslan',
    'email' => 'ruslan@test.com',
    'age' => 37
]); */


/* 
Case 1:
table('users')

Case 2:
table('users')
  ->select(['id', 'name'])

Case 3:
table('users')
  ->select(['id', 'name'])
  ->where('id', '=', 1)

table('users')
  ->select(['id', 'name'])
  ->where('id', '=', 1)
  ->where('status', '=', 'active')
              
              
              */
