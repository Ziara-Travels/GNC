<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Wishlist;

class WishlistItemAdded extends Notification
{
    use Queueable;

    protected $user;
    protected $wishlistItem;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, Wishlist $wishlistItem)
    {
        $this->user = $user;
        $this->wishlistItem = $wishlistItem;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Wishlist Item Added - Ziara Travel')
                    ->line('A user has added a new item to their wishlist.')
                    ->line('User: ' . $this->user->name . ' (' . $this->user->email . ')')
                    ->line('Item: ' . $this->wishlistItem->title)
                    ->line('Type: ' . ucfirst($this->wishlistItem->type))
                    ->line('Added on: ' . $this->wishlistItem->created_at->format('F j, Y \a\t g:i A'))
                    ->action('View Wishlist', url('/dashboard/wishlists'))
                    ->line('This could be a potential lead for conversion.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Wishlist Item Added',
            'message' => $this->user->name . ' added "' . $this->wishlistItem->title . '" to their wishlist',
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
            'wishlist_item_id' => $this->wishlistItem->id,
            'item_title' => $this->wishlistItem->title,
            'item_type' => $this->wishlistItem->type,
            'item_price' => $this->wishlistItem->price,
            'currency' => $this->wishlistItem->currency ?? 'KES',
            'created_at' => $this->wishlistItem->created_at->toISOString(),
            'action_url' => url('/dashboard/wishlists'),
            'icon' => 'heart',
            'color' => 'blue'
        ];
    }
}
