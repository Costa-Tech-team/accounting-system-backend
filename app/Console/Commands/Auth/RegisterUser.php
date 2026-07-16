<?php

namespace App\Console\Commands\Auth;

use App\Mail\WelcomeRegistrationMail;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

#[Signature('app:create-user {name} {email}')]
#[Description('Registra un usuario nuevo')]
class RegisterUser extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');

        $datos = [
            'name' => $name,
            'email' => $email,
        ];

        $reglas = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        ];

        $validador = Validator::make($datos, $reglas);

        if ($validador->fails()) {
            $this->error('¡Error de validación!');

            foreach ($validador->errors()->all() as $error) {
                $this->line(" ❌ {$error}");
            }

            return Command::FAILURE;
        }
        $registrationUrl = URL::temporarySignedRoute(
            'register.complete',
            now()->addHours(24),
            ['email' => $email, 'name' => $name]
        );

        try {
            Mail::to($email)->send(new WelcomeRegistrationMail($name, $registrationUrl));
            $this->info("✉️  Se ha enviado una invitación por correo a {$email} para completar el registro.");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("No se pudo enviar el correo: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
