<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class notificar_tarefa extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

     public $nomeTarefa;
     public $descricao;
     public $data_inicio_real;
     public $tempo_execucao;
     public $data_fim_real;
     public $percentagem;
     public $created_by;
     public $projecto;
     public $estado;
     public $url;

    public function __construct(string $nomeTarefa, string $descricao, string $data_inicio_real, string $tempo_execucao, string $data_fim_real, string $percentagem, string $created_by, string $projecto, string $estado, string $url)
    {
        //
        $this->nomeTarefa = $nomeTarefa;
        $this->descricao = $descricao;
        $this->data_inicio_real = $data_inicio_real;
        $this->tempo_execucao = $tempo_execucao;
        $this->data_fim_real = $data_fim_real;
        $this->percentagem = $percentagem;
        $this->created_by = $created_by;
        $this->projecto = $projecto;
        $this->estado = $estado;
        $this->url = $url;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Atribuição de Tarefa(Mutue Gestão de Projectos)')->markdown('emails.tarefa', [
            'url' => $this->url,
        ]);

    }
}
