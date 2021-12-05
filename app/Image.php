<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * Get the user that owns the Image
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    // protected $appends = ['image_path'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function  getImagePathAttribute()
    {
      return asset('uploads/images/'. $this->image);
    }// end of get Image Path

    public function  getStikerPathAttribute()
    {
      return asset('uploads/stikers/'. $this->image);
    }// end of get Image Path
}
