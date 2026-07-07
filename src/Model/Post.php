<?php

class Post extends Model {
    protected static string $table = 'posts';

    protected array $fillable = [
        'user_id',
        'title',
        'body',
    ];
}