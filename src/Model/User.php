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

}


