<?php
namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        try{
            return view('home/index');
        }catch(\Exception $ex){
            die('Error: ' . $ex->getMessage());
        }
    }
}