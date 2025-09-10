<!doctype html>
<html lang="en">

<head>
</head>
<style type="text/css">
    @font-face {
        font-family: SimHei;
        src: url('{{base_path().' /storage/'}}fonts/simhei.ttf') format('truetype')
    }

    /*         
    * {
        font-family: SimHei;
    }
    */
    table {
        border-spacing: 0px;
        width: 100%
    }

    table,
    td,
    th {
        border-collapse: collapse;
        font-family: SimHei, sans-serif;
    }

    table tr {
        line-height: 24px;
    }

    table td {
        border: 1px solid;
        padding-left: 5px;
    }
</style>

<body>

<div class="wrapper" style="font-family: SimHei">
    <div class="wrapper">
        <div class="error-spacer"></div>
        <div role="main" class="main">
            <h2 style="text-align: center; margin-top: 20px;">{{ $entry->form->title }}</h2> <!-- Form title -->
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="border: 1px solid #ccc; padding: 10px; text-align: left;">Field Name / 欄位</th>
                        <th style="border: 1px solid #ccc; padding: 10px; text-align: left;">Value / 內容</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    // Create a mapping of form_field_id to field_label and options
                    $fieldsMap = [];
                    foreach ($entry->form->fields as $field) {
                        $fieldsMap[$field->id] = [
                            'type'=>$field->type,
                            'label' => $field->field_label,
                            'options' => $field->options // Assuming options is an array
                        ];
                    }

                    // Loop through entry records and display field names
                    foreach ($entry->records as $record) {
                        $fieldId = $record->form_field_id;
                        $fieldName = $fieldsMap[$fieldId]['label'] ?? 'Unknown Field'; // Fallback if field not found
                        $fieldType = $fieldsMap[$fieldId]['type']; // Assuming field_type is defined
                        $fieldValue = $record->field_value; // Basic field value
                        switch ($fieldType) {
                            case 'true_false':
                                $fieldValue=$fieldValue?'是/Yes':'否/No';
                                break;
                            case 'dropdown':
                                $value=array_column($fieldsMap[$fieldId]['options'], 'label', 'value')[$fieldValue];
                                $fieldValue=$value;
                                break;
                            case 'radio':
                                // If fieldValue is a string and maps to options
                                $value=array_column($fieldsMap[$fieldId]['options'], 'label', 'value')[$fieldValue];
                                $fieldValue=$value;
                                break;
                            case 'checkbox':
                                // Check if fieldValue is a valid JSON string representing an array
                                $decodedValue = json_decode($fieldValue, true);
                                if (json_last_error() === JSON_ERROR_NONE && is_array($decodedValue)) {
                                    // Map the array values to their corresponding option labels
                                    $mappedValues = null;
                                    foreach ($decodedValue as $value) {
                                        if (isset($fieldsMap[$fieldId]['options'][$value])) {
                                            $mappedValues .= $fieldsMap[$fieldId]['options'][$value]['label'].'; ';
                                        }
                                    }
                                    $fieldValue=$mappedValues;
                                    //$fieldValue = implode(', ', $mappedValues); // Join mapped values as a string
                                } else {
                                    // If fieldValue is not an array, handle it as a single string
                                    $fieldValue = 'Unsupported format';
                                }

                                break;
                            default:
                                $fieldValue = $fieldValue;
                                break;
                        }
                    @endphp
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 10px;">{{ $fieldName }}</td>
                        <td style="border: 1px solid #ccc; padding: 10px;">{{ $fieldValue }}</td>
                    </tr>
                    @php
                    }
                    @endphp
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>

</html>