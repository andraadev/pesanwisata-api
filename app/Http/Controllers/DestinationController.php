<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestinationFormRequest;
use App\Http\Resources\APIResource;
use App\Models\Destination;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $destination = Destination::all();

        return new APIResource(true, 'List Data Destinasi', $destination);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DestinationFormRequest $request)
    {
        $image = $request->file('image_url');
        $path = $image->store('destinations', 'public');

        $destination = Destination::create([
            'name'        => $request->name,
            'slug'        => $this->generateUniqueSlug($request->name),
            'location'    => $request->location,
            'description' => $request->description,
            'image_url'   => $path,
        ]);

        return new APIResource(true, 'Data Destinasi Berhasil Ditambahkan!', $destination);
    }

    /**
     * Display the specified resource.
     */
    public function show(Destination $destination)
    {
        return new APIResource(true, 'Data Destinasi', $destination);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DestinationFormRequest $request, Destination $destination)
    {
        $data = [
            'name'        => $request->name,
            'slug'        => $this->generateUniqueSlug($request->name, $destination->id),
            'location'    => $request->location,
            'description' => $request->description,
        ];

        if ($request->hasFile('image_url')) {
            $image = $request->file('image_url');
            $path = $image->store('destinations', 'public');

            if ($destination->image_url) {
                Storage::disk('public')->delete($destination->image_url);
            }

            $data['image_url'] = $path;
        }

        $destination->update($data);

        return new APIResource(
            true,
            'Data Destinasi Berhasil Diubah!',
            $destination
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Destination $destination)
    {
        $imagePath = $destination->getRawOriginal('image_url');

        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        $destination->delete();

        return new APIResource(true, 'Data Destinasi Berhasil Dihapus!', null);
    }

    /**
     * Generate unique slug for destination.
     */
    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (
            Destination::where('slug', $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }
}
