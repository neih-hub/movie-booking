<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\Request;

class FoodAdminController extends Controller
{
    // Danh sách (search hỗ trợ)
    public function list(Request $request)
    {
        $search = $request->input('search');

        $foods = Food::when($search, function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->orderBy('name')
            ->get();

        return view('admin.foods.create', compact('foods', 'search')); // LƯU Ý: view tên create.blade.php hiển thị danh sách
    }

    // Form thêm (riêng)
    public function create()
    {
        return view('admin.foods.create_form');
    }

    // Lưu thêm
    public function store(Request $request)
{
    $data = $request->validate([
        'name'  => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'total' => 'required|numeric|min:0',
        'image' => 'nullable|image|max:2048'
    ]);

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        // ensure folder exists
        if (!file_exists(public_path('uploads/foods'))) {
            mkdir(public_path('uploads/foods'), 0755, true);
        }
        $moved = $file->move(public_path('uploads/foods'), $filename);
        // debug nếu cần:
        // if (!$moved) { return back()->with('error', 'Không thể lưu file'); }
        $data['image'] = $filename; // lưu chỉ tên file
    }

    $food = Food::create($data);

    return redirect()->route('admin.foods.list')->with('success', 'Thêm món ăn thành công!');
}



    // Form edit
    public function edit($id)
    {
        $food = Food::findOrFail($id);
        return view('admin.foods.edit', compact('food'));
    }

    // Cập nhật
    public function update(Request $request, $id)
{
    $food = Food::findOrFail($id);

    // Validate
    $data = $request->validate([
        'name'  => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'total' => 'required|numeric|min:0',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
    ]);

    // Nếu có upload ảnh mới
    if ($request->hasFile('image')) {

        // Tạo thư mục nếu chưa có
        $uploadPath = public_path('uploads/foods');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Xóa ảnh cũ nếu tồn tại
        if ($food->image && file_exists(public_path('uploads/foods/' . $food->image))) {
            unlink(public_path('uploads/foods/' . $food->image));
        }

        // Upload ảnh mới
        $file = $request->file('image');
        $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $file->move($uploadPath, $filename);

        // Lưu tên file mới vào DB
        $data['image'] = $filename;
    }

    // Cập nhật DB
    $food->update($data);

    return redirect()->route('admin.foods.list')->with('success', 'Cập nhật món ăn thành công!');
}



    // Xóa
    public function destroy($id)
    {
        Food::findOrFail($id)->delete();
        return redirect()->route('admin.foods.list')->with('success', 'Xóa thành công!');
    }
}