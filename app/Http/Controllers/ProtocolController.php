<?php

namespace App\Http\Controllers;

use App\Mail\WasteCollectionProtocolMail;
use App\Models\Protocol;
use App\Services\ProtocolService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ProtocolController extends Controller
{
    public function index(Request $request)
    {
        $protocols = Protocol::with(['client', 'user', 'collection'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->whereHas('client', fn ($clients) => $clients
                    ->where('company_name', 'like', '%' . $request->search . '%')
                    ->orWhere('bulstat', 'like', '%' . $request->search . '%'));
            })
            ->when($request->filled('driver'), fn ($query) => $query->whereHas('user', fn ($users) => $users->where('name', 'like', '%' . $request->driver . '%')))
            ->when($request->filled('date'), fn ($query) => $query->whereHas('collection', fn ($collections) => $collections->whereDate('collection_date', $request->date)))
            ->latest()->paginate(20)->withQueryString();

        return view('protocols.index', compact('protocols'));
    }

    public function preview(Protocol $protocol, ProtocolService $service)
    {
        $protocol = $this->regenerateIfMissing($protocol, $service);
        return response(Storage::disk('public')->get($protocol->pdf_path), 200, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline; filename="protocol-' . $protocol->id . '.pdf"']);
    }

    public function download(Protocol $protocol, ProtocolService $service)
    {
        $protocol = $this->regenerateIfMissing($protocol, $service);
        return Storage::disk('public')->download($protocol->pdf_path, 'protocol-' . $protocol->id . '.pdf');
    }

    public function resend(Protocol $protocol, ProtocolService $service)
    {
        $protocol = $service->generate($protocol->collection);
        Mail::to('bul_ros_group@abv.bg')->send(new WasteCollectionProtocolMail($protocol));
        $protocol->email_sent_to_owner = true;
        if ($protocol->client->email) {
            Mail::to($protocol->client->email)->send(new WasteCollectionProtocolMail($protocol, true));
            $protocol->email_sent_to_client = true;
        }
        $protocol->sent_at = now();
        $protocol->save();

        return back()->with('success', 'Протоколът беше изпратен повторно.');
    }

    public function destroy(Protocol $protocol)
    {
        Storage::disk('public')->delete($protocol->pdf_path);
        $protocol->delete();

        return back()->with('success', 'Протоколът беше изтрит.');
    }

    private function regenerateIfMissing(Protocol $protocol, ProtocolService $service): Protocol
    {
        return Storage::disk('public')->exists($protocol->pdf_path)
            ? $protocol
            : $service->generate($protocol->collection);
    }
}
