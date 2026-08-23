<?php

namespace App\Http\Controllers;

use App\Services\NotificationSettingsService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NotificationSettingsController extends Controller
{
    public function edit(NotificationSettingsService $settings)
    {
        return view('settings.email-notifications', [
            'collectionRecipients' => $settings->recipientsFor(NotificationSettingsService::COLLECTION_COMPLETED),
            'endOfDayRecipients' => $settings->recipientsFor(NotificationSettingsService::END_OF_DAY_DOCUMENTS),
            'transportRecipients' => $settings->recipientsFor(NotificationSettingsService::TRANSPORT_REPORT),
        ]);
    }

    public function update(Request $request, NotificationSettingsService $settings)
    {
        $validated = $request->validate([
            'collection_completed_recipients' => ['nullable', 'string'],
            'end_of_day_documents_recipients' => ['nullable', 'string'],
            'transport_report_recipients' => ['nullable', 'string'],
        ]);

        foreach ([
            NotificationSettingsService::COLLECTION_COMPLETED,
            NotificationSettingsService::END_OF_DAY_DOCUMENTS,
            NotificationSettingsService::TRANSPORT_REPORT,
        ] as $key) {
            $raw = $validated[$key] ?? '';
            $entered = collect(preg_split('/[\s,;]+/', $raw, -1, PREG_SPLIT_NO_EMPTY));
            $normalised = $settings->normalise([$raw]);

            if ($entered->count() !== $normalised->count()) {
                throw ValidationException::withMessages([$key => 'Въведете само валидни email адреси, разделени със запетая, точка и запетая или нов ред.']);
            }

            $settings->replace($key, $normalised->all());
        }

        return back()->with('success', 'Email получателите са запазени.');
    }
}
