<?php

namespace App\Services;

use App\Models\Report;

class ReportService
{
    public function getAll()
    {
        return Report::all();
    }

    public function findById($id)
    {
        return Report::findOrFail($id);
    }

    public function create(array $data)
    {
        return Report::create($data);
    }

    public function update($id, array $data)
    {
        $record = Report::findOrFail($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        return Report::destroy($id);
    }
}
