<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<link rel="shortcut icon" href="{{ asset('condominio.ico') }}" type="image/x-icon">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Factura </title>
<style>
  body { font-family: Arial, sans-serif; }
  .invoice-container { width: 100%; margin: auto; border: 1px solid #000; padding: 10px; }
  .header { text-align: center; }
  .header img { width: 100px; }
  .company-details, .customer-details, .invoice-details { margin-top: 20px; }
  .invoice-details{text-align: right; padding-right: 20px; margin-top: 20px;}
  .invoice-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
  .invoice-table th, .invoice-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
  .invoice-table th { background-color: #f2f2f2; }
  .totals { text-align: right; padding-right: 20px; margin-top: 20px; }
  .payment-info { font-size: 0.8em; margin-top: 20px;  border-top:1px solid #ddd; bottom:0; text-align:center; position: fixed; width: 100%;}
</style>
</head>
<body>

<div class="invoice-container">
<!--   <div class="header">
    <img src="#" alt="Logo da Empresa"/>
  </div> -->

  <div class="company-details">
    Condominio Vida Pacifíca.<br/>
    A3755 Andalucia<br/>
  </div>

  <div class="customer-details">
    Utilizador: <i>{{$factura->pessoa->nome_pessoa}}<i> {{$factura->pessoa->sobre_nome_pessoa}}<br/>
    Apartamento: {{$factura->apartamento->designacao}}<br/>
  </div>

  <div class="invoice-details">
    Nº de fatura: {{$factura->faturaReference}}<br/>
    Data de emissão: {{$factura->created_at}}<br/>
    </div>

  <table class="invoice-table">
    <tr>
      <th>CANT.</th>
      <th>DESCRIÇÃO</th>
      <th>PREÇO UN</th>
      <th>QTY</th>
      <th>TOTAL</th>
    </tr>
    @foreach($factura->factura_itens as $factura_item)
    <tr  >
      <td>{{$loop->iteration}}</td>
      <td>{{$factura_item->designacao}}</td>
      <td>{{$factura_item->preco}}</td>
      <td>{{$factura_item->quantidade}}</td>
      <td>{{$factura_item->total}}</td>
    </tr>
    @endforeach
  </table>
  Obs:{{$factura->observacao}}

  <div class="totals">
    Subtotal: {{$factura->total_preco_factura}}<br/>
    IVA 14%:{{$factura->total_iva}}<br/>
    TOTAL: {{$factura->valor_a_pagar}} 
  </div>

</div>
<div class="payment-info">
.<br/>

  </div>
</body>
</html>
