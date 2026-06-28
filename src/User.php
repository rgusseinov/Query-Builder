<?php

class User extends Model
{
    protected static string $table = 'users';
}

User::find(5);