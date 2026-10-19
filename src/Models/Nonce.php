<?php

namespace NjDigitalLab\Laranonce\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use NjDigitalLab\Laranonce\Laranonce;

#[Fillable(['name', 'salt', 'uuid'])]
class Nonce extends Model
{
    /**
     * Get the table name for the model.
     */
    public function getTable()
    {
        return Laranonce::tableName();
    }
}
