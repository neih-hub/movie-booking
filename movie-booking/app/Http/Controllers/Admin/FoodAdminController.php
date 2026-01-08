<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\Request;

class FoodAdminController extends Controller
{
    public function list(Request $request)
    {
        $search = $request->input('search');

        $foods = Food::when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        })
            ->orderBy('name')
            ->get();

        return view('admin.foods.create', compact('foods', 'search')); // view tên create.blade.php hiển thị danh sách
    }

    public function create()
    {
        return view('admin.foods.create_form');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:10000',
            'total' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());

            if (!file_exists(public_path('uploads/foods'))) {//đảm bảo thư mục tồn tại
                mkdir(public_path('uploads/foods'), 0755, true);
            }
            $moved = $file->move(public_path('uploads/foods'), $filename);

            if (!$moved) {
                return back()->with('error', 'Không thể lưu file');
            }
            $data['image'] = $filename; //chỉ luuw tên file
        }

        $food = Food::create($data);

        return redirect()->route('admin.foods.list')->with('success', 'Thêm món ăn thành công!');
    }

    public function edit($id)
    {
        $food = Food::findOrFail($id);
        return view('admin.foods.edit', compact('food'));
    }

    public function update(Request $request, $id)
    {
        $food = Food::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:10000',
            'total' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048'
        ]);

        //nếu có upload ảnh mới
        if ($request->hasFile('image')) {

            //tạo thư mục nếu chưa có
            $uploadPath = public_path('uploads/foods');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            //xóa ảnh cũ nếu tồn tại
            if ($food->image && file_exists(public_path('uploads/foods/' . $food->image))) {
                unlink(public_path('uploads/foods/' . $food->image));
            }

            //up ảnh
            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move($uploadPath, $filename);

            //lưu tên file vô csdl
            $data['image'] = $filename;
        }

        $food->update($data);
        return redirect()->route('admin.foods.list')->with('success', 'Cập nhật món ăn thành công!');
    }


    public function destroy($id)
    {
        Food::findOrFail($id)->delete();
        return redirect()->route('admin.foods.list')->with('success', 'Xóa thành công!');
    }
}