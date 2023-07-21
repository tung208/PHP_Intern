<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\MultiImage;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Image;

class AboutController extends Controller
{
    //
    public function aboutPage()
    {
        $aboutPage = About::find(1);
        return View('admin.about_page.about_page_all', compact('aboutPage'));
    }

    public function updateAboutPage(Request $request)
    {
        $about_id = @$request->id;
        if ($request->file('about_image')) {
            $image = $request->file('about_image');
            $name_generate = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(523, 603)->save('upload/home_about/' . $name_generate);
            $save_url = 'upload/home_about/' . $name_generate;
            About::findOrFail($about_id)->update([
                'title' => $request->title,
                'short_title' => $request->short_title,
                'short_description' => $request->short_description,
                'long_description' => $request->long_description,
                'about_image' => $save_url

            ]);
            $notification = array(
                'message' => 'Update About Page With Image Successfully !',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        } else {
            About::findOrFail($about_id)->update([
                'title' => $request->title,
                'short_title' => $request->short_title,
                'short_description' => $request->short_description,
                'long_description' => $request->long_description

            ]);
            $notification = array(
                'message' => 'Update About Page Without Image Successfully !',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        }
    }

    public function homeAbout()
    {
        $aboutPage = About::find(1);
        return View('frontend.about_page', compact('aboutPage'));
    }

    public function aboutMultiImage()
    {
        return view('admin.about_page.multi_image');
    }

    public function storeMultiImage(Request $request)
    {
        $image = $request->file('multi_image');
        foreach ($image as $multi_image) {
            $name_generate = hexdec(uniqid()) . '.' . $multi_image->getClientOriginalExtension();
            Image::make($multi_image)->resize(220, 220)->save('upload/multi/' . $name_generate);
            $save_url = 'upload/multi/' . $name_generate;
            MultiImage::insert([
                'multi_image' => $save_url,
                'created_at' => Carbon::now()

            ]);
        }
        $notification = array(
            'message' => 'Add Multi Image Successfully !',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    }

    public function allMultiImage()
    {
        $allMultiImage = MultiImage::all();
        return view('admin.about_page.all_multiImage', compact('allMultiImage'));
    }

    public function editMultiImage($id)
    {
        $multiImage = MultiImage::findOrFail($id);
        return view('admin.about_page.edit_multiImage', compact('multiImage'));

    }

    public function updateMultiImage(Request $request)
    {
        $multi_image_id = @$request->id;
        if ($request->file('multi_image')) {
            $image = $request->file('multi_image');
            $name_generate = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(220, 220)->save('upload/multi/' . $name_generate);
            $save_url = 'upload/multi/' . $name_generate;
            MultiImage::findOrFail($multi_image_id)->update([
                'multi_image' => $save_url

            ]);
            $notification = array(
                'message' => 'Update Multi Image Successfully !',
                'alert-type' => 'success'
            );
            return redirect()->route('all.multi.image')->with($notification);
        }
    }
    public function deleteMultiImage($id){
        $multi = MultiImage::findOrFail($id);
        $img = $multi -> multi_image;
        unlink($img);
        MultiImage::findOrFail($id)-> delete();
        $notification = array(
            'message' => 'Delete Multi Image Successfully !',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
}
