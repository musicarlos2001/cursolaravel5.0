<?php namespace CodeCommerce\Http\Controllers;

use CodeCommerce\Product;
use CodeCommerce\Http\Requests;
use CodeCommerce\Http\Controllers\Controller;
use Cagartner\CorreiosConsulta\CorreiosConsulta;




class ProductsController extends Controller {

	private $productModel;

	public function __construct(Product $productModel)
	{
		$this->productModel = $productModel;
	}

	public function index(){

		$products = $this->productModel->all();
		return view('products.index', compact('products'));
	}

	public function endereco(){
        $consulta = new CorreiosConsulta;
        echo "<h1>CEP: 21380010</h1>";
        echo "<pre>";
        print_r($consulta->cep('21380010'));

        exit;

    }


	public function create(){
		return view('products.create');
	}

	public function store(Requests\ProductRequest $request){

		$request['featured'] = $request->get('featured');
		$request['recommended'] = $request->get('recommended');

		$input = $request->all();
		$product = $this->productModel->fill($input);
		$product->save();
		return redirect()->route('products');
	}

	public function edit($id){
		$product = $this->productModel->find($id);
		return view('products.edit', compact('product'));
	}

	public function update(Requests\ProductRequest $request, $id){

		$this->productModel->find($id)->update($request->all());
		return redirect()->route('products');
	}


	public function destroy($id){
		$this->productModel->find($id)->delete();
		return redirect()->route('products');
	}


}
