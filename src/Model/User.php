<?php

class User extends Model
{
    protected static string $table = 'users';
    protected array $fillable = [
        'email',
        'full_name',
        'password_hash',
        'created_at'
    ];


    public function posts(){
        $queryBuilder = self::$container->get(QueryBuilder::class);

        $posts = $queryBuilder->table('posts')->where('user_id', '=', $this->attributes['id'])->get();

        return $posts;
    } 

}


