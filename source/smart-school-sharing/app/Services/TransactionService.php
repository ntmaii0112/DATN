<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService
{
    public function getAll()
    {
        return Transaction::all();
    }

    public function findById($id)
    {
        return Transaction::findOrFail($id);
    }

    public function create(array $data)
    {
        return Transaction::create($data);
    }

    public function update($id, array $data)
    {
        $record = Transaction::findOrFail($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        return Transaction::destroy($id);
    }
}
