<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Contributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContributorController extends Controller
{
    /**
     * Display contributors for an event
     */
    public function index(Event $event)
    {
        $contributors = $event->contributors()
            ->latest()
            ->paginate(20);

        return view('contributors.index', compact(
            'event',
            'contributors'
        ));
    }

    /**
     * Show create form
     */
    public function create(Event $event)
    {
        return view('contributors.create', compact('event'));
    }

    /**
     * Store contributor
     */
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'promised_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        Contributor::create([
            'event_id' => $event->id,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'promised_amount' => $validated['promised_amount'],
            'paid_amount' => 0,
            'remaining_amount' => $validated['promised_amount'],
            'status' => Contributor::STATUS_PENDING,
            'registration_method' => Contributor::REGISTRATION_MANUAL,
            'notes' => $validated['notes'] ?? null,
            'registered_at' => now(),
        ]);

        return redirect()
            ->route('contributors.index', $event)
            ->with('success', 'Mchangiaji ameongezwa kikamilifu.');
    }

    /**
     * Show contributor
     */
    public function show(Event $event, Contributor $contributor)
    {
        return view('contributors.show', compact(
            'event',
            'contributor'
        ));
    }

    /**
     * Edit contributor
     */
    public function edit(Event $event, Contributor $contributor)
    {
        return view('contributors.edit', compact(
            'event',
            'contributor'
        ));
    }

    /**
     * Update contributor
     */
    public function update(Request $request, Event $event, Contributor $contributor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'promised_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $contributor->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'promised_amount' => $validated['promised_amount'],
            'remaining_amount' => max(
                0,
                $validated['promised_amount'] - $contributor->paid_amount
            ),
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('contributors.index', $event)
            ->with('success', 'Taarifa za mchangiaji zimehaririwa.');
    }

    /**
     * Delete contributor
     */
    public function destroy(Event $event, Contributor $contributor)
    {
        DB::transaction(function () use ($contributor) {
            $contributor->contributions()->delete();
            $contributor->delete();
        });

        return redirect()
            ->route('contributors.index', $event)
            ->with('success', 'Mchangiaji amefutwa.');
    }
}