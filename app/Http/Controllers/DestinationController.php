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
            'name' => 'required|min:5',
            'location'     => 'required',
            'description'   => 'nullable',
            'image_url'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image_url');
        $image->storeAs('public/destinations_image', $image->hashName());

        $destination = Destination::create([
            'name'     => $request->name,
            'slug' => Str::slug($request->name),
            'location'   => $request->location,
            'description' => $request->description,
            'image_url'     => $image->hashName(),
        ]);

        return new APIResource(true, 'Data Destinasi Berhasil Ditambahkan!', $destination);
    }

    /**
     * Display the specified resource.
     */
    public function show(Destination $destination)
    {
        $destination = Destination::where('slug', $id)->first();
        if (!$destination) {
            return response()->json([
                'success' => false,
                'message' => 'Destinasi Tidak Ditemukan!'
            ], 404);
        }

        return new APIResource(true, 'Data Destinasi', $destination);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Destination $destination)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|min:5',
            'location'  => 'required',
            'description' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image_url');
            $image->storeAs('public/destination_image', $image->hashName());

            Storage::delete('public/destination_image/' . basename($destination->image_url));

            $destination->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'location' => $request->location,
                'description' => $request->description,
                'image_url' => $image->hashName(),
            ]);
        } else {
            $destination->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'location' => $request->location,
                'description' => $request->description,
            ]);
        }
        return new APIResource(true, 'Data Destinasi Berhasil Diubah!', $destination);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Destination $destination)
    {
        Storage::delete('public/destination_image/' . basename($destination->image_url));

        $destination->delete();

        return new APIResource(true, 'Data Destinasi Berhasil Dihapus!', null);
    }
}
