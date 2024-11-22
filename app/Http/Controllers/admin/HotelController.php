<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\images;


class HotelController extends Controller
{
    // Hiển thị danh sách tất cả khách sạn
    public function index()
{
    // Lấy tất cả khách sạn cùng với ảnh liên quan
    $hotels = Hotel::with('images')->get();

    return view('admin.hotels.index', compact('hotels'));
}


    // Hiển thị form thêm khách sạn mới
    public function create()
    {
        return view('admin.hotels.create');
    }


    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'name' => 'required|max:255',
            'address' => 'required|max:255',
            'city' => 'required|max:255',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable',
        ]);

        // Tạo khách sạn mới
        $hotel = new Hotel();
        $hotel->name = $request->name;
        $hotel->address = $request->address;
        $hotel->city = $request->city;
        $hotel->description = $request->description;

        // Lưu khách sạn để lấy hotel_id
        $hotel->save();

        // Xử lý upload ảnh
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Tạo tên file ảnh duy nhất
                $imageName = time() . '_' . $image->getClientOriginalName();

                // Di chuyển ảnh đến thư mục public/images/hotels
                $image->move(public_path('images/hotels'), $imageName);

                // Lưu thông tin ảnh vào bảng images
                $hotel->images()->create([
                    'image_path' => 'images/hotels/' . $imageName,  // Đường dẫn ảnh
                    'hotel_id' => $hotel->id,  // Liên kết với hotel
                    'room_id' => null,  // Để mặc định 0
                    'amenities_id' => null,  // Để mặc định 0
                    'note' => 1,  // Ghi chú có thể để null
                ]);
            }
        }

        return redirect()->route('hotels.index')->with('success', 'Thêm khách sạn thành công!');
    }




    // Hiển thị form sửa khách sạn
    public function edit($id)
    {
        $hotel = Hotel::findOrFail($id);
        return view('admin.hotels.edit', compact('hotel'));
    }

    // Cập nhật thông tin khách sạn
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'address' => 'required|max:255',
            'city' => 'required|max:255',
            'description' => 'nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Kiểm tra định dạng ảnh
        ]);

        $hotel = Hotel::findOrFail($id);

        // Cập nhật thông tin khách sạn
        $hotel->update($request->except('images')); // Không cập nhật ảnh ở đây

        // Xử lý ảnh
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Tạo tên tệp duy nhất cho ảnh
                $imageName = time() . '_' . $image->getClientOriginalName();
                // Lưu ảnh vào thư mục
                $image->move(public_path('image'), $imageName);

                // Thêm logic lưu tên ảnh vào cơ sở dữ liệu nếu cần thiết
                // Ví dụ: Image::create(['hotel_id' => $hotel->id, 'filename' => $imageName]);
            }
        }

        return redirect()->route('hotels.index')->with('success', 'Cập nhật thành công!');
    }


    // Xóa khách sạn
    public function destroy($id)
    {
        $hotel = Hotel::findOrFail($id);
        $hotel->delete();
        return redirect()->route('hotels.index')->with('success', 'Khách sạn đã bị xóa!');
    }

    //search
    public function search(Request $request)
{
    $request->validate([
        'query' => 'required|string|max:255', // Xác thực dữ liệu đầu vào
    ]);

    $query = $request->input('query');

    // Tìm kiếm khách sạn theo tên
    $hotels = Hotel::where('name', 'LIKE', "%{$query}%")->get();

    // Trả về view với danh sách khách sạn và truy vấn
    return view('admin.search', compact('hotels', 'query'));
}

}
