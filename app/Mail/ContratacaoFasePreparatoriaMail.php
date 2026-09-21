<?php

namespace App\Mail;

use App\Models\Pca;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContratacaoFasePreparatoriaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Pca $pca,
        public mixed $demandante
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Aviso: Contratação {$this->pca->codigoUASG}-{$this->pca->numeroContratacao} iniciou a Fase Preparatória",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contratacao-fase-preparatoria',
        );
    }
}