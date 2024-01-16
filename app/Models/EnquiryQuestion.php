<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EnquiryQuestion extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable=['enquiry_id','parent_id','content','token','is_closed','admin_id','escalated'];
    protected $casts=['files'=>'json'];
    protected $appends=['admin_user'];

    protected static function boot(){
        parent::boot();
        static::creating(function ($model){
            $model->token=hash('crc32',time().'mpu-crm');
            $model->admin_id=null;
        });
        // static::retrieved(function ($model){
        //     $d=date('Y-m-d', strtotime($model->created_at. ' + 2 day'));
        //     $model->escalated=now()>$d;
        //     $model->save();
        // });
    }

    public function getAdminuserAttribute(){
        return AdminUser::find($this->admin_id);
    }
    public function enquiry(){
        return $this->belongsTo(Enquiry::class);
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
        $this->addMediaCollection('enquiryQuestionAttachments')->useDisk('enquiry');
    }

    public function responses(){
        return $this->hasMany(EnquiryResponse::class)->with('media');
    }
    public function lastResponse(){
        return $this->hasOne(EnquiryResponse::class)->latest();
    }
    public function parent() {
        return $this->belongsToOne(static::class, 'parent_id');
    }
    //each category might have multiple children
    public function children() {
        //return $this->hasMany(static::class, 'parent_id')->with('children')->with('adminUser')->with('emails')->orderBy('id', 'asc');
        return $this->hasMany(static::class, 'parent_id')->with('children')->orderBy('id', 'asc');
    }



}
