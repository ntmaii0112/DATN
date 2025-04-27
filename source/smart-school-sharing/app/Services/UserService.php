<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function getAll()
    {
        return User::all();
    }

    public function findById($id)
    {
        return User::findOrFail($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        $record = User::findOrFail($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        return User::destroy($id);
    }
}
