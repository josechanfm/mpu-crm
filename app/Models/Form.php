<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class Form extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    protected $fillable = ['department_id', 'name', 'title', 'welcome', 'description', 'thanks', 'require_login', 'for_staff', 'published','layout','remark'];
    protected $cast=['require_login'=>'boolean','for_staff'=>'boolean','published'=>'boolean'];
    protected $appends=['entry_count','thumbnail'];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate a unique ID using Str::random or any other method
            $model->uuid =  (string) Str::uuid();
        });
    }

    public function getEntryCountAttribute(){
        return $this->entries->count();
    }
    public function getThumbnailAttribute(){
        return $this->media()->where('collection_name','thumb')->first()?->original_url;
    }

    public function registerMediaConversions(Media $media = null): void
    {
        // $this
        //     ->addMediaConversion('preview')
        //     ->fit(Manipulations::FIT_CROP, 300, 300)
        //     ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('banner')->singleFile()->useDisk('media');
        $this->addMediaCollection('thumb')->singleFile()->useDisk('media');
        // $this->addMediaCollection('form_content');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function fields()
    {
        return $this->hasMany(FormField::class)->orderByRaw('-sequence DESC');
    }
    //extra fields show in entry table
    public function in_column_fields()
    {
        return $this->hasMany(FormField::class)->where('in_column', 1);
    }
    //entry table column headers, for frontend table view and export to excel
    public function entry_columns()
    {
        $columns[] = (object)['title' => '#', 'dataIndex' => 'uid'];
        if($this->for_staff){
            $columns[] = (object)['title'=>'Net Id','dataIndex'=>'net_id'];
        }
        foreach ($this->in_column_fields as $column) {
            $columns[] = (object)['title' => $column->field_label, 'dataIndex' => 'extra_' . $column->id];
        }
        $columns[] = (object)['title' => 'Submit at', 'dataIndex' => 'submitted_at'];
        $columns[] = (object)['title' => 'Action', 'dataIndex' => 'operation'];
        return $columns;
    }

    public function entries()
    {
        return $this->hasMany(Entry::class)->with('records');
    }
    public function entries_group_count($groupName=''){
        $fieldId=array_column($this->fields->toArray(),null,'field_name')[$groupName]['id'];
        $records=$this->entryRecords()->select('field_value',DB::raw('count(*) as count'))->where('entry_records.form_field_id',$fieldId)->groupBy('form_id','field_value')->get();
        return $records;
    }

    //entries for frontend table view and export to excel
    public function tableEntries()
    {
        $entries = $this->entries;
        $fields = $this->in_column_fields;
        foreach ($entries as $entry) {
            $entry->adminUser;
            foreach ($fields as $field) {
                $f = $entry->records->where('form_field_id', $field->id)->first();
                if ($f) {

                    if ($field->type == 'radio') {
                        $fieldOptions = json_decode($field->options);
                        $value = array_filter($fieldOptions, function ($item) use ($f) {
                            return $item->value == $f->field_value;
                        });
                        $valueItem = reset($value);
                        $entry['extra_' . $field->id] = $valueItem->label ?? '';
                        // academic.form$entry);
                    } else if ($field->type == 'checkbox') {
                        $fieldOptions = json_decode($field->options);
                        $fieldValue = json_decode($f->field_value);
                        $value = array_filter($fieldOptions, function ($item) use ($fieldValue) {
                            return in_array($item->value, $fieldValue);
                        });
                        $labels = [];
                        foreach ($value as $item) {
                            $labels[] = $item->label;
                        }
                        $result = implode(',', $labels);
                        $entry['extra_' . $field->id] = $result;
                    } else {
                        $entry['extra_' . $field->id] = $f->field_value;
                    }
                }
            }
        }
        return $entries;
    }
    public function entryRecords(){
        return $this->hasManyThrough(EntryRecord::class, Entry::class);
    }
    public function records()
    {
        $fields = $this->fields;
        $entries = $this->entries;
        $list = [];
        foreach ($entries as $e => $entry) {
            $tmp = [];
            foreach ($fields as $f => $field) {
                // $tmp['entry_id'] = $entry->id;
                $tmp[$field->id] = '';
            }
            foreach ($entry->records as $r => $record) {
                $tmp[$record->form_field_id] = $record->field_value;
            }
            array_push($list, $tmp);
        }
        return collect($list);
    }

    public function excelRecords()
    {
        $form_fields = $this->fields->where('type','<>','html')->sortBy('sequence');
        $list = [];
        // $this->form->fields->pluck('field_label')->toArray();
        foreach ($this->entries as $entry) {
            $entry_records = $entry->records;
            collect($form_fields)->map(function ($field, $key) use ($entry_records, &$table_data) {
                $entry_record = collect($entry_records)->filter(function ($item) use ($field) {
                    return $item->form_field_id == $field->id;
                })->first();
                if ($field->type == 'radio') {
                    $value = array_filter(json_decode($field->options), function ($item) use ($entry_record) {
                        return $item->value === $entry_record?->field_value;
                    });
                    $table_data[$field->field_label] = reset($value)->label ?? '';
                // } else if ($field->type == 'checkbox') {
                //     //dd($entry_records, $field, $field->options);
                //     $value = array_filter(json_decode($field->options), function ($item) use ($entry_record) {
                //         return in_array($item->value, json_decode($entry_record->field_value));
                //     });
                //     $labels = [];
                //     foreach ($value as $item) {
                //         $labels[] = $item->label;
                //     }
                //     $result = implode(',', $labels);
                //     $table_data[$field->field_label] = $result;
                } else {
                    $table_data[$field->field_label] = $entry_record?->field_value;
                };
            });
            array_push($list, $table_data);
        }
        return collect($list);
    }
    public function hasChild()
    {
        return $this->fields()->exists();
    }
    // public function members(): MorphToMany{
    //     return $this->morphToMany(Member::class,'attendee')->withPivot(['status']);
    // }

}
