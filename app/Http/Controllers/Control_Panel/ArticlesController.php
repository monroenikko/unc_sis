<?php

namespace App\Http\Controllers\Control_Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use File;
use Carbon\Carbon;
use Auth;

use App\Article;

class ArticlesController extends Controller
{
     public function index (Request $request) 
    {
        if ($request->ajax())
        {
            $Article = \App\Article::where('status', 1)->where('title', 'like', '%'.$request->search.'%')->paginate(10);
            return view('control_panel.articles.partials.data_list', compact('Article'))->render();
        }
        $Article = \App\Article::where('status', 1)->paginate(10);
        return view('control_panel.articles.index', compact('Article'));
    }

    public function modal_data (Request $request) 
    {
        $Article = NULL;
        if ($request->id)
        {
            $Article = \App\Article::where('id', $request->id)->first();
        }
        return view('control_panel.articles.partials.modal_data', compact('Article'))->render();
    }

    public function save_data (Request $request) 
    {
        $Validator = Validator::make($request->all(),
            [
                'article_status'    => 'required',
                'featured_image'    => 'nullable|mimes:jpeg,png',
                'posting_date'      => 'required|date',
                'event_date'        => 'nullable',
                'school_year'       => 'nullable',
                'title'             => 'required',
                'level'             => 'required',
                'content'           => 'required',
                'multiple_level'    => 'required',
            ],
            [
                'article_status.required'    => 'Article status is required.',
                'featured_image.mimes'       => 'Featured image should be only either jpeg or png file.',
                'posting_date.required'      => 'Posting date is required.',
                'posting_date.date'          => 'Posting date should be a valid date format.',
                'title.required'             => 'Article title is required.',
                'level.required'             => 'Level is required.',
                'content.required'           => 'Articel content is required.',
                'multiple_level.required'    => 'Level is required.'
            ]
        );

        if ($Validator->fails())
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Please fill all required fields.', 'res_error_msg' => $Validator->getMessageBag()]);
        }
            
        if ($request->id) // this news is for edit
        {
            $Article = Article::where('id', $request->id)->first();

            if ($Article == NULL) // checks if there is an existing record base on id
            {
                // if no data
                return response()->json(['res_code' => 2, 'res_msg' => 'Invalid selection of article']);
            }
            
            // has selected article

	        $slug  = str_slug($request->title, '-');  

	        $Article->article_type  	= $request->article_type;
	        $Article->posting_date      = Carbon::parse($request->posting_date)->format('Y-m-d');
	        $Article->school_year       = $request->school_year;
	        $Article->title             = $request->title;
	        $Article->content           = $request->content;
	        $Article->level             = $request->multiple_level;
	        $Article->status            = $request->article_status;
	        $Article->user_id           = Auth::user()->id;
	        $Article->slug              = $slug;

            if($request->featured_image) // checks if there is upload featured image
            {   
                $full_filename = '';
                if($Article->featured_image != NULL) // checks if there is currently saved image to database
                {
                    $db_filename    = explode('.', $Article->featured_image);
                    $str_ext        = array_pop($db_filename);
                    $full_filename  = implode('.',$db_filename) . '.' . $request->featured_image->getClientOriginalExtension();
                }
                else // no image in the database
                {
                    $str_filename   = str_random(50);
                    $str_ext        = $request->featured_image->getClientOriginalExtension();
                    $full_filename = $str_filename . '.' . $str_ext;
                }

                $Article->featured_image = $full_filename;
                //upload image
                $request->featured_image->move(public_path('content/articles/featured_image/'), $full_filename);
            }

            $Article->save();

            return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully updated.', 'x' => $Article]);
        }

        $slug  = str_slug($request->title, '-');  

        $Article                    = new Article();
        $Article->article_type  	= $request->article_type;
        $Article->posting_date      = Carbon::parse($request->posting_date)->format('Y-m-d');
        $Article->school_year       = $request->school_year;
        $Article->title             = $request->title;
        $Article->content           = $request->content;
        $Article->level             = $request->multiple_level;
        $Article->status            = $request->article_status;
        $Article->user_id           = Auth::user()->id;
        $Article->slug              = $slug;

        if($request->featured_image) // checks if there is upload featured image
        {   
            $str_filename   = str_random(50);
            $str_ext        = $request->featured_image->getClientOriginalExtension();
            $full_filename = $str_filename . '.' . $str_ext;
            $Article->featured_image = $full_filename;

            //upload image
            $request->featured_image->move(public_path('content/articles/featured_image/'), $full_filename);
        }

        $Article->save();

        return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully saved.']);
    }
}
