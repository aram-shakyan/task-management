<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Task extends Eloquent
{
    protected $fillable = [
        'name', 'email', 'text', 'status', 'is_edited'
    ];

    const IN_PROGRESS = 0;
    const DONE = 1;


    /**
     * @param $value
     */
    public function setStatusAttribute($value)
    {
        $this->attributes["status"] = (string) $value;
    }
}