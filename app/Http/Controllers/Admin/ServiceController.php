<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('admin.services.index', [
            'services' => Service::query()->orderBy('sort_order')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(ServiceRequest $request): RedirectResponse
    {
        $payload = $request->validated();
        $payload['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $payload['image_path'] = $request->file('image')->store('services', 'public');
        }

        Service::query()->create($payload);
        Cache::forget('site.services');

        return redirect()->route('admin.services.index')->with('status', 'Serviço criado com sucesso.');
    }

    public function show(Service $service): View
    {
        return view('admin.services.show', ['service' => $service]);
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', ['service' => $service]);
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $payload = $request->validated();
        $payload['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($service->image_path) {
                Storage::disk('public')->delete($service->image_path);
            }

            $payload['image_path'] = $request->file('image')->store('services', 'public');
        }

        $service->update($payload);
        Cache::forget('site.services');

        return redirect()->route('admin.services.index')->with('status', 'Serviço atualizado com sucesso.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        if ($service->image_path) {
            Storage::disk('public')->delete($service->image_path);
        }

        $service->delete();
        Cache::forget('site.services');

        return redirect()->route('admin.services.index')->with('status', 'Serviço removido com sucesso.');
    }
}
