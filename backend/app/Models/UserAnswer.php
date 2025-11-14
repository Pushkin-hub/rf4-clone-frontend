<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    use HasFactory;

    protected $table = 'user_answer';

    protected $fillable = [
        'user_id',
        'question_id',
        'option_id'
    ];

    public $incrementing = false;
    protected $primaryKey = ['user_id', 'question_id'];
}
