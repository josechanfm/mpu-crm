<?php

namespace App\Exports;

use App\Models\Form;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EntryExport implements FromCollection, WithHeadings
{
    protected $form;
    protected $entries;
    public function __construct(Form $form, Collection $entries)
    {
        $this->form = $form;
        $this->entries = $entries;
    }

    /**
     * Build the column headers.
     */
    public function headings(): array
    {
        $columnHeaders = [];
        $fields = $this->form->fields->where('type', '<>', 'html')->sortBy('sequence');
        $expandTypes = ['checkbox', 'radio', 'dropdown', 'select'];

        foreach ($fields as $field) {
            $columnHeaders[] = $field->field_label;

            // If checkbox, add each option label as a separate column

            if (in_array($field->type, $expandTypes)) {
                $columnHeaders = array_merge(
                    $columnHeaders,
                    array_column($field->options, 'label', null)
                );
            }
        }
        //dd($columnHeaders);
        return $columnHeaders;
    }

    /**
     * Build the rows for the export.
     */
    public function collection()
    {
        // Get all non-html fields, keyed by id for quick lookup
        $formFields = $this->form->fields
            ->where('type', '<>', 'html')
            ->sortBy('sequence')
            ->keyBy('id')
            ->toArray();
        $rows = [];
            $expandTypes = ['checkbox', 'radio', 'dropdown', 'select'];

        foreach ($this->entries as $entry) {
            $rowData = [];

            // Loop through the entry's records
            foreach ($entry->records as $record) {
                $field = $formFields[$record->form_field_id] ?? null;
                if (!$field) continue;

                // For normal fields, just set the value
                $rowData[$field['field_label']] = $record->field_value;
                $opts = [];
                if (in_array($field['type'], $expandTypes)) {
                    $options = array_fill_keys(array_column($field['options'], 'value'), null);


                    $recordOptions = json_decode($record->field_value, true) ?? [];
                    //dd($options, $recordOptions, $record->field_value);
                 // dd($record, $options, $recordOptions);

                    if(is_array($recordOptions) && !empty($recordOptions)){
                        foreach($options as $key=>$value){
                            $options[$key] = in_array($key, $recordOptions) ? 1 : null;
                            
                            if($key=='option_other'){
                                $other= json_decode($record->extra, true);
                                if (json_last_error() == JSON_ERROR_NONE) {
                                    $options[$key] = $other['option_other'];
                                }else{
                                    $options[$key]='error input...';
                                }
                            }
                        }
                        //dd($options, $opts);
                    }else{
                        if($record->field_value=='option_other'){
                            $other= json_decode($record->extra, true);
                            if (json_last_error() == JSON_ERROR_NONE) {
                                $options['option_other'] = $other['option_other'];
                            }else{
                                $options['option_other']='error input...';
                            }
                        }else{
                            $options[$record->field_value]=1;
                        }
                    }
                    foreach ($options as $key=>$value) {
                        $opts[$record->id . '_' . $key]=$value;
                    }
                    $rowData = array_merge($rowData, $opts);
                }
            }

            $rows[] = $rowData;
        }
        //dd($rows);
        return collect($rows);
    }
}