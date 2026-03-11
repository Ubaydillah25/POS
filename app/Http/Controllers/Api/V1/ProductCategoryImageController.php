<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadProductCategoryImageRequest;
use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductCategoryImageController extends Controller
{
     public function store(UploadProductCategoryImageRequest $request, $id)
    {
        $category = ProductCategory::find($id);

        if (!$category) {
            return ApiResponse::error(
                'Product category not found',
                Response::HTTP_NOT_FOUND
            );
        }

        if ($category->image) {
            // Delete the old image
            Storage::disk('public')->delete($category->image);
        }

        $path = $request->file('image')->store('product_categories', 'public');

        $category->update(['image' => $path]);

        return ApiResponse::success(
            new ProductCategoryResource($category),
                'Product category image uploaded successfully'
        );
    }
}
