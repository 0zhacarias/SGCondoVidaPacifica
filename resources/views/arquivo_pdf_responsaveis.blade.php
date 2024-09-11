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
    </div>
    <table>
        <tr>
          <th  class="titulos">#</th>
          <th  class="titulos">Nome do Responsavel</th>
          <th class="titulos">E-mail</th>
          <th class="titulos">Numero do BI</th>
          <th class="titulos">Função</th>
          <th class="titulos">Genero</th>
          <th class="titulos">Estado Civil</th>
        </tr>
        @if (count($arquivo_pdf_responsaveis))
            @foreach ($arquivo_pdf_responsaveis as $responsavel)
            <tr>
                <td><span class="subtitulos">{{ $loop->iteration}}</span></td>
                <td><span class="subtitulos">{{ $responsavel->user->name}}</span></td>
                <td><span class="subtitulos">{{ $responsavel->email_responsavel}}</span></td>
                <td><span class="subtitulos">{{ $responsavel->numero_identificacao }}</span></td>
                <td><span class="subtitulos">{{ $responsavel->funcao->designacao}}</span></td>
                <td><span class="subtitulos">{{ $responsavel->genero->designacao }}</span></td>
                <td><span class="subtitulos">{{ $responsavel->estadoCivil->designacao }}</span></td>

              </tr>
            @endforeach
        @endif
        
      </table>
      <div class="rodape">Data de Imprensão: {{ $dataimpressao }}</div>
      
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
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
    .clearfix:after {
        content: "";
        display: table;
        clear: both;
        margin-bottom:6px;
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
    .subtitulos {
        color: #000;
        font-weight: normal;
        font-size: .7rem;
        font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif

    }

 .titulos {
        font-weight: bold;
        color: #009688;
        margin-left: 20px;
        margin-bottom: 12px;
        font-size: .7rem;
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

    .rodape {
        padding-top: 40px;
        padding-left: 6px;
        font-size: .6rem;
        text-align: center;
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




</style>

</html>
