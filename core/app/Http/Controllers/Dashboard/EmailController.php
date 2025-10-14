<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Section;
use App\Models\Email;
use App\Models\WebmasterSection;
use App\Models\Media;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\WebmasterSetting;
use Auth;
use File;
use Helper;
use Illuminate\Http\Request;
use Redirect;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;



class EmailController extends Controller
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
        $email_template = Email::get();
         // Paginate
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($email_template);
        $paginated = new LengthAwarePaginator(
            $collection->forPage($currentPage, $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current(), 'query' => request()->query()]
        );
        return view("dashboard.email_template.list", compact("paginated", "GeneralWebmasterSections"));
    }

    public function getData(Request $request)
    {
        $query = Email::query();
        if ($request->has('search') && $request->search['value'] != '') {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('identifier', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $totalData = $query->count();
        $totalFiltered = $totalData;
        $start = $request->input('start', 0);
        $limit = $request->input('length', 10);
        $draw = $request->input('draw', 1);
        $orderColumn = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'desc');
        $columns = $request->input('columns');
        if ($columns && isset($columns[$orderColumn])) {
            $orderField = $columns[$orderColumn]['data'];
            $query->orderBy($orderField, $orderDir);
        } else {
            $query->orderBy('id', 'desc');
        }

        $data = $query->offset($start)->limit($limit)->get();

        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'id' => $row->id,
                'check' => '<label class="ui-check m-a-0">
                                <input type="checkbox" name="ids[]" value="' . $row->id . '"><i></i>
                                <input type="hidden" name="row_ids[]" value="' . $row->id . '" class="form-control row_no">
                            </label>',
                'identifier' => $row->identifier,
                'Subject' => '<a class="dropdown-item" href="' . route('emailTemplateEdit', $row->slug) . '">'.$row->subject.'</a>',
                'status' => '<div class="text-center"><i class="fa ' . ($row->status ? 'fa-check text-success' : 'fa-times text-danger') . ' inline"></i></div>',
                'options' => '<div class="dropdown">
                                <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown">
                                    <i class="material-icons">&#xe5d4;</i> Options
                                </button>
                                <div class="dropdown-menu pull-right">
                                    <a class="dropdown-item" href="' . route('emailTemplateEdit', $row->slug) . '">
                                        <i class="material-icons">&#xe3c9;</i> Edit
                                    </a>
                                </div>
                            </div>',
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $result,
        ]);
    }

    public function create()
    {
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END
        return view("dashboard.email_template.create", compact("GeneralWebmasterSections"));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'identifier' => 'required',
            'subject' => 'required',
            'content' => 'required'
        ]);
        // Upload multiple photos
        $formFileName = "photo";
        $uploadedFileNames = []; // To store all uploaded file names

        if ($request->hasFile($formFileName)) {
            foreach ($request->file($formFileName) as $file) {
                if ($file && $file->isValid()) {
                    $fileFinalName = time() . rand(1111, 9999) . '.' . $file->getClientOriginalExtension();
                    $path = $this->uploadPath;

                    if (!file_exists($path)) {
                        mkdir($path, 0755, true);
                    }

                    $file->move($path, $fileFinalName);

                    // Optional: Resize & Optimize
                    Helper::imageResize($path . $fileFinalName);
                    Helper::imageOptimize($path . $fileFinalName);

                    $uploadedFileNames[] = $fileFinalName; // Store for reference (DB, etc.)
                }
            }
        }
        
        $emailTemplate = new Email;
        $emailTemplate->identifier  = $request->identifier;
        $emailTemplate->to_reciever  = $request->to_reciever;
        $emailTemplate->subject = $request->subject;
        $emailTemplate->content  = $request->content;
        $emailTemplate->status  = $request->status;
        $emailTemplate->save();

        if(count($uploadedFileNames) > 0){
            for($i=0;$i<count($uploadedFileNames);$i++){
                $media = new Media;
                $media->module  = 'mail_template';
                $media->module_id = $birthdayPackages->id;
                $media->filename  = $uploadedFileNames[$i];
                $media->original_name = $uploadedFileNames[$i];
                $media->file_url = $this->uploadPath.$uploadedFileNames[$i];
                $media->mime_type = '';
                $media->file_type  = 'image';
                $media->save();
            }
        }
        return redirect()->action('Dashboard\EmailController@index')->with('doneMessage', __('backend.addDone'));
    }

    public function clone($webmasterId, $id)
    {
        
    }

    public function edit($slug)
    {
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $email_template = Email::where('slug',$slug)->first();
        return view("dashboard.email_template.edit", compact("email_template", "GeneralWebmasterSections"));
    }

    public function update(Request $request, $id)
    {
       $emailTemplate = Email::where('slug',$id)->first();
       if (!empty($emailTemplate)) {
            $this->validate($request, [
                'identifier' => 'required',
                'subject' => 'required',
                'content' => 'required'
            ]);

            if ($request->has('media_delete')) {
                foreach ($request->input('media_delete') as $mediaId => $shouldDelete) {
                    if ($shouldDelete == '1') {
                        $media = Media::find($mediaId);
                        if ($media) {
                            $filePath = public_path('uploads/sections/' . $media->filename);
                            if (file_exists($filePath)) {
                                unlink($filePath);
                            }
                            $media->delete();
                        }
                    }
                }
            }
            $formFileName = "photo";
            $uploadedFileNames = []; // To store all uploaded file names

            if ($request->hasFile($formFileName)) {
                foreach ($request->file($formFileName) as $file) {
                    if ($file && $file->isValid()) {
                        $fileFinalName = time() . rand(1111, 9999) . '.' . $file->getClientOriginalExtension();
                        $path = $this->uploadPath;

                        if (!file_exists($path)) {
                            mkdir($path, 0755, true);
                        }

                        $file->move($path, $fileFinalName);

                        // Optional: Resize & Optimize
                        Helper::imageResize($path . $fileFinalName);
                        Helper::imageOptimize($path . $fileFinalName);

                        $uploadedFileNames[] = $fileFinalName; // Store for reference (DB, etc.)
                    }
                }
            }

            $emailTemplate->identifier  = $request->identifier;
            $emailTemplate->to_reciever  = $request->to_reciever;
            $emailTemplate->subject = $request->subject;
            $emailTemplate->content  = $request->content;
            $emailTemplate->status  = $request->status;
            $emailTemplate->save();
            if(count($uploadedFileNames) > 0){
                for($i=0;$i<count($uploadedFileNames);$i++){
                    $media = new Media;
                    $media->module  = 'mail_template';
                    $media->module_id = $emailTemplate->id;
                    $media->filename  = $uploadedFileNames[$i];
                    $media->original_name = $uploadedFileNames[$i];
                    $media->file_url = $this->uploadPath.$uploadedFileNames[$i];
                    $media->mime_type = '';
                    $media->file_type  = 'image';
                    $media->save();
                }
            }
            return redirect()->action('Dashboard\EmailController@edit', [$id])->with('doneMessage',
                __('backend.saveDone'));

       }else{
            return redirect()->action('Dashboard\EmailController@index');
       }
    }

    public function smtpConfigure()
    {
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END
        return view("dashboard.email_template.smtp_configure", compact("GeneralWebmasterSections"));
    }

    public function updateSmtp(Request $request)
    {
        //
        $WebmasterSetting = WebmasterSetting::find(1);
        if (!empty($WebmasterSetting)) {

            $WebmasterSetting->mail_driver = ($request->mail_driver != "") ? $request->mail_driver : "smtp";
            $WebmasterSetting->mail_host = ($request->mail_host != "") ? $request->mail_host : "";
            $WebmasterSetting->mail_port = ($request->mail_port != "") ? $request->mail_port : "";
            $WebmasterSetting->mail_username = ($request->mail_username != "") ? $request->mail_username : "";
            $WebmasterSetting->mail_password = ($request->mail_password != "") ? $request->mail_password : "";
            $WebmasterSetting->mail_encryption = ($request->mail_encryption != "") ? $request->mail_encryption : "";
            $WebmasterSetting->mail_no_replay = ($request->mail_no_replay != "") ? $request->mail_no_replay : "";
            $WebmasterSetting->mail_title = $request->mail_title;
            $WebmasterSetting->mail_template = $request->mail_template;
            $WebmasterSetting->save();

            $OLD_BACKEND_PATH = config('smartend.backend_path');
            if ($request->backend_path == "") {
                $request->backend_path = "admin";
            }
            // Update .env file
            $env_update = $this->changeEnv([
                'MAIL_DRIVER' => $request->mail_driver,
                'MAIL_HOST' => $request->mail_host,
                'MAIL_PORT' => $request->mail_port,
                'MAIL_USERNAME' => $request->mail_username,
                'MAIL_PASSWORD' => $request->mail_password,
                'MAIL_ENCRYPTION' => $request->mail_encryption,
                'MAIL_FROM_ADDRESS' => $request->mail_no_replay,
            ]);

            // clear old sessions
            \Session()->forget('_Loader_WebmasterSettings');
            \Session()->forget('_Loader_Web_Settings');
            \Session()->forget('_Loader_Languages');
            \Session()->forget('_Loader_Events');
            \Session()->forget('_Loader_WebmasterSections');

            // clear cache & views cache
            Artisan::call('cache:clear');
            Artisan::call('view:clear');

            // delete cache files manually
            if (\File::exists(base_path("bootstrap/cache/config.php"))) {
                \File::delete(base_path("bootstrap/cache/config.php"));
            }
            if (\File::exists(base_path("bootstrap/cache/routes-v7.php"))) {
                \File::delete(base_path("bootstrap/cache/routes-v7.php"));
            }

            // re cache routes
            Artisan::call('route:cache');
            // re cache config
            Artisan::call('config:cache');
            sleep(4);
            return redirect()->action('Dashboard\EmailController@smtpConfigure')->with('doneMessage', __('backend.addDone'));
        } else {
            return redirect()->action('Dashboard\EmailController@smtpConfigure');
        }
    }

    public function changeEnv($data = array())
    {
        if (count($data) > 0) {

            // Read .env-file
            $env = file_get_contents(base_path() . '/.env');

            // Split string on every " " and write into array
            $env = preg_split('/\s+/', $env);;


            // Loop through given data
            foreach ((array)$data as $key => $value) {

                // add KEY if not exist
                $KEY_EXIST = 0;
                foreach ($env as $env_key => $env_value) {
                    $entry = explode("=", $env_value, 2);
                    if ($entry[0] == $key) {
                        $KEY_EXIST = 1;
                    }
                }
                if (!$KEY_EXIST) {
                    $env[$key] = $key . "=";
                }

                // Loop through .env-data
                foreach ($env as $env_key => $env_value) {

                    // Turn the value into an array and stop after the first split
                    // So it's not possible to split e.g. the App-Key by accident
                    $entry = explode("=", $env_value, 2);

                    // Check, if new key fits the actual .env-key
                    if ($entry[0] == $key) {
                        // If yes, overwrite it with the new one
                        $env[$env_key] = $key . "=" . $value;
                        config(['smartend.' . strtolower($key) => $value]);
                    } else {
                        // If not, keep the old one
                        $env[$env_key] = $env_value;
                        config(['smartend.' . strtolower($env_key) => $env_value]);
                    }
                }
            }

            // Turn the array back to an String
            $env = implode("\n", $env);

            // And overwrite the .env with the new data
            file_put_contents(base_path() . '/.env', $env);

            return true;
        } else {
            return false;
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
