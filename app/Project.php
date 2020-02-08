<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $guarded = [];

    public function Tasks()
    {
        return $this->hasMany(Task::class)->orderBy('priority', 'asc');
    }
}
