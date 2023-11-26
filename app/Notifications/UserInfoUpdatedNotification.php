<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserInfoUpdatedNotification extends Notification
{
    use Queueable;
    /**
     * @var App\Models\User
     */
    private $user;

    /**
     * @var array
     */
    private $updated;

    private $translations = [
        "username" => "Nom d'utilisateur",
        "email" => "Adresse e-mail",
        "first_name" => "Prénom",
        "last_name" => "Nom de famille",
        "phone" => "N° de téléphone",
        "role" => "Rôle",
        "gender" => "Genre",
        "registration_number" => "Matricule",
        "agencies" => "Agence / DRE",
    ];

    /**
     * Create a new notification instance.
     * @param App\Models\User $user
     * @param array $updated
     * @return void
     */
    public function __construct(User $user, array $updated)
    {
        $this->user = $user;
        $this->updated = $updated;
    }

    /**
     * Get email body
     *
     * @return array
     */
    private function getContent(): array
    {
        $role = $this->user->username == 'DGA' ? 'Directeur Général Adjoint' : $this->user->role->name;

        $ligns = [
            'Nous vous informons que les informations de votre compte (Profil : ' . $role . ') ont été modifiées avec succès.',
            'Vos informations modifiées :',
        ];
        foreach ($this->updated as $key => $values) {
            $oldValue = $values['oldValue'];
            $newValue = $values['newValue'];
            $translations = $this->translations[$key];
            // if (is_array($key) || is_array($values)) {
            //     dd($key);
            // }
            array_push($ligns, ' - ' . $translations . ' : ' . $oldValue . ' => ' . $newValue);
        }
        dd($ligns);
        return $ligns;
    }

    /**
     * Get email subject
     *
     * @return string
     */
    private function getTitle(): string
    {
        return 'CREATION DE VOTRE COMPTE - PLATEFROME TASKS MANAGER';
    }

    /**
     * Get action url
     *
     * @return string
     */
    private function getUrl(): string
    {
        return env('APP_URL');
    }
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if (app()->environment('production')) {
            return ['mail', 'database'];
        }
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->greeting(' ')
            ->subject($this->getTitle())
            ->lines($this->getContent());
        $mail = $mail->action('Se connecter', $this->getUrl())
            ->success();
        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->user->id,
            'url' => $this->getUrl(),
            'content' => $this->getContent(),
            'title' => $this->getTitle(),
            'emitted_by' => auth()->user()->username,
        ];
    }
}
