<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <title>Document</title>
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
<div id="details" class="clearfix">

    <div id="invoice">
        <h1>Relatório de Projecto</h1>
        <div class="date">Data de Inicio:</div>
        <div class="date">Fim de Inicio:</div>
    </div>
</div>
<table id="customers">
    <tr>
        <th>
            <h4>Numero</h4>
        </th>
        <th>
            <h4>Nome do Projecto</h4>
        </th>
        <th>
            <h4>Estado do projecto</h4>
        </th>
    </tr>
    <!-- Este foreach é para percorer cada linha de uma relação um para muitos -->
    @if (count($arquivo_pdf_projecto))
    @foreach ($arquivo_pdf_projecto as $projecto)
    <tr>
        <td>{{ $projecto->id}}</td>
        <td>{{ $projecto->nome_proj }}</td>

        <td>
            <!-- Aqui é acesso direito para a relação, sem necessidade de percorrer novamente -->
            {{$projecto->estadoProjeto->designacao}}

    </tr>
    <!-- Usamos este foreach porque estamos mediante a uma relação muitos para muito, uma relação muitos para muitos usa-se dois ciclos -->

    {{-- @foreach($projecto->equipaProjecto as $equipa_projeto)
                       

                    @endforeach
                 --}}
    @endforeach
    @else
    <tr>
        <td colspan="3">Não encontrado</td>
    </tr>
    @endif
    <br />
</table>
<table id="customers" border="0" cellspacing="0" cellpadding="0">
    <tr>

        <th>
            <h4>Prioridade do projecto</h4>
        </th>
        <th>
            <h4>Data de inicio do Projecto</h4>
        </th>
        <th>
            <h4>Data final do Projecto</h4>
        </th>
    </tr>
    <!-- Este foreach é para percorer cada linha de uma relação um para muitos -->
    @if (count($arquivo_pdf_projecto))
    @foreach ($arquivo_pdf_projecto as $projecto)
    <tr>
        <td>{{ $projecto->prioridade_proj }}</td>
        <td>{{ $projecto->dat_inicio_real }}</td>
        <td>{{ $projecto->dat_fim_real }}</td>
    </tr>



    <!-- Usamos este foreach porque estamo mediante a uma relação muitos para muito, uma relação muitos para muitos usa-se dois ciclos -->
    {{-- <!-- <td>{{ $projecto->responsavel_id }}</td> --> --}}
    {{-- @foreach($projecto->equipaProjecto as $equipa_projeto)
                       
                   @endforeach
                 --}}
    @endforeach
    @else
    <tr>
        <td colspan="3">No user Found</td>
    </tr>
    @endif

</table>

<table id="customers">
    <tr>
        <th>
            <h4>Membros da Equipa</h4>
        </th>
    </tr>
    @if (count($arquivo_pdf_projecto))
    @foreach ($arquivo_pdf_projecto as $projecto)
    {{-- <!-- <td>{{ $projecto->responsavel_id }}</td> --> --}}

    @foreach($projecto->equipaProjecto as $equipa_projeto)

    <tr>
        <td>{{$equipa_projeto->responsavel->nome_responsavel}}</td>
    </tr>
    @endforeach

    @endforeach
    @else
    <tr>
        <td colspan="3">Não encontrado</td>
    </tr>
    @endif
    </tr>
        
</table>
<!-- <table id="customers" class=bordered>
        <tr>
            <th><h4>Estatistica da alteração das</h4></th>
        </tr>
            @if (count($arquivo_pdf_projecto))
            @foreach ($arquivo_pdf_projecto as $projecto)
             {{--  <!-- <td>{{ $projecto->responsavel_id }}</td> --> --}}

@foreach($projecto->equipaProjecto as $equipa_projeto)

<tr>
    <td>{{$equipa_projeto->responsavel->nome_responsavel}}</td>
</tr>
@endforeach

@endforeach

@else
<tr>
    <td colspan="3">No user Found</td>
</tr>
@endif
</tr>

    </table> -->
<div id="notices">
    <div>Observação:</div>
    <div class="notice">........................</div>
</div>
</body>

<style>
    #customers {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }


    #customers tr:nth-child(even) {
        background-color: #e6e6e6;
    }

    #customers th {
        padding: 0px 0px 0px 20px;

        text-align: left;
        background-color: #04896D;
        color: white;
    }

</style>
<style>
    @font-face {
        font-family: SourceSansPro;
        src: url(SourceSansPro-Regular.ttf);
    }

    .clearfix:after {
        content: "";
        display: table;
        clear: both;
    }

    body {
        position: relative;
        width: 50em;
        height: 29.7cm;
        margin: 0;
        color: #555555;
        background: #FFFFFF;
        font-family: Arial, sans-serif;
        font-size: 14px;
        font-family: SourceSansPro;
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
    }


    #details {
        margin-bottom: 50px;
    }

    #client {
        padding-left: 6px;
        border-left: 6px solid #db3311;
        float: left;
    }

    #client .to {
        color: #777777;
    }

    h2.name {
        font-size: 1.4em;
        font-weight: normal;
        margin: 0;
    }



    #invoice h1 {
        border-left: 6px solid #04896D;
        font-size: 2.6em;
        line-height: 1em;
        font-weight: normal;
        margin: 0 0 10px 0;
    }

    #invoice .date {
        font-size: 1.3em;
        color: #777777;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
    }

    table th,
    table td {
        padding: 20px;
        background: #EEEEEE;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;
    }

    table th {
        white-space: nowrap;
        font-weight: normal;
    }

    table td {
        text-align: left;
    }

    table td h3 {
        color: #db3311;
        font-size: 1.2em;
        font-weight: normal;
        margin: 0 0 0.2em 0;
    }

    table .no {
        color: #FFFFFF;
        font-size: 1.6em;
        background: #db3311;
    }

    table .desc {
        text-align: left;
    }

    table .unit {
        background: #DDDDDD;
    }

    table .qty {}

    table .total {
        background: #db3311;
        color: #FFFFFF;
    }

    table td.unit,
    table td.qty,
    table td.total {
        font-size: 1.2em;
    }

    table tbody tr:last-child td {
        border: none;
    }

    table tfoot td {
        padding: 10px 20px;
        background: #FFFFFF;
        border-bottom: none;
        font-size: 1.2em;
        white-space: nowrap;
        border-top: 1px solid #AAAAAA;
    }

    table tfoot tr:first-child td {
        border-top: none;
    }

    table tfoot tr:last-child td {
        color: #db3311;
        font-size: 1.4em;
        border-top: 1px solid #db3311;

    }

    table tfoot tr td:first-child {
        border: none;
    }

    #thanks {
        font-size: 2em;
        margin-bottom: 50px;
    }

    #notices {
        padding-top: 10px;
        padding-left: 6px;
        border-left: 6px solid #04896D;
    }

    #notices .notice {
        font-size: 1.2em;
    }

    footer {
        color: #777777;
        width: 100%;
        height: 30px;
        position: absolute;
        bottom: 0;
        border-top: 1px solid #AAAAAA;
        padding: 8px 0;
        text-align: center;
    }

</style>

</html>
