<?php

namespace App\Http\Controllers;

class AliExpressController
{
    public function index()
    {

        $data['product'] = $this->apiConnect();
        return view('aliexpress',$data);

    }

    public function apiConnect()
    {

    }

}
