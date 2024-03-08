<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchasePDF extends Model
{
    use SoftDeletes;
    protected $table = "purchase_pdf";
    protected $fillable = ['purchase_id',"path"];

    public function getFilePdf()  {
        if (!empty($this->path) && file_exists($this->path)) {
           return url($this->path);
        }else {
            return "";
        }
    }
}
