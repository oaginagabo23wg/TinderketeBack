<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ContactMail extends Mailable
{
    public $subject = 'Nuevo mensaje de contacto'; // Asunto del correo
    public $data; // Datos accesibles en la vista

    /**
     * Constructor de la clase ContactMail.
     *
     * @param array $data Datos pasados al mailable (name, email, message, etc.)
     */
    public function __construct(array $data)
    {
        $this->data = $data; // Almacenar los datos recibidos
    }

    /**
     * Construir el mensaje de correo.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->data['email'], $this->data['name'] ?? 'Usuario') // Configurar remitente
                    ->subject($this->subject) // Configurar el asunto
                    ->view('emails.contact') // Usar la vista contact.blade.php
                    ->with('data', $this->data); // Pasar los datos a la vista
    }
}
