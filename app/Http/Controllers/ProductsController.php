<?php namespace CodeCommerce\Http\Controllers;

use CodeCommerce\Category;
use CodeCommerce\Product;
use CodeCommerce\Http\Requests;
use CodeCommerce\Http\Controllers\Controller;
use Cagartner\CorreiosConsulta\CorreiosConsulta;
use CodeCommerce\ProductImage;
use CodeCommerce\Tag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class ProductsController extends Controller {

	private $productModel;

	public function __construct(Product $productModel)
	{
		$this->productModel = $productModel;
	}

	public function index(){


		$products = $this->productModel->paginate(10);

		return view('products.index', compact('products'));
	}

	public function endereco(){
        $consulta = new CorreiosConsulta;
        echo "<h1>CEP: 21380010</h1>";
        echo "<pre>";
        print_r($consulta->cep('21380010'));

        exit;

    }


	public function create(Category $category, Tag $tag){

		$tags = $tag->lists('name','id');

		$categories = $category->lists('name','id');

		return view('products.create', compact('categories', 'tags'));
	}

	private function storeTag($inputTags, $id)
	{
		$tag = new Tag();

		$countTags = count($inputTags);

		foreach ($inputTags as $key => $value) {

			$newTag = $tag->firstOrCreate(["name" => $value]);
			$idTags[] = $newTag->id;
		}

		$product = $this->productModel->find($id);
		$product->tags()->sync($idTags);

	}

public function store(Requests\ProductRequest $request){

	$params = $request->all();

	$product = $this->productModel->fill($params);
	$product->save();

	//$product = $this->productModel->fill($request->all());

	//$product->save();

	$tags = $params['tags'];
	$tagsProduct = $product->tagToArray($tags);
	$product->tags()->sync($tagsProduct);

	//$inputTags = array_map('trim', explode(',', $request->get('tags')));

	//$this->storeTag($inputTags,$product->id);

	return redirect()->route('products');


	/*
    $request['featured'] = $request->get('featured');
    $request['recommended'] = $request->get('recommended');

    $input = $request->all();
    $product = $this->productModel->fill($input);
    $product->save();
    return redirect()->route('products', compact('product'));
	*/

	}

	public function edit($id, Category $category){

        $categories = $category->lists('name','id');

		$product = $this->productModel->find($id);
		return view('products.edit', compact('product','categories'));
	}

	public function update(Requests\ProductRequest $request, $id){



		$this->productModel->find($id)->update($request->all());
		

		//$tags = $request['tags'];
		//$tagsProduct = $this->productModel->tagToArray($tags);
		//$this->productModel->tags()->sync($tagsProduct);

		//$id = $request['id'];
		//$produto = $this->productModel->find($id);
		//$produto->fill($request->all());
		//$produto->save();
		//$tags = $request['tags'];
		//$tagsProduct = $produto->tagToArray($tags);
		//$produto->tags()->sync($tagsProduct);

		return redirect()->route('products');
	}


	public function destroy($id){
		$this->productModel->find($id)->delete();
		return redirect()->route('products');
	}

	public function images($id){

		$product = $this->productModel->find($id);

		return view('products.images', compact('product'));
	}

	public function createImage($id){
		$product = $this->productModel->find($id);

		return view('products.create_image', compact('product'));
	}

	public function storeImage(Requests\ProductImageRequest $request ,$id, ProductImage $productImage){

		$file = $request->file('image');
		$extension = $file->getClientOriginalExtension();
		$image = $productImage::create(['product_id'=>$id, 'extension'=>$extension]);
		Storage::disk('public_local')->put($image->id.'.'.$extension, File::get($file));

		return redirect()->route('products.images', ['id'=>$id]);
	}

	public function destroyImage($id, ProductImage $productImage){

		$image = $productImage->find($id);

		if(file_exists(public_path().'/uploads/'.$image->id.'.'.$image->extension)){
			Storage::disk('public_local')->delete($image->id.'.'.$image->extension);
		}


		$product = $image->product;
		$image->delete();

		return redirect()->route('products.images', ['id'=>$product->id]);
	}



}
