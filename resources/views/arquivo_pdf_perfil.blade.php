<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RELATÓRIO DA TAREFA</title>
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
            <h2>Dados Pessoais</h2>
            {{--  @if (count($arquivo_pdf_tarefa))
                @foreach ($arquivo_pdf_tarefa as $perfil)
                    <div>Data Inicial da tarefa: {{ $perfil->data_inicio_real }}</div>
                    <div>Data Final da tarefa: {{ $perfil->data_fim_real }}</div>
                @endforeach
            @endif  --}}
        </div>
    </div>

    <div class="flex">
        <div class="flex1">
            <div class="flex2">
                {{--  <h4>Dados do Membro <img src="img/project.png" alt="" width="12px"></h4>  --}}
                @if (count($arquivo_pdf_perfil))
                    @foreach ($arquivo_pdf_perfil as $perfil)
                        <div class="titulos">Nome: <span class="subtitulos">{{ $perfil->name }}</span></div>
                        <div class="titulos">Nome de usuario: <span class="subtitulos">{{ $perfil->username}}</span></div>
                       <div class="titulos">Genero: <span class="subtitulos">{{ $perfil->responsavel->genero->designacao  }}</span></div>
                        <div class="titulos">Estado Cívil: <span class="subtitulos">{{ $perfil->responsavel->estadoCivil->designacao }}</span></div>
                        <div class="titulos">Função: <span class="subtitulos">{{ $perfil->responsavel->funcao->designacao}}</span></div>
                            <div class="titulos">E-mail: <span class="subtitulos">{{ $perfil->email }}</span></div>
                            <div class="titulos">Telefone: <span class="subtitulos">{{ $perfil->telefone }}</span></div>
                       
                    @endforeach
                @endif
            </div>
        </div>

    </div>
    </div>


</body>

<style>
    {{--  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap');  --}}

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
    }


    #details {
        margin-bottom: 50px;
    }


    h2.name {
        font-size: 1.4em;
        font-weight: normal;
        margin: 0;
    }



    #invoice h2 {
        border-bottom: 2px solid #1b2c1c;
        font-size: 2em;
        line-height: 1em;
        font-weight: bold;
        margin: 0 0 20px 0;
        text-align: center;
        
    }




    @media (min-width: 480px) {
    }

    @media (min-width: 480px) {

        
        
    }

 

    .subtitulos {
        color: #000000;
        font-weight: normal;
    }

    .flex .titulos {
        font-weight: bold;
        margin-left: 10px;
        margin-bottom: 15px;
    }

</style>

</html>
