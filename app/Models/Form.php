<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Form extends Model
{
    use HasFactory;
    use InteractsWithMedia;
    protected $fillable=['department_id','name','title','welcome','description','thankyou','require_login','for_staff','published'];

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function fields(){
        return $this->hasMany(FormField::class);
    }
    public function filleds(){
        return $this->hasMany(Filled::class)->with('fields');
    }
    public function hasChild(){
        return $this->fields()->exists();
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->useDisk('media');
    }        
}
