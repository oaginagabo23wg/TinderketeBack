<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ContactMail extends Mailable
{
    public $subject = 'Mezu berria'; // Asunto del correo
    public $data; // Datos accesibles en la vista

    /**
     * Constructor de la clase ContactMail.
     *
     * @param array $data Datos del formulario (name, email, message, etc.)
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Construir el mensaje de correo.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->subject;
        
        // Si el user_id está presente, puedes incluirlo en el asunto o en el cuerpo
        if (isset($this->data['user_id'])) {
            $subject .= ' (Usuario ID: ' . $this->data['user_id'] . ')';
        }

        return $this->from('noreply@tinderkete.com', 'Formulario de Contacto') // Dirección genérica
                    ->replyTo($this->data['email'], $this->data['name'] ?? 'Usuario') // Configurar el reply-to
                    ->subject($subject) // Asunto con el user_id si está presente
                    ->view('emails.contact')
                    ->with('data', $this->data);
    }
}
