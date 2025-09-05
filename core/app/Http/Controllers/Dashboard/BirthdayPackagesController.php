<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Section;
use App\Models\BirthdayPackages;
use App\Models\WebmasterSection;
use Illuminate\Pagination\LengthAwarePaginator;
use Auth;
use File;
use Helper;
use Illuminate\Http\Request;
use Redirect;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;



class BirthdayPackagesController extends Controller
{
    private $uploadPath = "uploads/sections/";

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function index()
    {
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $birthday_packages = BirthdayPackages::get();
         // Paginate
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($birthday_packages);
        $paginated = new LengthAwarePaginator(
            $collection->forPage($currentPage, $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current(), 'query' => request()->query()]
        );
        return view("dashboard.birthday_packages.list", compact("paginated", "GeneralWebmasterSections"));
    }

    public function create()
    {
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        return view("dashboard.birthday_packages.create",compact("GeneralWebmasterSections"));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'map_link' => 'required'
        ]);

        // Upload photo
        $formFileName = "photo";
        $fileFinalName = "";
        if ($request->$formFileName != "") {
            $fileFinalName = time() . rand(1111, 9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
            $path = $this->uploadPath;

            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $request->file($formFileName)->move($path, $fileFinalName);
            Helper::imageResize($path . $fileFinalName);
            Helper::imageOptimize($path . $fileFinalName);
        }

        // Upload banner_image
        $formFileName2 = "banner_image";
        $fileFinalName2 = "";
        if ($request->$formFileName2 != "") {
            $fileFinalName2 = time() . rand(1111, 9999) . '.' . $request->file($formFileName2)->getClientOriginalExtension();
            $path = $this->uploadPath;

            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $request->file($formFileName2)->move($path, $fileFinalName2);
            Helper::imageResize($path . $fileFinalName2);
            Helper::imageOptimize($path . $fileFinalName2);
        }
        $birthdayPackages = new BirthdayPackages;
        $birthdayPackages->title  = $request->title;
        $birthdayPackages->description = $request->description;
        if ($fileFinalName != "") {
            $birthdayPackages->image_url = $fileFinalName;
        }
        if ($fileFinalName2 != "") {
            $birthdayPackages->banner_image = $fileFinalName2;
        }
        $birthdayPackages->price  = $request->price;
        $birthdayPackages->map_link = $request->map_link;
        $birthdayPackages->status = $request->status;
        $birthdayPackages->save();

        return redirect()->action('Dashboard\BirthdayPackagesController@index')->with('doneMessage', __('backend.addDone'));
    }

    public function clone($webmasterId, $id)
    {
        
    }

    public function edit($slug)
    {
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $birthday_packages = BirthdayPackages::where('slug',$slug)->first();
        return view("dashboard.birthday_packages.edit", compact("birthday_packages", "GeneralWebmasterSections"));
    }

    public function update(Request $request, $id)
    {
       $birthdayPackages = BirthdayPackages::where('slug',$id)->first();
       if (!empty($birthdayPackages)) {
            $this->validate($request, [
                'title' => 'required',
                'description' => 'required',
                'price' => 'required',
                'map_link' => 'required'
            ]);

            // Start of Upload Files
            $formFileName = "photo";
            $fileFinalName = "";
            if ($request->$formFileName != "") {
                // Delete a Section photo
                if ($birthdayPackages->photo != "") {
                    File::delete($this->uploadPath . $birthdayPackages->photo);
                }

                $fileFinalName = time() . rand(1111,
                        9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
                $path = $this->uploadPath;
                $request->file($formFileName)->move($path, $fileFinalName);

                // resize & optimize
                Helper::imageResize($path . $fileFinalName);
                Helper::imageOptimize($path . $fileFinalName);
            }

            // Upload banner_image
            $formFileName2 = "banner_image";
            $fileFinalName2 = "";
            if ($request->$formFileName2 != "") {
                $fileFinalName2 = time() . rand(1111, 9999) . '.' . $request->file($formFileName2)->getClientOriginalExtension();
                $path = $this->uploadPath;

                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }

                $request->file($formFileName2)->move($path, $fileFinalName2);
                Helper::imageResize($path . $fileFinalName2);
                Helper::imageOptimize($path . $fileFinalName2);
            }
            // End of Upload Files

            $birthdayPackages->title  = $request->title;
            $birthdayPackages->description = $request->description;
            if ($fileFinalName != "") {
                $birthdayPackages->image_url = $fileFinalName;
            }
            if ($fileFinalName2 != "") {
                $birthdayPackages->banner_image = $fileFinalName2;
            }
            $birthdayPackages->price  = $request->price;
            $birthdayPackages->map_link = $request->map_link;
            $birthdayPackages->status = $request->status;
            $birthdayPackages->save();
            return redirect()->action('Dashboard\BirthdayPackagesController@edit', [$id])->with('doneMessage',
                __('backend.saveDone'));

       }else{
            return redirect()->action('Dashboard\BirthdayPackagesController@index');
       }
    }

    public function seo(Request $request, $webmasterId, $id)
    {
        
    }

    public function destroy($webmasterId, $id = 0)
    {
        
    }

    public function updateAll(Request $request, $webmasterId)
    {
       
    }


}
