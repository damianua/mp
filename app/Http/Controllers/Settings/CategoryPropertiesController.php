<?php

namespace App\Http\Controllers\Settings;

use App\Http\Adapters\Settings\CategoryPropertiesAdapter;
use App\Models\Category;
use App\Services\CategoryPropertiesAssociationService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryPropertiesController extends Controller
{
	public function __construct()
	{
		$this->middleware('can:associateWithProperties,'.Category::class);
	}

	public function index(Category $category = null)
    {
    	$service = new CategoryPropertiesAssociationService($category);
    	$adapter = new CategoryPropertiesAdapter($service);

    	return view('settings.category_properties', $adapter->getData());
    }
}
