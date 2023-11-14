<?php

namespace App\Repositories;

use App\Models\Item;

class EloquentItemRepository implements ItemRepositoryInterface
{
    public function all()
    {
        return Item::all();
    }

    public function create(array $data)
    {
        return Item::create($data);
    }

    public function find($id)
    {
        return Item::findOrFail($id);
    }

    public function update(array $data, $id)
    {
        $item = $this->find($id);
        $item->update($data);
        return $item;
    }

    public function delete($id)
    {
        return Item::destroy($id);
    }
}
