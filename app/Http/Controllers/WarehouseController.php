<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\WarehouseTransaction;
use App\Services\WarehouseInventoryService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WarehouseController extends Controller
{
    public function __construct(private WarehouseInventoryService $inventory)
    {
    }

    public function index()
    {
        $collections = Collection::with('user')
            ->get()
            ->map(fn (Collection $collection) => [
                'date' => Carbon::parse($collection->collection_date),
                'created_at' => $collection->created_at,
                'type' => 'in',
                'liters' => (float) $collection->liters,
                'driver' => $collection->user?->name,
                'buyer' => null,
                'reference' => 'Събиране #' . $collection->id,
            ]);

        $transactions = WarehouseTransaction::with('user')
            ->get()
            ->map(fn (WarehouseTransaction $transaction) => [
                'date' => Carbon::parse($transaction->date),
                'created_at' => $transaction->created_at,
                'type' => 'out',
                'liters' => (float) $transaction->quantity,
                'driver' => $transaction->user?->name,
                'buyer' => $transaction->buyer,
                'reference' => $transaction->document_number ?: 'Транзакция #' . $transaction->id,
                'transaction' => $transaction,
            ]);

        $movements = $collections
            ->concat($transactions)
            ->sortBy(fn (array $movement) => $movement['date']->format('Y-m-d') . '-' . ($movement['created_at']?->format('YmdHis') ?? ''))
            ->values();

        $remainingStock = 0;
        $movements = $movements->map(function (array $movement) use (&$remainingStock) {
            $remainingStock += $movement['type'] === 'in'
                ? $movement['liters']
                : -$movement['liters'];

            $movement['remaining_stock'] = $remainingStock;

            return $movement;
        })->reverse()->values();

        $currentStock = $this->inventory->currentStock();

        return view('warehouse.index', compact('currentStock', 'movements'));
    }

    public function create()
    {
        return view('warehouse.create', [
            'currentStock' => $this->inventory->currentStock(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateTransaction($request);

        DB::transaction(function () use ($validated) {
            $currentStock = $this->inventory->currentStock();

            $this->ensureQuantityAvailable($validated['quantity'], $currentStock);

            WarehouseTransaction::create([
                ...$validated,
                'user_id' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('warehouse.index')
            ->with('success', 'Изходящата складова транзакция беше записана.');
    }

    public function edit(WarehouseTransaction $warehouseTransaction)
    {
        return view('warehouse.edit', [
            'warehouseTransaction' => $warehouseTransaction,
            'availableStock' => $this->inventory->currentStock() + (float) $warehouseTransaction->quantity,
        ]);
    }

    public function update(Request $request, WarehouseTransaction $warehouseTransaction)
    {
        $validated = $this->validateTransaction($request);

        DB::transaction(function () use ($validated, $warehouseTransaction) {
            $availableStock = $this->inventory->currentStock() + (float) $warehouseTransaction->quantity;

            $this->ensureQuantityAvailable($validated['quantity'], $availableStock);

            $warehouseTransaction->update($validated);
        });

        return redirect()
            ->route('warehouse.index')
            ->with('success', 'Складовата транзакция беше обновена.');
    }

    public function destroy(WarehouseTransaction $warehouseTransaction)
    {
        $warehouseTransaction->delete();

        return redirect()
            ->route('warehouse.index')
            ->with('success', 'Складовата транзакция беше изтрита.');
    }

    public function report()
    {
        $from = now()->startOfMonth();
        $to = now()->endOfMonth();
        $openingStock = $this->inventory->collectedBetween('1900-01-01', $from->copy()->subDay())
            - $this->inventory->recycledBetween('1900-01-01', $from->copy()->subDay());
        $collected = $this->inventory->collectedBetween($from, $to);
        $recycled = $this->inventory->recycledBetween($from, $to);
        $closingStock = $openingStock + $collected - $recycled;

        return Pdf::loadView('warehouse.report-pdf', compact(
            'from',
            'to',
            'openingStock',
            'collected',
            'recycled',
            'closingStock'
        ))->setPaper('a4')->stream('warehouse-report-' . $from->format('Y-m') . '.pdf');
    }

    private function validateTransaction(Request $request): array
    {
        return $request->validate([
            'date' => 'required|date',
            'quantity' => 'required|numeric|gt:0',
            'buyer' => 'required|string|max:255',
            'document_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
    }

    private function ensureQuantityAvailable(float $quantity, float $availableStock): void
    {
        if ($quantity > $availableStock + 0.00001) {
            throw ValidationException::withMessages([
                'quantity' => 'Наличното количество в склада е ' . number_format($availableStock, 2) . ' литра.',
            ]);
        }
    }
}
