<!doctype html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>Macao Polytechnic University — Submission Confirmation</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style type="text/css">
        @font-face {
            font-family: 'SimHei';
            font-style: normal;
            font-weight: normal;
            src: url('{{ public_path('fonts/simhei.ttf') }}') format('truetype');
        }

        @page {
            margin: 0;
        }

        * {
            font-family: 'SimHei', sans-serif;
            font-weight: normal;
            font-style: normal;
            box-sizing: border-box;
        }

        body {
            margin: 15mm;
            font-size: 11px;
            color: #000;
            line-height: 1.55;
        }

        /* ---------- Header ---------- */
        .header {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }

        .header td {
            vertical-align: middle;
            border: none;
            padding: 0;
        }

        .header .logo img {
            height: 60px;
            display: block;
        }

        .header .institution {
            text-align: right;
        }

        .header .institution .zh {
            font-size: 15px;
            letter-spacing: 2px;
        }

        .header .institution .en {
            font-size: 10px;
            letter-spacing: 0.5px;
            color: #555;
        }

        /* ---------- Document title ---------- */
        .doc-title {
            text-align: center;
            margin: 18px 0 6px;
        }

        .doc-title .zh {
            font-size: 18px;
            letter-spacing: 6px;
        }

        .doc-title .en {
            font-size: 11px;
            letter-spacing: 2px;
            color: #555;
            margin-top: 4px;
        }

        /* ---------- Reference / meta line ---------- */
        .meta {
            width: 100%;
            margin: 18px 0 14px;
            font-size: 11px;
        }

        .meta td {
            border: none;
            padding: 2px 0;
            vertical-align: top;
        }

        .meta .left  { text-align: left;  }
        .meta .right { text-align: right; }

        /* ---------- Intro paragraph ---------- */
        .intro {
            margin: 0 0 14px;
            text-align: justify;
            font-size: 11px;
            line-height: 1.7;
        }

        /* ---------- Section heading ---------- */
        .section-title {
            font-size: 12px;
            padding: 6px 10px;
            background-color: #efefef;
            border-left: 3px solid #333;
            border-top: 1px solid #ccc;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            margin-bottom: 0;
        }

        /* ---------- Field table ---------- */
        table.fields {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        table.fields th {
            background-color: #f7f7f7;
            border: 1px solid #ccc;
            padding: 7px 10px;
            text-align: left;
            font-size: 11px;
            color: #333;
        }

        table.fields td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            vertical-align: top;
            font-size: 11px;
        }

        table.fields td.label {
            width: 38%;
            background-color: #fafafa;
            color: #222;
        }

        table.fields td.value {
            width: 62%;
            word-wrap: break-word;
        }

        /* ---------- Confirmation note ---------- */
        .note {
            border: 1px solid #bbb;
            background-color: #f8f8f8;
            padding: 10px 12px;
            font-size: 10px;
            color: #333;
            line-height: 1.7;
            margin-bottom: 24px;
        }

        .note .heading {
            font-size: 11px;
            margin-bottom: 4px;
        }

        /* ---------- Signature / stamp ---------- */
        table.stamp {
            width: 100%;
            margin-top: 34px;
            border-collapse: collapse;
        }

        table.stamp td {
            border: none;
            vertical-align: bottom;
            padding: 0 12px;
            width: 50%;
        }

        .stamp-line {
            border-bottom: 1px solid #000;
            height: 46px;
        }

        .stamp-label {
            font-size: 10px;
            color: #555;
            padding-top: 4px;
        }

        /* ---------- Footer ---------- */
        .footer {
            margin-top: 28px;
            padding-top: 8px;
            border-top: 1px dashed #999;
            font-size: 9px;
            color: #777;
            text-align: center;
            line-height: 1.6;
        }
    </style>
</head>
<body>

{{-- ================= HEADER ================= --}}
<table class="header">
    <tr>
        <td class="logo" style="width: 240px;">
            <img src="file://{{ public_path('storage/images/mpu_banner.png') }}" alt="MPU Logo" />
        </td>
        <td class="institution">
            <div class="zh">澳門理工大學</div>
            <div class="en">Macao Polytechnic University</div>
        </td>
    </tr>
</table>

{{-- ================= DOCUMENT TITLE ================= --}}
<div class="doc-title">
    <div class="zh">提交確認</div>
    <div class="en">SUBMISSION CONFIRMATION</div>
</div>

{{-- ================= META ================= --}}
<table class="meta">
    <tr>
        <td class="left">Reference No. / 編號 : {{ $entry->uid }}</td>
        <td class="right">
            Date Issued / 發出日期 :
            {{ $entry->created_at ? $entry->created_at->format('Y-m-d') : date('Y-m-d') }}
        </td>
    </tr>
</table>

{{-- ================= INTRO ================= --}}
<div class="intro">
    This document confirms that the following submission has been received and recorded
    by the University. Please retain this confirmation for your reference.
    <br>
    本確認書用以證明以下資料已由大學接收並記錄在案，請妥善保存以作參考。
</div>

{{-- ================= FORM TITLE ================= --}}
<div class="section-title">{{ $entry->form->title }}</div>

{{-- ================= FIELD TABLE ================= --}}
<table class="fields">
    <thead>
        <tr>
            <th style="width: 38%;">Field / 欄位</th>
            <th style="width: 62%;">Submitted Value / 已提交內容</th>
        </tr>
    </thead>
    <tbody>
        @php
            $fieldsMap = [];
            foreach ($entry->form->fields as $field) {
                $fieldsMap[$field->id] = [
                    'type'    => $field->type,
                    'label'   => $field->field_label,
                    'options' => $field->options,
                ];
            }
        @endphp

        @forelse($entry->records as $record)
            @php
                $fieldId    = $record->form_field_id;
                $fieldName  = $fieldsMap[$fieldId]['label'] ?? 'Unknown Field';
                $fieldType  = $fieldsMap[$fieldId]['type'] ?? null;
                $fieldValue = $record->field_value;

                switch ($fieldType) {
                    case 'true_false':
                        $fieldValue = $fieldValue ? '是 / Yes' : '否 / No';
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
                        break;
                }
            @endphp

            <tr>
                <td class="label">{{ $fieldName }}</td>
                <td class="value">{{ $fieldValue }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2" style="text-align: center; color: #888;">
                    No records / 無記錄
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- ================= NOTE ================= --}}
<div class="note">
    <div class="heading">Note / 備註</div>
    This confirmation is system-generated and does not require a handwritten signature
    to be valid. If any information above is incorrect, please contact the responsible
    office as soon as possible.
    <br>
    本確認書由系統自動生成，無需親筆簽名亦具效力。如上述資料有誤，請儘快聯絡相關部門。
</div>



{{-- ================= FOOTER ================= --}}
<div class="footer">
    Macao Polytechnic University · 澳門理工大學<br>
    Issued on {{ date('Y-m-d H:i:s') }}
</div>

</body>
</html>