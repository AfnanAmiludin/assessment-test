<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectSentece;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ObjectSentenceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/object-sentences",
     *     summary="Mendapatkan semua data object sentences dengan pagination",
     *     description="Mengambil daftar semua object sentences yang tersimpan di database dengan pagination 10 item per halaman",
     *     tags={"Object Sentences"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Nomor halaman (default: 1)",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Data berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="current_page",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="sentence", type="string", example="Sebuah kucing sedang bermain"),
     *                         @OA\Property(property="description", type="string", example="Deskripsi gambar kucing"),
     *                         @OA\Property(property="image", type="string", example="uploads/category/1699876543.jpg"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-12T10:30:00.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-12T10:30:00.000000Z")
     *                     )
     *                 ),
     *                 @OA\Property(property="first_page_url", type="string", example="http://example.com/api/object-sentences?page=1"),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=5),
     *                 @OA\Property(property="last_page_url", type="string", example="http://example.com/api/object-sentences?page=5"),
     *                 @OA\Property(property="next_page_url", type="string", example="http://example.com/api/object-sentences?page=2"),
     *                 @OA\Property(property="path", type="string", example="http://example.com/api/object-sentences"),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="prev_page_url", type="string", example=null),
     *                 @OA\Property(property="to", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=50)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Token tidak valid atau tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve data"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $sentences = ObjectSentece::paginate(10);
            return response()->json([
                'success' => true,
                'message' => 'Data retrieved successfully',
                'data' => $sentences
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/object-sentences/{id}",
     *     summary="Mendapatkan detail object sentence berdasarkan ID",
     *     description="Mengambil data object sentence spesifik berdasarkan ID",
     *     tags={"Object Sentences"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID dari object sentence",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Data berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="sentence", type="string", example="Sebuah kucing sedang bermain"),
     *                 @OA\Property(property="description", type="string", example="Deskripsi gambar kucing"),
     *                 @OA\Property(property="image", type="string", example="uploads/category/1699876543.jpg"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Data tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Data not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $data = ObjectSentece::find($id);
            
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data retrieved successfully',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/object-sentences",
     *     summary="Membuat object sentence baru",
     *     description="Upload gambar beserta kalimat dan deskripsi",
     *     tags={"Object Sentences"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"sentence", "image"},
     *                 @OA\Property(
     *                     property="sentence",
     *                     type="string",
     *                     description="Kalimat yang menjelaskan objek gambar",
     *                     example=""
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Deskripsi detail tentang gambar",
     *                     example=""
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="string",
     *                     format="binary",
     *                     description="File gambar (jpeg, png, jpg, gif, maksimal 2MB)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Data berhasil dibuat",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="sentence", type="string", example="Seekor kucing sedang bermain dengan bola"),
     *                 @OA\Property(property="description", type="string", example="Gambar menampilkan kucing berbulu putih yang sedang bermain"),
     *                 @OA\Property(property="image", type="string", example="uploads/category/1699876543.jpg"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="sentence",
     *                     type="array",
     *                     @OA\Items(type="string", example="The sentence field is required.")
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="array",
     *                     @OA\Items(type="string", example="The image must be a file of type: jpeg, png, jpg, gif.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sentence' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $fileName = null;
            $path = 'uploads/category/';

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . '.' . $extension;
                $file->move($path, $fileName);
            }

            $sentence = ObjectSentece::create([
                'description' => $request->description,
                'sentence' => $request->sentence,
                'image' => $path . $fileName
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data created successfully',
                'data' => $sentence
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/object-sentences/{id}",
     *     summary="Update object sentence",
     *     description="Memperbarui data object sentence. Menggunakan POST karena multipart/form-data tidak support PUT/PATCH",
     *     tags={"Object Sentences"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID dari object sentence yang akan diupdate",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"sentence", "image"},
     *                 @OA\Property(
     *                     property="sentence",
     *                     type="string",
     *                     description="Kalimat yang menjelaskan objek gambar",
     *                     example=""
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Deskripsi detail tentang gambar (opsional)",
     *                     example=""
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="string",
     *                     format="binary",
     *                     description="File gambar baru (opsional, jpeg, png, jpg, gif, maksimal 2MB)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Data berhasil diupdate",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="sentence", type="string", example="Seekor kucing sedang tidur"),
     *                 @OA\Property(property="description", type="string", example="Gambar menampilkan kucing yang sedang tidur di sofa"),
     *                 @OA\Property(property="image", type="string", example="uploads/category/1699876543.jpg"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Data tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Data not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'sentence' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = ObjectSentece::find($id);

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found'
                ], 404);
            }

            $updateData = [
                'sentence' => $request->sentence,
            ];

            if ($request->description == null) {
                $updateData['description'] = null;
            }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . '.' . $extension;
                $path = 'uploads/category/';
                $file->move($path, $fileName);

                if (File::exists($data->image)) {
                    File::delete($data->image);
                }

                $updateData['image'] = $path . $fileName;
            }

            $data->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Data updated successfully',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/object-sentences/{id}",
     *     summary="Hapus object sentence",
     *     description="Menghapus data object sentence beserta file gambarnya",
     *     tags={"Object Sentences"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID dari object sentence yang akan dihapus",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Data berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Data tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Data not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to delete data"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $data = ObjectSentece::find($id);

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found'
                ], 404);
            }

            if (File::exists($data->image)) {
                File::delete($data->image);
            }

            $data->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}