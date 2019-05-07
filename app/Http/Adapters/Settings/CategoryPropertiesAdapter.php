<?php


namespace App\Http\Adapters\Settings;


use App\Http\Adapters\AbstractControllerAdapter;
use App\Models\Category;
use App\Models\ProductProperty;
use App\Services\CategoryPropertiesAssociationService;
use Illuminate\Support\Collection;

class CategoryPropertiesAdapter extends AbstractControllerAdapter
{
	/**
	 * @var CategoryPropertiesAssociationService
	 */
	private $service;

	public function __construct(CategoryPropertiesAssociationService $service)
	{
		$this->service = $service;
	}

	public function getData()
	{
		return [
			'categories' => $this->service->getCategories()->map(\Closure::fromCallable([$this, 'hydrateCategory'])),
			'currentCategory' => $this->hydrateCategory($this->service->getCategory()),
			'productProperties' => $this->hydrateProperties($this->service->getProductProperties())
		];
	}

	protected function hydrateCategory(Category $category)
	{
		return [
			'id' => $category->id,
			'name' => $category->name,
			'is_current' => $category->id === $this->service->getCategory()->id
		];
	}

	protected function hydrateProperties(Collection $productProperties)
	{
		$data = $productProperties->map(
			\Closure::fromCallable([$this, 'hydrateProperty'])
		)->toArray();

		uasort($data, function($propData1, $propData2){
			if($propData1['sort'] === $propData2['sort']){
				return $propData1['id'] <=> $propData2['id'];
			}
			return $propData1['sort'] <=> $propData2['sort'];
		});

		return $data;
	}

	protected function hydrateProperty(ProductProperty $property)
	{
		$inputPrefix = 'property.'.$property->id.'.';
		$categoryProperty = $this->service->getCategoryProperties()->get($property->id);
		$data = [
			'id' => $property->id,
			'name' => $property->name,
			'sort' => $categoryProperty ? $categoryProperty->pivot->sort : 100,
			'is_hidden' => !$categoryProperty,
            'is_required' => $categoryProperty ? $categoryProperty->pivot->require : false
		];
		$data['sort_value'] = old($inputPrefix.'sort', $data['sort']);
		$data['hide_value'] =  old($inputPrefix.'hide', $data['is_hidden']);
        $data['require_value'] =  old($inputPrefix.'require', $data['is_required']);

		return $data;
	}

	public function associate(array $propertiesData)
	{
		$syncData = [];
		foreach($propertiesData as $propertyId => $associateData){
			if(isset($associateData['hide'])){
				continue;
			}
			$syncData[$propertyId] = [
				'sort' => $associateData['sort'],
                'require' => isset($associateData['require'])
			];
		}

		$this->service->syncCategoryProperties($syncData);
	}
}