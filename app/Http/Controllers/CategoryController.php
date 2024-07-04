<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\PostStandaloneCategoryRequest;
use App\Http\Requests\PostLeafCategoryRequest;
use App\Http\Resources\CategoryTreeResource;

class CategoryController extends Controller
{
    // GET: สำหรับเรียกดู Category แบบ standalone node

    public function getStandaloneCategory($id)
    {
        $category = Category::whereNull('parent_id')->findOrFail($id);
        return response()->json($category);
    }

    // GET: สำหรับเรียกดู Category ทั้งหมด ในรูปแบบ Tree ภายใต้ node ที่รับค่า
    /**
     * @LRDparam per_page int|nullable|min:1|max:100
     * // จำนวนรายการต่อหน้า
     * @LRDparam page int|nullable|min:1
     * // หมายเลขหน้าที่ต้องการ
     * @LRDresponses 200|422
     */
    public function getCategoryTree(Request $request, $id)
    {
        $request->validate([
            'per_page' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
        ]);

        $category = Category::findOrFail($id);

        return new CategoryTreeResource($category);
    }



    // GET: สำหรับเรียกดู Category ทั้งหมด ในรูปแบบ Array
    /**
     * @LRDparam per_page int|nullable|min:1|max:100
     * // จำนวนรายการต่อหน้า
     * @LRDparam page int|nullable|min:1
     * // หมายเลขหน้าที่ต้องการ
     * @LRDresponses 200|422
     */
    public function getAllCategories(Request $request)
    {
        $request->validate([
            'per_page' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
        ]);

        $categories = Category::paginate($request->input('per_page', 10));
        return response()->json($categories);
    }

    // POST: สำหรับ Create Category แบบ standalone node
    public function createStandaloneCategory(PostStandaloneCategoryRequest $request)
    {
        $category = Category::create([
            'category_name' => $request->category_name,
        ]);

        return response()->json($category, 201);
    }

    // POST: สำหรับ Create Category แบบ leaf node
    public function createLeafCategory(PostLeafCategoryRequest $request)
    {
        $category = Category::create([
            'category_name' => $request->category_name,
            'parent_id' => $request->parent_id,
        ]);

        return response()->json($category, 201);
    }

    // DELETE: สำหรับการลบ Category
    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
