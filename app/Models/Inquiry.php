<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Inquiry extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable=['email','name','token'];

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('questionAttachments')->useDisk('media');
    }
    
    public function department(){
        return $this->belongsTo(Department::class);
    }
    public function responses(){
        return $this->hasMany(Response::class)->with('media');
    }

    public function parent() {
        return $this->belongsToOne(static::class, 'parent_id');
    }
    //each category might have multiple children
    public function children() {
        //return $this->hasMany(static::class, 'parent_id')->with('children')->with('adminUser')->with('emails')->orderBy('id', 'asc');
        return $this->hasMany(static::class, 'parent_id')->with('children')->with('adminUser')->with('emails')->orderBy('id', 'asc');
    }
    public function adminUser(){
        return $this->belongsTo(AdminUser::class);
    }

}
