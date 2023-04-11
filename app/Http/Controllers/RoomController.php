<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoomsRequest;
use App\Models\Room;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Str;

class RoomController extends Controller
{

    public function index()
    {
        $rooms = Room::all();
        return response()->json([
            'rooms' => $rooms
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CreateRoomsRequest $request)
    {
        try{
            $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();
            //Create Rooms


                $data = Auth::guard('api')->user();
                Room::create([
                    'name' => $request->name,
                    'title' => $request->title,
                    'description' => $request->description,
                    'address' => $request->address,
                    'price' => $request->price,
                    'area' => $request->area,
                    'image' => $imageName,
                    'category_id' => $request->category_id,
                    'account_id' =>  $data->id,
                    'city' => $request->city,
                    'district' => $request->district,
                    'ward' => $request->ward,
                    'status' => $request->status,
                ]);

                 //Lưu ảnh
            Storage::disk('public')->put($imageName, file_get_contents($request->image));

            return response()->json([
                'message' => "Đăng tin thành công!"
            ],200);







        }catch (\Exception $e){
            return response()->json([
                'message' => "Có gì đó không ổn!"
            ],500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
