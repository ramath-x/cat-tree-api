<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class CategoryTreeResource extends JsonResource
{


    public function toArray(Request $request): array
    {

        $perPage = $request->input('per_page', 10);

        $children = $this->children()
            ->orderBy('id')
            ->paginate($perPage);
        $collection = collect($children->items());
        return [
            'data' => [
                'id' => $this->id,
                'name' => $this->category_name,
                'parent_id' => $this->parent_id,
                'children' => $collection->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->category_name,
                        'has_children' => $child->hasChildren(),
                        'children_url' => $child->hasChildren()
                            ? env("APP_URL") . "/api/categories/tree/{$child->id}"
                            : null
                    ];
                }),
            ],
            'meta' => [
                'current_page' => $children->currentPage(),
                'per_page' => $children->perPage(),
                'total_direct_children' => $children->total(),
            ],
            'links' => [
                'next' => $children->nextPageUrl(),
                'prev' => $children->previousPageUrl(),
            ],
        ];
    }
}
