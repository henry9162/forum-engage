<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    protected $guarded = [];

    // protected $fillable = [
    //     'blob_id',
    //     'url',
    //     'pictureable_id',
    //     'pictureable_type'
    // ];

    public function pictureable()
    {
        return $this->morphTo();
    }
}
