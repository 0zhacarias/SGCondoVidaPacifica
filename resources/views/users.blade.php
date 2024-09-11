<!DOCTYPE html>
<html>

<head>
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

        a {
            color: #0087C3;
            text-decoration: none;
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
            height: 70px;
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

        #invoice {
            float: right;
            text-align: right;
        }

        #invoice h1 {
            color: #db3311;
            font-size: 2.4em;
            line-height: 1em;
            font-weight: normal;
            margin: 0 0 10px 0;
        }

        #invoice .date {
            font-size: 1.1em;
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
            text-align: right;
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
            padding-left: 6px;
            border-left: 6px solid #db3311;
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
</head>

<body>
    <header class="clearfix">
        <div id="logo">
            <img src="../img/person.png">
        </div>
        <div id="company">
            <h2 class="name">Mutue Soluções tecnologias inteligentes</h2>
            <div>Rua Nossa Senhora da Muxima, Kinaxixi</div>
            <div>Luanda-Angola</div>
            <div>(+244)922 969 192</div>
            <div>geral@mutue.net</div>
        </div>
        </div>
    </header>
    <main>
        <div id="details" class="clearfix">
            <div id="client">
                <div class="to">Relatório do: --Porjecto--</div>
                <h2 class="name">--Name--</h2>
                <div class="address">Estado do Projecto:</div>
                <div class="email">Prioridade do Projecto:</div>
            </div>
            <div id="invoice">
                <h1>Relatório de Projecto</h1>
                <div class="date">Data de Inicio:</div>
                <div class="date">Fim de Inicio:</div>
            </div>
        </div>
        <table border="0" cellspacing="0" cellpadding="0">
            <thead>
            </thead>
            <tbody id="invoiceItems">
                <tr>
                    <th class="no"> Descricão do Projecto</th>
                    <td class="desc">.........................</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td style="text-align: center;">Responsável</td>
                    <td style="text-align: center;"> Função </td>
                </tr>
                <tr>
                    <td></td>
                    <td>--Nome do Responsável--</td>
                    <td> --Função do responsável-- </td>
                </tr>
            </tfoot>
        </table>
        <div id="thanks">Thank you!</div>
        <div id="notices">
            <div>Observação:</div>
            <div class="notice">........................</div>
        </div>
    </main>
</body>

</html>
