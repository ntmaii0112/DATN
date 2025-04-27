<?php

namespace App\Services;

use App\Models\Review;

class ReviewService
{
    public function getAll()
    {
        return Review::all();
    }

    public function findById($id)
    {
        return Review::findOrFail($id);
    }

    public function create(array $data)
    {
        return Review::create($data);
    }

    public function update($id, array $data)
    {
        $record = Review::findOrFail($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        return Review::destroy($id);
    }
}
