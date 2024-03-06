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
                <div style="text-align: center;font-size: 24px;">Applicaton form in pdf format.</div>
                <p>Draft without any fomrat.</p>
                <ol>
                    @php
                    foreach($table_data as $key=>$value){
                        echo "<li>{$key}:{$value}</li>";
                    }
                    @endphp
                </lol>

            </div>
        </div>
</body>

</html>