<?php

namespace App\Services;

use App\Models\NotificationSetting;
use Illuminate\Support\Collection;

class NotificationSettingsService
{
    public const COLLECTION_COMPLETED = 'collection_completed_recipients';
    public const END_OF_DAY_DOCUMENTS = 'end_of_day_documents_recipients';
    public const TRANSPORT_REPORT = 'transport_report_recipients';

    public function recipientsFor(string $key): array
    {
        return collect(NotificationSetting::query()->where('key', $key)->value('recipients') ?? [])
            ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->map(fn ($email) => strtolower(trim($email)))
            ->unique()
            ->values()
            ->all();
    }

    public function replace(string $key, array $recipients): void
    {
        NotificationSetting::updateOrCreate(
            ['key' => $key],
            ['recipients' => $this->normalise($recipients)->all()]
        );
    }

    public function normalise(array $recipients): Collection
    {
        return collect($recipients)
            ->flatMap(fn ($value) => preg_split('/[\s,;]+/', (string) $value, -1, PREG_SPLIT_NO_EMPTY))
            ->map(fn ($email) => strtolower(trim($email)))
            ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values();
    }
}
