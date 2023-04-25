<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoomsRequest;
use App\Models\ImageChild;
use App\Models\Room;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\File as HttpFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File as RulesFile;
use Illuminate\Support\Facades\File;

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
    public function create2(CreateRoomsRequest $request)
    {
        try{
            $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();
            //Create Rooms


                // $data = Auth::guard('api')->user();
                Room::create([
                    'name' => $request->name,
                    'title' => $request->title,
                    'description' => $request->description,
                    'address' => $request->address,
                    'price' => $request->price,
                    'area' => $request->area,
                    'image' => $imageName,
                    'category_id' => $request->category_id,
                    'account_id' =>  $request->user()->id,
                    'city' => $request->city,
                    'district' => $request->district,
                    'ward' => $request->ward,
                    'status' => $request->status,
                ]);

                 //Lưu ảnh
                 Storage::disk('public')->put($imageName, file_get_contents($request->image));

            return response()->json([
                'message' => "Đăng tin thành công!",

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
    public function dit(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function detail( $id)
    {
        $rooms = Room::with(['category','account','imagechild'])->where('id',$id)->first();

        $data = Auth::guard('api')->user();
        if($rooms){
            return response()->json([
                'message' => 'Thành công',
                'data'   => $rooms,
                // 'caterory' => $rooms->category->name,
                // 'user' =>   $rooms->user()->name
            ],200);
        } else {
            return response()->json([
                'message' => 'Không thành công',
            ],400);
        }
    }

    public function hot(){
        $rooms = Room::with(['account','category','imagechild'])->where('hot', 1)->get();

        if($rooms){
            return response()->json([
                'message' => 'Thành công',
                'data'   => $rooms
            ],200);
        } else {
            return response()->json([
                'message' => 'Không thành công',
            ],400);
        }
    }

    public function update(Request $request,  $id)
    {
       $room = Room::with(['category','account','imagechild'])->where('id',$id)->first();
       if($room){
            if($room->account_id==$request->user()->id){
                if($request->hasFile('image')){
                    $image_name=time().'.'.$request->image->extension();
                    $request->image->move(public_path('/storage'),$image_name);
                    $old_path = public_path().'/storage/'.$room->image;
                    if(File::exists($old_path)){
                        File::delete($old_path);
                    }
                }else{
                    $image_name=$room->image;
                }
                $room->update([
                    'name' => $request->name,
                    'title' => $request->title,
                    'description' => $request->description,
                    'address' => $request->address,
                    'price' => $request->price,
                    'area' => $request->area,
                    'image' => $image_name,
                    'category_id' => $request->category_id,
                    'city' => $request->city,
                    'district' => $request->district,
                    'ward' => $request->ward,
                    'status' => $request->status,
                ]);

                if($request->hasFile('image_child')){
                    $files= $request->file('image_child');
                    foreach($files as $file){
                        $imageName=time().'.'.$file->extension();
                        // $request['room_id']=$room->id;
                        // $request['image_child']= $imageName;
                        $file->move(public_path('/storage'), $imageName);
                        $image= ImageChild::create([
                            'image_child' => $imageName,
                            'room_id' =>$room->id,

                        ]);

                    }
                }

                return response()->json([
                    'message' => 'Thành công',
                    'data' => $room,
                    'image_child' => $image
                ]);

            }else{
                return response()->json([
                    'message' => 'Thất bại'
                ],403);
            }
       }else{
            return response()->json([
                'message' => 'No room'
            ],400);
       }

    }


    public function destroy( $id,Request $request)
    {
        $room = Room::findOrfail($id);
        if($room){
            if($room->account_id==$request->user()->id){
                $old_path = public_path().'/storage/'.$room->image;
                if(File::exists($old_path)){
                    File::delete($old_path);
                }
                $imagechild=ImageChild::where('room_id',$room->id)->get();
                foreach($imagechild as $imageC){
                    if(File::exists('/storage/'.$imageC->image_child)){
                        File::delete('/storage/'.$imageC->image_child);
                    }

                }
                $room->delete();

                return response()->json([
                    'message' => 'Đã xoá thành công'
                ],200);
            }else{
                return response()->json([
                    'message' => 'Không'
                ],403);
            }
        }else{
            return response()->json([
                'message' => 'Not found'
            ],400);
        }
    }

    public function create(Request $request){
        try{

                $image_name=time().'.'. $request->image->extension();
                $request->image->move(public_path('/storage'),$image_name);
                $room=Room::create([
                    'name' => $request->name,
                    'title' => $request->title,
                    'description' => $request->description,
                    'address' => $request->address,
                    'price' => $request->price,
                    'area' => $request->area,
                    'image' => $image_name,
                    'category_id' => $request->category_id,
                    'account_id' =>  $request->user()->id,
                    'city' => $request->city,
                    'district' => $request->district,
                    'ward' => $request->ward,
                    'status' => $request->status,
                ]);
                $room->load('category','account');

            if($request->hasFile('image_child')){
                $files= $request->file('image_child');
                foreach($files as $file){
                    $imageName=time().'.'.$file->extension();
                    // $request['room_id']=$room->id;
                    // $request['image_child']= $imageName;
                    $file->move(public_path('/storage'), $imageName);
                    $image= ImageChild::create([
                        'image_child' => $imageName,
                        'room_id' =>$room->id,

                    ]);

                }

            }
            return response()->json([
                'message' => "Đăng tin thành công!",
                'data'  => $room,
                'room ' =>  $room->id,
                'imagechild' => $image

            ],200);
         }catch (\Exception $e){
            return response()->json([
                'message' => "Có gì đó không ổn!"
            ],500);
        }
    }

    public function list(Request $request){
        $room = Room::with(['category','account']);
        if($request->address){
            $room->where('address','LIKE','%'.$request->address.'%');
        }
        if($request->district){
            $room->where('district','LIKE','%'.$request->district.'%');
        }
        if($request->city){
            $room->where('city','LIKE','%'.$request->city.'%');
        }
        if($request->ward){
            $room->where('ward','LIKE','%'.$request->ward.'%');
        }
        if($request->category){
            $room->whereHas('category',function($query) use($request){
                $query->where('slug',$request->category);
            });
        }
        $rooms = $room->get();
        return response()->json([
            'message' => 'Thành công',
            'data' => $rooms
        ],200);
    }

}
