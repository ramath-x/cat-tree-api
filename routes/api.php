<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::prefix('categories')->group(function () {
    // GET: สำหรับเรียกดู Category แบบ standalone node
    Route::get('/standalone/{id}', [CategoryController::class, 'getStandaloneCategory']);

    // GET: สำหรับเรียกดู Category ทั้งหมด ในรูปแบบ Tree ภายใต้ node ที่รับค่า
    Route::get('/tree/{id}', [CategoryController::class, 'getCategoryTree']);

    // GET: สำหรับเรียกดู Category ทั้งหมด ในรูปแบบ Array
    Route::get('/all', [CategoryController::class, 'getAllCategories']);

    // POST: สำหรับ Create Category แบบ standalone node
    Route::post('/standalone', [CategoryController::class, 'createStandaloneCategory']);

    // POST: สำหรับ Create Category แบบ leaf node
    Route::post('/leaf', [CategoryController::class, 'createLeafCategory']);

    // DELETE: สำหรับการลบ Category
    Route::delete('/{id}', [CategoryController::class, 'deleteCategory']);
});
