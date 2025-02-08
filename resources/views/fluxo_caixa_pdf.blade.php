<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<link rel="shortcut icon" href="{{ asset('condominio.ico') }}" type="image/x-icon">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fluo de caixa </title>
<style>
  body { font-family: Arial, sans-serif; }
  .invoice-container { width: 100%; margin: auto; padding: 10px; }
  .header { text-align: center; }
  .header img { width: 100px; }
  .company-details, .customer-details, .invoice-details { margin-top: 20px; }
  .invoice-details{text-align: right; padding-right: 20px; margin-top: 20px;}
  .invoice-table { width: 70%; border-collapse: collapse; margin:20px auto; }
  .invoice-table th, .invoice-table td { border: 1px solid #ddd; padding: 8px; text-align: center; }
  .totals { text-align: right; padding-right: 20px; margin-top: 20px; }
  .payment-info { font-size: 0.8em; margin-top: 20px;  border-top:1px solid #ddd; bottom:0; text-align:center; position: fixed; width: 100%;}
  .lista {
    text-align: center;
    font-weight: 900;
    font-size: x-large;
  }
</style>
</head>
<body>

<div class="invoice-container">
<!--   <div class="header">
    <img src="#" alt="Logo da Empresa"/>
  </div> -->

  <div class="company-details">
    Condominio Vida Pacifíca.<br/>
    <br/>
    <p class="lista">Fluxo de Caixa Total </p>

  </div>

  <div class="invoice-details">
    </div>

  <table class="invoice-table">
    <tr>
      <th>Total de Saida.</th>
      <th>Total Pago</th>
      <th>Total por Paga</th>

    </tr>
    <tr  >
      <td>{{$saidas}} kz</td>
      <td>{{$pagamentos}} kz</td>
      <td>{{$ppagar}} kz</td>
    </tr>
  </table>
</div>
<div class="payment-info">
.<br/>

  </div>
</body>
</html>
