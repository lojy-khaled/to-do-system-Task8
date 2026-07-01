<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        "title",
        "description",
        "is_done",
        "category_id"
    ];

    protected $guarded = [
        "id"
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
