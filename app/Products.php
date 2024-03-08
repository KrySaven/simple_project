<?php

namespace App;

use App\Color;
use App\Size;
use App\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use SoftDeletes;
    protected $table ="products";
    protected $fillable =['name','name_kh','price','category_id','unit_id','description','image','pro_no','created_by','updated_by','deleted_by','source_image','code_product'];

    /**
     * The roles that belong to the Products
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_colors', 'product_id', 'color_id')
        ->wherePivot('deleted_at', null);

    }

    public function unit(){
        return $this->belongsTo(Unit::class, 'unit_id', 'id')->select('id','name','type');
    }

    /**
     * Get all of the comments for the Products
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pdfFiles()
    {
        return $this->hasMany(ProductFile::class, 'product_id', 'id');
    }
}
