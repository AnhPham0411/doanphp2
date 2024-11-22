<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Hotel;
use App\Models\Images; // Ensure this model exists
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('hotel', 'images')->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $hotels = Hotel::all();
        return view('admin.rooms.create', compact('hotels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_number' => 'required|string|max:255',
            'type' => 'required|string',
            'capacity' => 'required|integer',
            'price' => 'required|numeric',
            'hotel_id' => 'required|exists:hotels,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $room = Room::create($request->only(['room_number', 'type', 'capacity', 'price', 'hotel_id']));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $imageName = time() . '_' . uniqid() . '_' . $imageFile->getClientOriginalName();
                $imageFile->move(public_path('images/rooms'), $imageName);

                Images::create([
                    'image_path' => 'images/rooms/' . $imageName,
                    'room_id' => $room->id,
                    'hotel_id' => $request->hotel_id,
                    'note' => null,
                ]);
            }
        }

        return redirect()->route('rooms.index')->with('success', 'Phòng đã được thêm thành công!');
    }

    public function edit($id)
    {
        $room = Room::with('images')->findOrFail($id);
        $hotels = Hotel::all();
        return view('admin.rooms.edit', compact('room', 'hotels'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'room_number' => 'required|string|max:255',
            'type' => 'required|string',
            'capacity' => 'required|integer',
            'price' => 'required|numeric',
            'hotel_id' => 'required|exists:hotels,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $room = Room::findOrFail($id);
        $room->update($request->except('images'));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $imageName = time() . '_' . uniqid() . '_' . $imageFile->getClientOriginalName();
                $imageFile->move(public_path('images/rooms'), $imageName);

                Images::create([
                    'image_path' => 'images/rooms/' . $imageName,
                    'room_id' => $room->id,
                    'hotel_id' => $request->hotel_id,
                    'note' => null,
                ]);
            }
        }

        return redirect()->route('rooms.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->images()->delete();
        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Phòng đã bị xóa!');
    }
    public function getRoomsByHotel(Request $request)
    {
        $hotelId = $request->query('hotel_id');

        // Lấy danh sách phòng theo hotel_id
        $rooms = Room::where('hotel_id', $hotelId)->get();

        return response()->json($rooms);
    }
}
