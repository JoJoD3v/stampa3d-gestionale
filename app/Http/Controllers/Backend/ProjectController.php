<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $search   = $request->input('search', '');
        $projects = Project::withCount('files')
            ->when($search, fn($q) => $q->where('name', 'like', '%' . $search . '%'))
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('backend.projects.index', compact('projects', 'search'));
    }

    public function create(): View
    {
        return view('backend.projects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:200'],
            'photo'          => ['nullable', 'image', 'max:5120'],
            'files'          => ['nullable', 'array'],
            'files.*'        => ['file', 'max:102400'],
            'filament_type'  => ['required', 'string', 'max:100'],
            'filament_grams' => ['required', 'integer', 'min:1'],
            'print_hours'    => ['required', 'integer', 'min:0'],
            'print_minutes'  => ['required', 'integer', 'min:0', 'max:59'],
            'height_cm'      => ['nullable', 'numeric', 'min:0'],
            'width_cm'       => ['nullable', 'numeric', 'min:0'],
            'depth_cm'       => ['nullable', 'numeric', 'min:0'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('projects/photos', 'public');
        }

        $project = Project::create([
            'name'           => $request->name,
            'photo'          => $photoPath,
            'filament_type'  => $request->filament_type,
            'filament_grams' => $request->filament_grams,
            'print_hours'    => $request->print_hours,
            'print_minutes'  => $request->print_minutes,
            'height_cm'      => $request->height_cm ?: null,
            'width_cm'       => $request->width_cm  ?: null,
            'depth_cm'       => $request->depth_cm  ?: null,
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('projects/files', 'public');
                $project->files()->create([
                    'original_name' => $file->getClientOriginalName(),
                    'path'          => $path,
                    'size'          => $file->getSize(),
                ]);
            }
        }

        return redirect()
            ->route('backend.projects.show', $project)
            ->with('success', 'Progetto creato con successo.');
    }

    public function show(Project $project): View
    {
        $project->load('files');

        return view('backend.projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        $project->load('files');

        return view('backend.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:200'],
            'photo'          => ['nullable', 'image', 'max:5120'],
            'files'          => ['nullable', 'array'],
            'files.*'        => ['file', 'max:102400'],
            'filament_type'  => ['required', 'string', 'max:100'],
            'filament_grams' => ['required', 'integer', 'min:1'],
            'print_hours'    => ['required', 'integer', 'min:0'],
            'print_minutes'  => ['required', 'integer', 'min:0', 'max:59'],
            'height_cm'      => ['nullable', 'numeric', 'min:0'],
            'width_cm'       => ['nullable', 'numeric', 'min:0'],
            'depth_cm'       => ['nullable', 'numeric', 'min:0'],
        ]);

        $photoPath = $project->photo;
        if ($request->hasFile('photo')) {
            if ($project->photo) {
                Storage::disk('public')->delete($project->photo);
            }
            $photoPath = $request->file('photo')->store('projects/photos', 'public');
        }

        $project->update([
            'name'           => $request->name,
            'photo'          => $photoPath,
            'filament_type'  => $request->filament_type,
            'filament_grams' => $request->filament_grams,
            'print_hours'    => $request->print_hours,
            'print_minutes'  => $request->print_minutes,
            'height_cm'      => $request->height_cm ?: null,
            'width_cm'       => $request->width_cm  ?: null,
            'depth_cm'       => $request->depth_cm  ?: null,
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('projects/files', 'public');
                $project->files()->create([
                    'original_name' => $file->getClientOriginalName(),
                    'path'          => $path,
                    'size'          => $file->getSize(),
                ]);
            }
        }

        return redirect()
            ->route('backend.projects.show', $project)
            ->with('success', 'Progetto aggiornato con successo.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        foreach ($project->files as $file) {
            Storage::disk('public')->delete($file->path);
        }

        if ($project->photo) {
            Storage::disk('public')->delete($project->photo);
        }

        $project->delete();

        return redirect()
            ->route('backend.projects.index')
            ->with('success', 'Progetto eliminato con successo.');
    }

    public function destroyFile(Project $project, ProjectFile $file): RedirectResponse
    {
        Storage::disk('public')->delete($file->path);
        $file->delete();

        return redirect()
            ->route('backend.projects.edit', $project)
            ->with('success', 'File eliminato.');
    }

    public function downloadFile(Project $project, ProjectFile $file): StreamedResponse
    {
        abort_unless(Storage::disk('public')->exists($file->path), 404, 'File non trovato.');

        return Storage::disk('public')->download($file->path, $file->original_name);
    }
}
