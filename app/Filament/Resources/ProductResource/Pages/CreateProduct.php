<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Services\CloudflareImages;
use Filament\Forms\Form;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Livewire\WithFileUploads;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    use WithFileUploads;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (request()->hasFile('image_local')) {
            $service = app(CloudflareImages::class);
            $result = $service->upload(request()->file('image_local'));
            $data['cf_image_id'] = $result['id'] ?? null;
            $data['image_url'] = $result['url'] ?? null;
        }

        return $data;
    }
}
