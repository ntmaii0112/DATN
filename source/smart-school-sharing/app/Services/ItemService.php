<?php

namespace App\Services;

use App\Models\Item;

class ItemService
{
    public function getAll()
    {
        return Item::all();
    }

    public function findById($id)
    {
        return Item::findOrFail($id);
    }

    public function create(array $data)
    {
        return Item::create($data);
    }

    public function update($id, array $data)
    {
        $record = Item::findOrFail($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        return Item::destroy($id);
    }
}
