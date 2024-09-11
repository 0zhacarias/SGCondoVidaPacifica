@component('mail::message')
    Foi-lhe atribuido uma tarefa ao sistema, com os seguintes dados:
    <p>Nome: {{ $nomeTarefa }}</p>
    <p>Descrição: {{ $descricao }}</p>
    <p>Tempo execução: {{ $tempo_execucao }} dias</p>
    <p>Percentagem: {{ $percentagem }}%</p>
    <p>Projecto: {{ $projecto }}</p>
    <p>Estado: {{ $estado }}</p>
    <p>Data inicio real: {{ $data_inicio_real }}</p>
    <p>Data fim real: {{ $data_fim_real }}</p>
    @component('mail::button', ['url' => $url])
        Visualizar Tarefa
    @endcomponent
    Atenciosamente,<br>
    {{ $created_by }} ({{ config('app.name') }})
@endcomponent
