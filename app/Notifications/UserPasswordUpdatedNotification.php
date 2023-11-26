<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserPasswordUpdatedNotification extends Notification
{
    use Queueable;
    /**
     * @var App\Models\User
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * Create a new notification instance.
     * @param App\Models\User $user
     * @param string $password
     * @return void
     */
    public function __construct(User $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
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
            'Nous vous informons que le mot de passe de votre compte (Profil : ' . $role . ') a été réinitialisé avec succès.',
            'Voici vos informations d\'authentification :',
            ' - Adresse e-mail : ' . $this->user->email,
            ' - Nom d\'utilisateur : ' . $this->user->username,
            ' - Mot de passe : ' . $this->password,
            'Vous avez la possibilité de vous connecter en utilisant soit votre adresse e-mail ou nom d\'utilisateur.',
            'Veuillez noter qu\'il est obligatoire de changer votre mot de passe lors de votre prochaine connexion.',
            'Garder votre mot de passe confidentiel pour assurer la sécurité de votre compte. Nous ne pourrons être tenus responsables de tout accès non autorisé résultant d\'informations de connexion partagées.',
            'Pour une meilleure expérience d\'utilisation de la plateforme TASKS MANAGER, nous vous recommandons d\'utiliser un navigateur web tel que Microsoft Edge ou Google Chrome',
            'Pour toutes difficultés ou informations complémentaires, il y a lieu de se rapprocher de la Direction du contrôle permanent (D.C.P)',
        ];
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
        return (new MailMessage)
            ->greeting(' ')
            ->subject($this->getTitle())
            ->line($this->getContent()[0])
            ->line($this->getContent()[1])
            ->line($this->getContent()[2])
            ->line($this->getContent()[3])
            ->line($this->getContent()[4])
            ->line($this->getContent()[5])
            ->line($this->getContent()[6])
            ->line($this->getContent()[7])
            ->line($this->getContent()[8])
            ->line($this->getContent()[9])
            ->action('Se connecter', $this->getUrl())
            // ->line('Merci d\'utiliser Skaalab Test!')
            ->success();
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