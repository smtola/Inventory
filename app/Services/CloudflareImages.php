<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class CloudflareImages
{
    public function upload(UploadedFile $file): array
    {
        $accountId = config('services.cloudflare_images.account_id');
        $token = config('services.cloudflare_images.api_token');
        if (! $accountId || ! $token) {
            throw new \RuntimeException('Cloudflare Images not configured');
        }

        $response = Http::withToken($token)
            ->asMultipart()
            ->post("https://api.cloudflare.com/client/v4/accounts/{$accountId}/images/v1", [
                [
                    'name' => 'file',
                    'contents' => fopen($file->getRealPath(), 'r'),
                    'filename' => $file->getClientOriginalName(),
                ],
            ]);

        if (! $response->ok() || ! ($response['success'] ?? false)) {
            throw new \RuntimeException('Cloudflare upload failed: ' . $response->body());
        }

        $id = $response['result']['id'] ?? null;
        $variants = $response['result']['variants'] ?? [];
        $deliveryBase = config('services.cloudflare_images.delivery_base');
        $variant = env('CLOUDFLARE_IMAGES_DELIVERY_VARIANT', 'public');
        $url = $deliveryBase && $id ? rtrim($deliveryBase, '/') . '/' . $id . '/' . $variant : ($variants[0] ?? null);

        return ['id' => $id, 'url' => $url];
    }
}


