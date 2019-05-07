<?php


namespace App\Services\Stateless;


use App\Models\ProductProperty;
use Illuminate\Support\Collection;

class ProductPropertyService extends AbstractModelService
{
	/**
	 * @var CategoryService
	 */
	private $categoryService;

	public function __construct()
	{
		$this->categoryService = app('CategoryService');
	}

	public function getModelClass(): string
	{
		return ProductProperty::class;
	}

	public function getActive()
	{
		return $this->getAll()->filter(function(ProductProperty $productProperty){
			return $productProperty->active;
		});
	}

	/**
	 * @return ProductProperty[]|Collection
	 */
	public function getAll()
	{
		return $this->cache()->remember('product_properties.all', 3600, function(){
			return $this->get(ProductProperty::orderBy('sort'));
		});
	}

	public function createProductProperty(array $attributes)
	{
		$productProperty = ProductProperty::create($attributes)->fresh();
		//по-умолчанию при добавлении нового активного свойства оно автоматом должно привязываться к категории товара
		if($productProperty->active){
			foreach($this->categoryService->getActive() as $category){
				$category->productProperties()->attach($productProperty->id);
			}
		}

		$this->cache()->forget('product_properties.all');

		return $productProperty;
	}

	public function findProductPropertyOrFail($productProperty)
	{
		return $this->findOrFail($productProperty);
	}
}