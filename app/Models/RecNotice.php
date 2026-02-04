<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class RecNotice extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable=['rec_vacancy_id','title_zh','title_en','title_pt','date_start','date_end','can_download','apply_button','published','user_id'];
    protected $casts=['can_download'=>'boolean','apply_button'=>'boolean','published'=>'boolean'];
    // protected $appends=['file_zh','file_en','file_pt'];

    // public function getFileZhAttribute(){
    //     return $this->media->where('collection_name','noticeFileZh')->first();
    // }
    // public function getFileEnAttribute(){
    //     return $this->media->where('collection_name','noticeFileEn')->first();
    // }
    // public function getFilePtAttribute(){
    //     return $this->media->where('collection_name','noticeFilePt')->first();
    // }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('vacancyNotices')->useDisk('recruitment');
        $this->addMediaCollection('noticeFileZh')->useDisk('recruitment');
        $this->addMediaCollection('noticeFileEn')->useDisk('recruitment');
        $this->addMediaCollection('noticeFilePt')->useDisk('recruitment');
    }

    public function vacancy(){
        return $this->belongsTo(RecVacancy::class,'rec_vacancy_id');
    }
}
