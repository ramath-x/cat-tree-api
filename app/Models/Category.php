<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = ['category_name', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function hasChildren()
    {
        return $this->children()->exists();
    }

    // public function getDescendantsTree()
    // {
    //     return $this->children()
    //         ->with('children')
    //         ->paginate(10)
    //         ->map(function ($child) {
    //             return [
    //                 'id' => $child->id,
    //                 'category_name' => $child->category_name,
    //                 'parent_id' => $child->parent_id,
    //                 'children' => $child->getDescendantsTree()
    //             ];
    //         });
    // }
}
