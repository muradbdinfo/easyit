<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    use Queueable;

    public function __construct(public Comment $comment) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $post = $this->comment->post;

        return (new MailMessage)
            ->subject("New Comment on: {$post->title}")
            ->greeting('New Blog Comment')
            ->line("**{$this->comment->display_name}** commented on \"{$post->title}\":")
            ->line('"' . \Str::limit($this->comment->body, 150) . '"')
            ->action('Review Comment', url("/admin/comments/{$this->comment->id}"))
            ->line('This comment requires your approval.');
    }

    public function toArray($notifiable): array
    {
        return [
            'comment_id' => $this->comment->id,
            'post_id' => $this->comment->post_id,
            'post_title' => $this->comment->post->title,
            'author' => $this->comment->display_name,
            'excerpt' => \Str::limit($this->comment->body, 80),
        ];
    }
}
