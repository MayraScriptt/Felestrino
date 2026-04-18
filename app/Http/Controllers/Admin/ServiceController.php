<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Service;
use App\Support\SiteCache;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('admin.services.index', [
            'services' => Service::query()->orderBy('display_order')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.services.create', [
            'service' => new Service(),
        ]);
    }

    public function store(ServiceRequest $request): RedirectResponse
    {
        $payload = $this->payload($request);

        if ($request->hasFile('image')) {
            $payload['image_path'] = $request->file('image')->store('services', 'public');
        }

        Service::query()->create($payload);
        SiteCache::bump();

        return redirect()->route('admin.services.index')->with('status', 'Servico criado com sucesso.');
    }

    public function show(Service $service): RedirectResponse
    {
        return redirect()->route('admin.services.edit', $service);
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $payload = $this->payload($request);

        if ($request->hasFile('image')) {
            if ($service->image_path) {
                Storage::disk('public')->delete($service->image_path);
            }
            $payload['image_path'] = $request->file('image')->store('services', 'public');
        }

        $service->update($payload);
        SiteCache::bump();

        return redirect()->route('admin.services.index')->with('status', 'Servico atualizado com sucesso.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        if ($service->image_path) {
            Storage::disk('public')->delete($service->image_path);
        }

        $service->delete();
        SiteCache::bump();

        return redirect()->route('admin.services.index')->with('status', 'Servico removido com sucesso.');
    }

    private function payload(ServiceRequest $request): array
    {
        $payload = $request->safe()->only([
            'title',
            'slug',
            'short_description',
            'description',
            'display_order',
        ]);
        $payload['is_highlight'] = (bool) $request->boolean('is_highlight');
        $payload['is_published'] = (bool) $request->boolean('is_published');

        return $payload;
    }
}
