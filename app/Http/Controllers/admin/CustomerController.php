<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Hiển thị danh sách khách hàng
    public function index()
    {
        $customers = Customer::all();
        return view('admin.customers.index', compact('customers'));
    }

    // Hiển thị form thêm khách hàng
    public function create()
    {
        return view('admin.customers.create');
    }

    // Lưu khách hàng mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:20',
            'point' => 'nullable|numeric|min:0',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Khách hàng đã được thêm thành công!');
    }

    // Hiển thị chi tiết khách hàng
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    // Hiển thị form chỉnh sửa khách hàng
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    // Cập nhật thông tin khách hàng
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $id,
            'phone' => 'required|string|max:15',
            'point' => 'nullable|numeric|min:0',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($request->all());

        return redirect()->route('admin.customers.index')->with('success', 'Cập nhật thông tin khách hàng thành công!');
    }

    // Xóa khách hàng
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Khách hàng đã được xóa!');
    }
}
