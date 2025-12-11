<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;
    public $incrementing = false;      
    protected $keyType = 'string';
    protected $fillable = ['kode_barang','userId','nama', 'harga', 'stock','gambar', 'kategori', 'satuan', 'bungkus','harga_bungkus','harga_satuan'];

        protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });

           static::addGlobalScope('byUser', function ($builder) {
            if (Auth::check()) { // Hanya terapkan scope jika user sedang login
                $builder->where('userId', Auth::user()->userId);
            }
        });
    }

      public function user()
    {
        // 'userId' adalah foreign key di tabel products
        // 'userId' adalah local key di tabel users (kolom yang dirujuk)
        return $this->belongsTo(User::class, 'userId', 'userId');
    }
}

