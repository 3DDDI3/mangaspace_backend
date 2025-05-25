<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *      title="Category",
 *      description="Category model",
 *      @OA\Xml(
 *          name="Category"
 *      )
 * )
 */
class Category extends Model
{
    /**
     * @OA\Property(
     *      title="id",
     *      description="ID категории",
     *      type="integer",
     *      example=1
     * )
     *
     * @var id
     */
    private $id;

    /**
     * @OA\Property(
     *      title="categoey",
     *      description="Название категории",
     *      type="integer",
     *      example=1
     * )
     *
     * @var categoey
     */
    private $categoey;

    use HasFactory;

    protected $connection = "temp";

    protected $table = "categories";

    protected $fillable = [
        'category',
    ];
}
