<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::pattern('id', '[0-9]+');

Route::group(['prefix' => 'admin'], function() {
	Route::group(['prefix' => 'categories'], function() {
		Route::get('/', 'AdminCategoriesController@index');

	});

	Route::group(['prefix' => 'products'], function(){
		Route::get('/', 'AdminProductsController@index');

	});

	Route::group(['prefix' => 'categories'], function(){
		Route::get('/{id}', function($id){

			$category = new \CodeCommerce\Category();
			$c = $category->find($id);
			return "Nome: ". $c->name ."<br/>".
					"Descrição:" .$c->description;

		});
	});


	Route::group(['prefix' => 'products'], function(){
		Route::get('/{id}', function($id){

			$product = new \CodeCommerce\Product();
			$p = $product->find($id);
			return "Nome:" .$p->name ."<br/>".
			       "Descrição:" .$p->description ."<br/>".
			       "Preço:" .$p->price;

		});

	});



});




/*

Route::get('category/{category}', function(\CodeCommerce\Category $category){
	return $category->name ."<br/>". $category->description;
});

Route::get('product/{id}', function($id){

	$product = new \CodeCommerce\Product();
	$p = $product->find($id);
	return $p->name ."<br/>". $p->description;

});

Route::get('user/{id?}', function($id = 0){

	if($id)
		return "Olá $id";
	return "Não Possui ID";

})->where('id', '[0-9]+');

Route::get('testando',['as'=>'produtos', function(){
	echo Route::currentRouteName();
		//return "rotas";

}]);


//redirect()->route('produtos');
//echo route('produtos');die;

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::get('/exemplo', 'WelcomeController@exemplo');

//Route::get('admin/categories', 'AdminCategoriesController@index');

//Route::get('admin/products', 'AdminProductsController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

*/