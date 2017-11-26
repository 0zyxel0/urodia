<?php

namespace App\Http\Controllers;

use App\listCategory;
use App\listChildCategory;
use Faker\Provider\Uuid;
use App\Patient;
use App\checkupRecord;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


   public function dashboard()
    {
       return view('content.content-dashboard');
    }

  public function profile()
    {
       return view('content.content-profile');
    }

    public function reports()
    {
        return view('content.content-reports');
    }


  public function test()
    {
       return view('content.test');
    }

    public function viewPatientRecords()
    {
        $data = Patient::all ();
       // dd($data);
       return view('content.content-patientRecords')->withData ( $data );
    }

    public function addPatientRecords()
    {
        return view('content.content-newRecord');
    }

  public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return view('content.content-dashboard');
    }

    public function viewCheckupRecords()
    {
        $data2 = checkupRecord::all ();
        dd($data2);

        //return view('content.content-checkup')->withData ( $data2 );
    }

    public function addCategoryListItem()
    {
        $data = listCategory::all ();
        $convs = json_encode($data);
        return view('content.content-createlist',['data'=>json_decode($convs,true)]);
    }

    public function addChildItem()
    {

        $items = listCategory::all(['categoryid', 'categoryname']);
        $convs = json_encode($items);

       
        $item2 = listChildCategory::join('list_Categories', 'list_Child_Categories.parentCategory' ,'=', 'list_Categories.categoryid')->get();

        $convs2 = json_encode($item2);
dd($convs2 , $convs);

        //return view('content.content-childList', ['data'=>json_decode($convs,true),'data2'=>json_decode($convs2,true)]);

    }

    public function saveNewCategory(Request $request)
    {


        $categoryid = Uuid::uuid();
        $categoryname = $request->input('catName');
        $categoryOrder = $request->input('catGroup');
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');

        $data = array(
            'categoryid'=>$categoryid,
            'categoryname'=>$categoryname,
            'categoryOrder'=>$categoryOrder,
            'created_at'=>$created_at,
            'updated_at'=>$updated_at
        );

        DB::table('list_categories')->insert($data);
        return redirect('addCategoryList');
       
    }


    public function saveChildCategory(Request $request)
    {
        $childCategoryid = Uuid::uuid();
        $parentCategory = $request->input('parentCat');
        $categoryname = $request->input('catName');
        $categoryOrder = $request->input('catGroup');
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');

        $data = array(
            'childCategoryid'=>$childCategoryid,
            'parentCategory'=>$parentCategory,
            'categoryname'=>$categoryname,
            'categoryOrder'=>$categoryOrder,
            'created_at'=>$created_at,
            'updated_at'=>$updated_at
        );

        DB::table('list_child_categories')->insert($data);
        return redirect('addChildItem');

    }

}
