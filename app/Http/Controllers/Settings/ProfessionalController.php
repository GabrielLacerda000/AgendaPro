<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Settings\ProfessionalRequest;

class ProfessionalController extends Controller
{
    public function edit(Request $request)
    {
        return Inertia::render('settings/Professional', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfessionalRequest $request)
    {
        $request->user()->update($request->validated());

        return redirect()->route('professional.edit');
    }
}
