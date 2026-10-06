<!doctype html>
<html>
    <head>
    <meta charset="UTF-8">
    <title>Macao Polytechnic University</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style type="text/css">
        @font-face {
            font-family: 'NotoSansTC';
            font-style: normal;
            font-weight: 400;
            src: url('{{ public_path('fonts/Noto/NotoSansTC-Regular.ttf') }}') format('truetype');
        }

        body {
            margin: 10px;
            font-family: 'NotoSansTC', sans-serif;
        }
    
        table {
            border-spacing: 0px;
            width: 100%
        }

        table,
        td,
        th {
            border-collapse: collapse;
        }

        table tr {
            line-height: 4px;
        }

        table td {
            border: 1px solid;
            padding-left: 2px;
        }
    </style>
</head>
<body>

<div style="font-family: SimHei">
    <div>
        <img src="{{ public_path('/storage/images/mpu_banner.png') }}" alt="MPU Logo" style="display: block; margin: 0 auto 20px; height: 80px;" />
    </div>
    <div>
        <h2 style="text-align: center; margin-top: 20px;">{{ $entry->form->title }}</h2> <!-- Form title -->
        <div style="text-align: right">No.: {{ $entry->uid }}</div>
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
                    <td style="border: 1px solid #ccc; padding: 10px;">{!! $fieldValue !!}</td>
                </tr>
                @php
                }
                @endphp
            </tbody>
        </table>
    </div>
</div>

</body>

</html>