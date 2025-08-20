<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index()
    {
        $professional = auth()->user()->professional;
        
        if (!$professional) {
            return redirect()->route('settings.professional')->with('error', 'You need to complete your professional profile first.');
        }

        $services = $professional->services()->get();

        return Inertia::render('Services', [
            'services' => $services
        ]);
    }

    public function show(Service $service)
    {
        $this->authorize('view', $service);
        
        return Inertia::render('Services/Show', [
            'service' => $service
        ]);
    }

    public function create()
    {
        $professional = auth()->user()->professional;
        
        if (!$professional) {
            return redirect()->route('settings.professional')->with('error', 'You need to complete your professional profile first.');
        }

        return Inertia::render('Services/Create');
    }

    public function store(ServiceRequest $request)
    {
        $professional = auth()->user()->professional;
        
        if (!$professional) {
            return redirect()->route('settings.professional')->with('error', 'You need to complete your professional profile first.');
        }

        $professional->services()->create($request->validated());

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        $this->authorize('update', $service);

        return Inertia::render('Services/Edit', [
            'service' => $service
        ]);
    }

    public function update(ServiceRequest $request, Service $service)
    {
        $this->authorize('update', $service);

        $service->update($request->validated());

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $this->authorize('delete', $service);

        $service->delete();

        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }
}