<!DOCTYPE HTML>
<html lang="ru">
<head>
    <title>MATLAB Documentation Center</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="https://nl.mathworks.com/includes_content/responsive/css/bootstrap/bootstrap.min.css" rel="stylesheet"
          type="text/css">
    <link href="https://nl.mathworks.com/includes_content/responsive/css/site6.css?201809" rel="stylesheet"
          type="text/css">


    <style>
        .es_wrap {
            display: flex;
        }

        .es_sidebar {
            flex-basis: 230px;
            flex-shrink: 0;
            border-right: 1px solid #ddd;
        }

        .es_main {
            flex-grow: 1;
            flex-shrink: 0;
        }

        #section_header_title {
            padding-left: 20px;
            display: flex;
        }

        .section_header form {
            padding-top: 0px;
        }

        .section_header_content {
            width: 230px;
            padding: 12px 0 11px;
        }

        .form-inline .form-control {
            width: calc(100% - 150px);
        }

        .es_prods li {
            background-color: white;
            padding: 6px 10px;
            margin: 6px;
        }

        .es_prods li a {
            cursor: pointer;
        }

        .search_result_table {
            background-color: white;
        }

        .prodbold {
            font-weight: bold;
            color: #fff;
            background-color: #0076a8 !important;
        }

        .prodbold a {
            color: #fff;
        }
    </style>

</head>
<body>
<div class="sticky_header_container includes_subnav">
    <div class="section_header level_3">
        <div class="container-fluid">
            <div class="row" id="mobile_search_row">
                <div class="" id="section_header_title" style="padding-left:20px;display: flex; align-items: center; ">
                    <div class="section_header_content">
                        <div class="section_header_title add_cursor_pointer">
                            <h1>Поиск</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="es_wrap">
    <div class="es_sidebar">
        <ul class="es_prods">
            <li>
                <a href="/" style="color:gray">Вернуться на главную</a>
            </li>
        </ul>
    </div>
    <div class="es_main">
        @if (count($result->hits))
            <div id="results_area">
                @foreach($result->hits as $hit)
                    <table class="table search_result_table">
                        <tbody>
                        <tr>
                            <td class="search_result_desc">
                                <span class="search_title">
                                    <a href="{{ route('docs.show.page', ['lang' => $hit->productLang, 'product' => $hit->product, 'version' => $hit->productVersion, 'page' => $hit->id, 'searchHighlight' => $result->query]) }}">{{ $hit->title }}</a>
                                </span>
                                <div class="search_highlight1">
                                    {{ $hit->body }}
                                </div>
                                <div class="search_url add_font_color_tertiary">
                                    <a href="{{ route('docs.show.page', ['lang' => $hit->productLang, 'product' => $hit->product, 'version' => $hit->productVersion, 'page' => $hit->id]) }}">{{ $hit->id }}</a>
                                </div>
                            </td>
                            <td class="search_result_icons"></td>
                        </tr>
                        </tbody>
                    </table>
                @endforeach
            </div>
        @else
            <h4 style="margin:20px; color:#555">Ничего не найдено! Попробуйте изменить поисковый запрос.</h4>
        @endif
    </div>
</div>
</body>
</html>

