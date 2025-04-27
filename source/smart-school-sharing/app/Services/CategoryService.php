<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function getAll()
    {
        return Category::all();
    }

    public function findById($id)
    {
        return Category::findOrFail($id);
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update($id, array $data)
    {
        $record = Category::findOrFail($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        return Category::destroy($id);
    }
}
