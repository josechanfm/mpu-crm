<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EnquiryResponse extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    protected $fillable=['enquiry_question_id','title','remark','by_email','email_address','email_subject','email_content','admin_id','token','has_question'];
    protected $appends=['admin_user'];

    protected static function boot(){
        parent::boot();
        static::creating(function ($model){
            $model->token=hash('crc32',time().'mpu-crm');
            $model->admin_id=auth()->user()->id;
        });
    }

    public function getAdminUserAttribute(){
        return AdminUser::find($this->admin_id);
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
        $this->addMediaCollection('enquiryResponseAttachments')->useDisk('enquiry');

    }
    public function question(){
        return $this->belongsTo(EnquiryQuestion::class,'enquiry_question_id');
    }

}
