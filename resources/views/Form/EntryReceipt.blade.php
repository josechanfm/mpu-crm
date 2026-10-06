<!doctype html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>Macao Polytechnic University</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style type="text/css">
        @page {
            margin: 0; /* margins handled by dompdf setOption() */
        }

        * {
            font-family: 'SimHei', sans-serif;
            font-weight: normal;   /* SimHei has no bold face */
            font-style: normal;    /* SimHei has no italic face */
        }

        body {
            margin: 10px;
            font-family: 'SimHei', sans-serif;
            font-size: 12px;
            color: #000;
        }

        h1, h2, h3, h4, h5, h6,
        b, strong, th {
            font-family: 'SimHei', sans-serif;
            font-weight: normal;   /* avoid tofu from missing bold face */
        }

        table {
            border-spacing: 0;
            width: 100%;
        }

        table,
        td,
        th {
            border-collapse: collapse;
        }

        table td {
            border: 1px solid #ccc;
            padding: 10px;
            vertical-align: top;
        }

        th {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>

<div>
    <div>
        <img src="{{ public_path('/storage/images/mpu_banner.png') }}"
             alt="MPU Logo"
             style="display: block; margin: 0 auto 20px; height: 80px;" />
    </div>

    <div>
        <h2 style="text-align: center; margin-top: 20px;">{{ $entry->form->title }}</h2>
        <div style="text-align: right;">No.: {{ $entry->uid }}</div>

        <table style="margin-top: 20px;">
            <thead>
                <tr>
                    <th>Field Name / 欄位</th>
                    <th>Value / 內容</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Create a mapping of form_field_id to field_label and options
                    $fieldsMap = [];
                    foreach ($entry->form->fields as $field) {
                        $fieldsMap[$field->id] = [
                            'type'    => $field->type,
                            'label'   => $field->field_label,
                            'options' => $field->options,
                        ];
                    }

                    // Loop through entry records and display field names
                    foreach ($entry->records as $record) {
                        $fieldId    = $record->form_field_id;
                        $fieldName  = $fieldsMap[$fieldId]['label'] ?? 'Unknown Field';
                        $fieldType  = $fieldsMap[$fieldId]['type'] ?? null;
                        $fieldValue = $record->field_value;

                        switch ($fieldType) {
                            case 'true_false':
                                $fieldValue = $fieldValue ? '是/Yes' : '否/No';
                                break;

                            case 'dropdown':
                            case 'radio':
                                $options = array_column($fieldsMap[$fieldId]['options'] ?? [], 'label', 'value');
                                $fieldValue = $options[$fieldValue] ?? $fieldValue;
                                break;

                            case 'checkbox':
                                $decodedValue = json_decode($fieldValue, true);
                                if (json_last_error() === JSON_ERROR_NONE && is_array($decodedValue)) {
                                    $mappedValues = [];
                                    foreach ($decodedValue as $value) {
                                        if (isset($fieldsMap[$fieldId]['options'][$value])) {
                                            $mappedValues[] = $fieldsMap[$fieldId]['options'][$value]['label'];
                                        }
                                    }
                                    $fieldValue = implode('; ', $mappedValues);
                                } else {
                                    $fieldValue = 'Unsupported format';
                                }
                                break;

                            default:
                                // keep as-is
                                break;
                        }
                @endphp

                <tr>
                    <td>{{ $fieldName }}</td>
                    <td>{!! $fieldValue !!}</td>
                </tr>

                @php
                    } // end foreach records
                @endphp
            </tbody>
        </table>
    </div>
</div>

</body>
</html>