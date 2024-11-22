<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenities;
use App\Models\Images; // Đảm bảo model này tồn tại
use Illuminate\Http\Request;

class AmenitiesController extends Controller
{
    public function index()
    {
        // Lấy danh sách tất cả tiện nghi cùng với hình ảnh liên quan
        $amenities = Amenities::with('images')->get(); // Sử dụng 'images' vì quan hệ đã định nghĩa
        return view('admin.amenities.index', compact('amenities'));
    }

    public function create()
    {
        // Hiển thị form thêm tiện nghi mới
        return view('admin.amenities.create');
    }

    public function store(Request $request)
{
    // Xác thực dữ liệu
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string', // Thêm xác thực cho description
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Xác thực ảnh
    ]);

    // Tạo tiện nghi
    $amenity = Amenities::create([
        'name' => $request->name,
        'description' => $request->description, // Lưu description
    ]);

    // Lưu ảnh (không thay đổi)
    if ($request->hasFile('image')) {
        // Tạo tên file ảnh duy nhất
        $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move(public_path('images/amenities'), $imageName);

        Images::create([
            'image_path' => 'images/amenities/' . $imageName,
            'amenities_id' => $amenity->id,  // Liên kết với amenity
            'note' => 1, // Ghi chú có thể để null
        ]);
    }

    return redirect()->route('amenities.index')->with('success', 'Tiện nghi đã được thêm thành công!');
}

    public function edit($id)
    {
        // Hiển thị form sửa tiện nghi
        $amenity = Amenities::with('images')->findOrFail($id); // Sử dụng 'images'
        return view('admin.amenities.edit', compact('amenity'));
    }

    public function update(Request $request, $id)
{
    // Xác thực thông tin tiện nghi
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string', // Thêm xác thực cho description
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Kiểm tra ảnh
    ]);

    $amenity = Amenities::findOrFail($id);
    $amenity->update($request->only('name', 'description')); // Cập nhật thông tin tiện nghi mà không cập nhật ảnh

    // Xử lý ảnh mới (không thay đổi)
    if ($request->hasFile('image')) {
        $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move(public_path('images/amenities'), $imageName);

        Images::updateOrCreate(
            ['amenities_id' => $amenity->id], // Điều kiện để cập nhật
            ['image_path' => 'images/amenities/' . $imageName, 'note' => 1] // Cập nhật hoặc tạo mới
        );
    }

    return redirect()->route('amenities.index')->with('success', 'Cập nhật thành công!');
}

    public function destroy($id)
    {
        // Xóa tiện nghi
        $amenity = Amenities::findOrFail($id);

        // Xóa ảnh liên quan nếu cần
        if ($amenity->images()->exists()) {
            // Thêm logic xóa file ảnh trong thư mục nếu cần
        }

        $amenity->delete(); // Xóa tiện nghi

        return redirect()->route('amenities.index')->with('success', 'Tiện nghi đã bị xóa!');
    }
}
