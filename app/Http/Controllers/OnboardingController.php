<?php

namespace App\Http\Controllers;

use App\Core\Onboarding\Interfaces\OnboardingServiceInterface;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    protected $onboardingService;

    public function __construct(OnboardingServiceInterface $onboardingService)
    {
        $this->onboardingService = $onboardingService;
    }

    public function create()
    {
        return view('onboarding.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'resume_link' => 'nullable|url',
            'cover_letter' => 'nullable|string',
        ]);

        $data = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'credentials_data' => [
                'resume_link' => $validated['resume_link'] ?? null,
                'cover_letter' => $validated['cover_letter'] ?? null,
            ]
        ];

        $this->onboardingService->submitRequest($data);

        return redirect()->route('onboarding.success');
    }

    public function success()
    {
        return view('onboarding.success');
    }
}
