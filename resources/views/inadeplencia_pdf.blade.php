<style>
  body {
    font-family: Arial, sans-serif;
  }

  .invoice-container {
    width: 100%;
    margin: auto;
    padding: 10px;
  }

  .header {
    text-align: center;
  }

  .header img {
    width: 100px;
  }

  .company-details,
  .customer-details,
  .invoice-details {
    margin-top: 20px;
  }

  .lista {
    text-align: center;
    font-weight: 900;
    font-size: x-large;
  }
</style>
<div class="invoice-container">
  <div class="company-details">
    Condominio Vida Pacifíca.<br />
    <br />
    <p class="lista"> Lista de devedores de serviços do condomino </p>
  </div>

  <table style="width: 100%; border-collapse: collapse; text-align: center;" border="1">
    <thead>
      <tr>
        <th>code</th>
        <th>Nome do Cliente</th>
        <th>Serviço</th>
        <th>Janeiro</th>
        <th>Fevereiro</th>
        <th>Março</th>
        <th>Abril</th>
        <th>Maio</th>
        <th>Junho</th>
        <th>Julho</th>
        <th>Agosto</th>
        <th>Setembro</th>
        <th>Outubro</th>
        <th>Novembro</th>
        <th>Dezembro</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($variavel as $item)
      @php
      $primeiraLinha = true;
      @endphp
      @foreach ($item['servicos'] as $servico)
      <tr>
        @if ($primeiraLinha)
        <td rowspan="{{ count($item['servicos']) }}">{{ $item['condomino_id'] }}</td>
        <td rowspan="{{ count($item['servicos']) }}">{{ $item['nome'] }}</td>
        @php $primeiraLinha = false; @endphp
        @endif
        <td>{{ $servico['servico'] }}</td>
        <td>{{ $servico['Janeiro'] }}</td>
        <td>{{ $servico['Fevereiro'] }}</td>
        <td>{{ $servico['Março'] }}</td>
        <td>{{ $servico['Abril'] }}</td>
        <td>{{ $servico['Maio'] }}</td>
        <td>{{ $servico['Junho'] }}</td>
        <td>{{ $servico['Julho'] }}</td>
        <td>{{ $servico['Agosto'] }}</td>
        <td>{{ $servico['Setembro'] }}</td>
        <td>{{ $servico['Outubro'] }}</td>
        <td>{{ $servico['Novembro'] }}</td>
        <td>{{ $servico['Dezembro'] }}</td>
      </tr>
      @endforeach
      @if (empty($item['servicos']))
      <tr>
        <td>{{ $item['condomino_id'] }}</td>
        <td>{{ $item['nome'] }}</td>
        <td colspan="13">Nenhum serviço</td>
      </tr>
      @endif
      @endforeach
    </tbody>
  </table>
</div>