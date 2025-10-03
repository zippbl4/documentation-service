<?php

namespace App\Eloquent;

use Illuminate\Support\Facades\Schema;

trait ExcludeColumnsScopeTrait
{
    public function scopeExcludeColumns($query, array $exclude = [])
    {
        $columns = Schema::connection($this->connection)->getColumnListing($this->table);
        $query->select(array_diff($columns, $exclude));
        return $query;
    }

//    public function getTableColumns()
//    {
//        return Cache::rememberForever('table_columns_' . $this->getTable(), function () {
//            return Schema::getColumnListing($this->getTable());
//        });
//    }
//
//    public function scopeExcludeColumns($query, array $exclude = [])
//    {
//        return $query->select(array_diff($this->getTableColumns(), $exclude));
//    }
}
