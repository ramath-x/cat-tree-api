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
        $page = $request->input('page', 1);

        $children = $this->children()
            ->orderBy('id')
            ->get();

        $paginatedChildren = $this->paginateCollection($children, $perPage, $page);

        return [
            'id' => $this->id,
            'category_name' => $this->category_name,
            'parent_id' => $this->parent_id,
            'children' => CategoryTreeResource::collection($paginatedChildren->items()),
            'children_pagination' => [
                'total' => $paginatedChildren->total(),
                'per_page' => $paginatedChildren->perPage(),
                'current_page' => $paginatedChildren->currentPage(),
                'last_page' => $paginatedChildren->lastPage(),
            ],
        ];
    }

    protected function paginateCollection(Collection $collection, $perPage, $page)
    {
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $collection->forPage($page, $perPage),
            $collection->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
    }
}
