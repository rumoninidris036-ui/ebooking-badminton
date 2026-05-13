<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Http\Requests\Court\StoreCourtRequest;
use App\Http\Requests\Court\UpdateCourtRequest;
use App\Services\CourtService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CourtPageController extends Controller
{
    public function __construct(
        protected CourtService $courtService,
    ) {
    }

    public function index(Request $request): View
    {
        return view('pages.courts.index', [
            'courts' => $this->courtService->listForOwner($request->user(), 12),
        ]);
    }

    public function create(): View
    {
        return view('pages.courts.create', [
            'days' => $this->courtService->days(),
        ]);
    }

    public function store(StoreCourtRequest $request): RedirectResponse
    {
        $this->courtService->create($request->validated(), $request->user());

        return redirect()
            ->route('courts.index')
            ->with('status', 'Court created successfully.');
    }

    public function edit(Request $request, int $court): View
    {
        return view('pages.courts.edit', [
            'court' => $this->courtService->findOwnedOrFail($court, $request->user()),
            'days' => $this->courtService->days(),
        ]);
    }

    public function update(UpdateCourtRequest $request, int $court): RedirectResponse
    {
        $courtModel = $this->courtService->findOwnedOrFail($court, $request->user());
        $this->courtService->update($courtModel, $request->validated(), $request->user());

        return redirect()
            ->route('courts.index')
            ->with('status', 'Court updated successfully.');
    }

    public function destroy(Request $request, int $court): RedirectResponse
    {
        $courtModel = $this->courtService->findOwnedOrFail($court, $request->user());
        $this->courtService->delete($courtModel, $request->user());

        return redirect()
            ->route('courts.index')
            ->with('status', 'Court deleted successfully.');
    }
}
