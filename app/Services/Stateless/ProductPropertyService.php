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

	public function getByExternalId(string $externalId): ?ProductProperty
    {
        return ProductProperty::where('external_id', $externalId)->first();
    }

	public function getModelClass(): string
	{
		return ProductProperty::class;
	}

	public function getActive(): Collection
	{
		return $this->getAll()->filter(function(ProductProperty $productProperty){
			return $productProperty->active;
		});
	}

	/**
	 * @return ProductProperty[]|Collection
	 */
	public function getAll(): Collection
	{
		return $this->cache()->remember('product_properties.all', 3600, function(){
			return $this->get(ProductProperty::orderBy('sort'));
		});
	}

	public function updateProductProperty($productProperty, array $attributes)
    {
        $productProperty = $this->findProductPropertyOrFail($productProperty);

        return $productProperty
            ->fill($attributes)
            ->save();
    }

	public function createProductProperty(array $attributes): ProductProperty
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

	public function findProductPropertyOrFail($productProperty): ProductProperty
	{
		return $this->findOrFail($productProperty);
	}
}