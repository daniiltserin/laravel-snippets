<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use App\Product;
use Dingo\Api\Routing\Helpers;

class ApiBaseController extends Controller
{
    use Helpers;
    public function test()
    {
        $products = Product::all();
        return $this->response()->array($products->toArray());

    }

    public function err()
    {
        return $this->response->error('This is an error. Fuck you', 404);
    }


}
