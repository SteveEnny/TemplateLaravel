<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TemplateCollection;
use App\Http\Resources\TemplateResource;
use App\Mail\RequestTemplate;
use App\Models\Api\V1\Template;
use Cloudinary\Cloudinary;
// use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Str;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $cloudinary;

    public function index()
    {
        // $plan = request()->only('plan');
        $plan = request()->query('plan');
        
        $templates = Template::query()->filter($plan)->get();
        return new TemplateCollection($templates);
        // return $plan;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required | string",
            "price" => "required | string",
            "plan" => "required | string",
            "imagepath" => "required |mimes:png,jpg,jpeg,webp | max:2048",

        ]);

        try {
            $reqImg = $request->file('imagepath')->getRealPath();

            $uploadedImg = cloudinary()->upload($reqImg);
            $imgUrl = $uploadedImg->getSecurePath();
            $public_id = $uploadedImg->getPublicId();
    
            return Template::create([
            'name' => $request->name,
            'price' => $request->price,
            'plan' => $request->plan,
            'imagepath' => $imgUrl,
            'public_id' => $public_id,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        // $imgName = time() . '.' . $reqImg->getClientOriginalExtension();
        // $filePath = $reqImg->storeAs('uploads', $imgName, 'public');
        // $uploadedImg = Storage::disk('public')->put('/', $reqImg);


        // // $imageUrl =  config('services.baseUrl') . Storage::url($uploadedImg);
        // $imageUrl =  config('services.baseUrl') . Storage::url($uploadedImg);
    
        // return Template::create([
        //     'name' => $request->name,
        //     'price' => $request->price,
        //     'plan' => $request->plan,
        //     // 'imagepath' => $reqImg,
        // ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Template $template)
    {
        return new TemplateResource($template);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Template $template)
    {
        $updateTemplate = $request->validate([
            "name" => "sometimes | string",
            "price" => "sometimes | string",
            "plan" => "sometimes | string",
            "imagepath" => "sometimes |mimes:png,jpg,jpeg,webp | max:2048",

        ]);
        $publicId = $template->public_id;
    
        try {
            // Upload new image and overwrite existing one
            $uploadedFileUrl = cloudinary()->upload($request->file('image')->getRealPath(), [
                'public_id' => $publicId, // Keep the same public_id
                'overwrite' => true,
            ])->getSecurePath();
    
            $template->update($updateTemplate);
            return new TemplateResource($template);

            // return response()->json(['url' => $uploadedFileUrl, 'public_id' => $publicId], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Template $template)
    {
        cloudinary()->destroy($template->public_id);
        $template->delete();

        return response(status: 204);// 204 No Content
    }

    public function requestTemplate(Request $request) {
        $request->validate([
            "name" => "required|string",
            "email" => "required|email",
            "templateId" => 'required',
        ]);
        $template = Template::find($request->templateId);
        
        Mail::to(env('MAIL_USERNAME'))->send( new RequestTemplate( $request->email, $request->name,$template->name, $request->templateId));

        return response([
            "template" => "Template request processed successfully",
        ]);
    }
}