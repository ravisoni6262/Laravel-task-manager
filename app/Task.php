<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $guarded = [];

    public function Project()
    {
        return $this->belongsTo(Project::class);
    }
}
