<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\StokRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewStokRequest extends Notification implements ShouldQueue
{
    use Queueable;

    public $stokRequest;

    public function __construct(StokRequest $stokRequest)
    {
        $this->stokRequest = $stokRequest;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $r = $this->stokRequest;
        $stokName = $r->stok->nama ?? '—';

        return (new MailMessage)
                    ->subject("Permohonan Stok Baru (#{$r->id})")
                    ->greeting("Hai {$notifiable->name},")
                    ->line("Terdapat permohonan stok baru oleh: {$r->requester_name}")
                    ->line("Stok: {$stokName}")
                    ->line("Kuantiti: {$r->quantity}")
                    ->line("Tujuan: " . \Illuminate\Support\Str::limit($r->purpose, 120))
                    ->action('Lihat Permohonan', url(route('admin.stok.requests.show', $r->id)))
                    ->line('Sila semak dan kemaskini status permohonan.');
    }

    public function toArray($notifiable)
    {
        return [
            'request_id' => $this->stokRequest->id,
            'requester_name' => $this->stokRequest->requester_name,
            'stok_name' => $this->stokRequest->stok->nama ?? null,
            'quantity' => $this->stokRequest->quantity,
            'status' => $this->stokRequest->status,
        ];
    }
}
