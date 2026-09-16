<?php

namespace App\Http\Controllers;

use App\Http\Resources\APIResource;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|min:5',
            'location'    => 'required|max:100',
            'description' => 'nullable|max:100',
            'image_url'   => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image_url');
        $path = $image->store('destinations', 'public');

        $destination = Destination::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
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
    public function update(Request $request, Destination $destination)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|min:5',
            'location'    => 'required|max:100',
            'description' => 'required|max:100',
            'image_url'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = [
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
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
        Storage::disk('public')->delete($destination->image_url);

        $destination->delete();

        return new APIResource(true, 'Data Destinasi Berhasil Dihapus!', null);
    }
}
