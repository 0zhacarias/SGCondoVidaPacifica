<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Relatório do Projecto</title>
</head>
<div id="logo">
    <img src="img/mutue.jpeg">
</div>
<header class="clearfix">
    <div id="company">
        <h2 class="name">Mutue Soluções tecnologias inteligentes</h2>
        <div>Rua Nossa Senhora da Muxima, Kinaxixi</div>
        <div>Luanda-Angola</div>
        <div>(+244)922 969 192</div>
        <div>geral@mutue.net</div>
    </div>
    </div>
</header>

<body>
    <div id="details" class="clearfix">

        <div id="invoice">
            <h2>RELATÓRIO DO PROJECTO</h2>
            @if (count($arquivo_pdf_projecto))
                @foreach ($arquivo_pdf_projecto as $projecto)
                    <div>Data Inicial do projecto: {{ $projecto->dat_inicio_real }}</div>
                    <div>Data Final: {{ $projecto->dat_fim_real }}</div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="flex">
        <h3>Dados do Projecto <img src="img/project.png" alt="" width="12px"></h3>
        @if (count($arquivo_pdf_projecto))
            @foreach ($arquivo_pdf_projecto as $projecto)
                <div class="titulos">Nome: <span class="subtitulos">{{ $projecto->nome_proj }}</span></div>
                <div class="titulos">Gestor do Projecto: <span class="subtitulos">{{ $projecto->responsavel->nome_responsavel }}</span></div>
                <div class="titulos">Prioridade: <span class="subtitulos">{{ $projecto->prioridade_proj }}</span></div>
                <div class="titulos">Estado: <span class="subtitulos">{{ $projecto->estadoProjeto->designacao }}</span>
                </div>
            @endforeach
        @endif
    </div>

    <h3 class="respon">Lista dos Responsáveis <img src="img/group4.png" alt="" width="12px"></h3>
    <table class="rwd-table2">
        <tr>
            <th>Nome dos Responsáveis</th>
            <th>Função</th>
        </tr>
        @if (count($arquivo_pdf_projecto))
            {{--  este ciclo é para percorrer a tarefa que vem da control tarefa  --}}
            @foreach ($arquivo_pdf_projecto as $projecto)
                {{-- <!-- <td>{{ $projecto->responsavel_id }}</td> --> --}}
                {{--  este ciclo serve para o relacionamento entre a tarefa e tabela fraca control tarefa  --}}

                @foreach ($projecto->equipaProjecto as $equipa_projeto)
                    <tr>
                        <td>{{ $equipa_projeto->responsavel->nome_responsavel }}</td>
                        <td>{{ $equipa_projeto->responsavel->funcao->designacao }}</td>
                @endforeach
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="3">Não encontrado</td>
            </tr>
        @endif
    </table>


    {{--  <div id="notices">
        <div>Observação:</div>
        <div class="notice">..................................</div>
    </div>  --}}

</body>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap');

    body {
        position: relative;
        width: 50em;
        height: 29.7cm;
        margin: 0;
        color: #555555;
        background: #FFFFFF;
        font-size: 14px;
        font-family: 'Montserrat', sans-serif;
        -webkit-font-smoothing: antialiased;
        text-rendering: optimizeLegibility;

    }

    /*   @font-face {
        font-family: SourceSansPro;
        src: url(SourceSansPro-Regular.ttf);
    } */

    table {
        width: 100%;
    }



    .clearfix:after {
        content: "";
        display: table;
        clear: both;
    }

    header {
        padding: 10px 0;
        margin-bottom: 20px;
        border-bottom: 1px solid #AAAAAA;
    }

    #logo {
        float: left;
        margin-top: 8px;
    }

    #logo img {
        height: 90px;
    }

    #company {
        float: right;
        text-align: right;
        font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif

    }


    #details {
        margin-bottom: 50px;
    }


    h2.name {
        font-size: 1.2em;
        font-weight: bolder;
        margin: 0;
    }


#invoice{
    font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif

}
    #invoice h2 {
        border-bottom: 2px solid #1b2c1c;
        font-size: 2em;
        line-height: 1em;
        font-weight: bold;
        margin: 0 0 20px 0;
        text-align: center;
        {{--  font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif  --}}

    }

    #notices {
        padding-top: 10px;
        padding-left: 6px;
        border-left: 6px solid #000000;
        font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif

    }

    #notices .notice {
        font-size: 1.2em;
    }

    .flex h3 {
        margin-left: 19px;
        font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif

    }

    .flex {
        display: flex;
        flex-wrap: wrap;
        margin: -18px;
    }

    .flex2 {
        float: right;
        margin-right: 100px;
    }

    .rwd-table2 {
        margin: 1em 0;
        font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif

    }

    .rwd-table2 th {
        border: 0.5px solid black;
        border-collapse: collapse;
        border-radius: 3px;
        font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif

    }


    .rwd-table2 td {
        display: block;
    }

    .rwd-table2 td:first-child {
        padding-top: .5em;
    }

    .rwd-table2 td:last-child {
        padding-bottom: .5em;
    }


    .rwd-table2 th,
    .rwd-table2 td {
        text-align: left;
    }

    @media (min-width: 480px) {

        .rwd-table2 th,
        .rwd-table2 td {
            display: table-cell;
            padding: .25em .5em;
        }

        .rwd-table2 th:first-child,
        .rwd-table2 td:first-child {
            padding-left: 0;
        }

        .rwd-table2 th:last-child,
        .rwd-table2 td:last-child {
            padding-right: 0;
        }
    }

    .rwd-table2 {
        background: #ffffff;
        color: rgb(0, 0, 0);
        overflow: hidden;
    }

    .rwd-table2 tr {
        border-color: #46637f;
    
    }

    .rwd-table2 th,
    .rwd-table2 td {
        margin: .5em 1em;
    }

    @media (min-width: 480px) {

        .rwd-table2 th

        /* .rwd-table2 td */
            {
            padding: 1em !important;
        }

        .rwd-table2 td {
            padding-left: 1em !important;
        }
    }

    .rwd-table2 th,
    .flex .titulos {
        color: #009688;
        font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif

    }

    .subtitulos {
        color: #000000;
        font-weight: normal;
        font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif

    }

    .flex .titulos {
        font-weight: bold;
        margin-left: 20px;
        margin-bottom: 12px;
    }

    .respon {
        margin-top: 50px;
        text-align: center;
        font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif ;

    }
</style>

</html>
