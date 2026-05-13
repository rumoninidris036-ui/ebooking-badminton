<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Services\CourtService;
use Illuminate\Contracts\View\View;

class LandingPageController extends Controller
{
    public function __construct(
        protected CourtService $courtService,
    ) {
    }

    public function __invoke(): View
    {
        $featuredCourts = $this->courtService->listPublic(6)->getCollection();

        return view('pages.landing', [
            'featuredCourts' => $featuredCourts,
        ]);
    }
}
