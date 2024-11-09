<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Url;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Rules\ValidUrl;

class ShortLinkController extends Controller
{

    public function index(Request $request)
    {
        $format_date = null;
        try {
            $carbon_date = Carbon::createFromFormat('d/m/Y', $request->data_search);
            if ($carbon_date) {
                $format_date = $carbon_date->format('Y-m-d');
            }
        } catch (\Exception $e) {
            //
        }
        $data_search = $request->data_search;

        $checkRole = Auth::user()->role;
        $lists = Url::with('user')
        ->leftjoin('users', 'users.id','=','urls.user_id')
        ->when($checkRole !== 'ADMIN', function ($query) {
            return $query->where('user_id', Auth::user()->id);
        })
            ->when($request->data_search && is_null($format_date), function ($query) use ($data_search) {
                return $query->where(function ($subquery) use ($data_search) {
                    $subquery->where('urls.link_name', 'like', "%$data_search%")
                        ->orWhere('urls.original_url', 'like', "%$data_search%")
                        ->orWhere('urls.short_path_name', 'like', "%$data_search%")
                        ->orWhere('users.name', 'like', "%$data_search%");
                });
            })
            ->when($format_date, function ($query, $format_date) {
                return $query->where(function ($subquery) use ($format_date) {
                    $subquery->whereDate('urls.expire_date', $format_date);
                });
            })
            ->select('urls.*')
            ->orderBy('urls.created_at', 'desc')
            ->paginate(10);

        return view('short-links.index', [
            'lists' => $lists,
            'data_search' => $data_search,
        ]);
    }

    public function create()
    {
        $prefix_url = request()->getSchemeAndHttpHost() . '/myshort-';

        return view('short-links.short-link-form', [
            'prefix_url' => $prefix_url,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $url_model = Url::firstOrNew(['id' => $request->id]);
        $request->validate(
            [
                'original_link' => ['required', 'string', new ValidUrl],
                'short_link' => ['required', 'string', Rule::unique('urls', 'short_url')->ignore($url_model)],
                'link_name' => ['required', 'string'],
                'is_expire' => ['required'],
                'expire_date' => [Rule::when($request->is_expire == 1, ['required'])],
            ],
            [
                'original_link.url_fail' => 'รูปแบบลิงก์ไม่ถูกต้อง',
            ],
            [
                'original_link' => 'ลิงก์ที่ต้องการแปลง',
                'short_link' => 'ชื่อลิงก์ใหม่',
                'link_name' => 'ชื่อ / รายละเอียดลิงก์',
                'is_expire' => 'ต้องการตั้งค่าวันหมดอายุหรือไม่',
                'expire_date' => 'วันหมดอายุของลิงก์',
            ]
        );


        $prefix_url = request()->getSchemeAndHttpHost() . '/myshort-';
        if ($url_model) {
            $url_model->original_url = $request->original_link;
            $url_model->short_url = $request->short_link;
            $url_model->short_path_name = $prefix_url . $request->short_link;
            $url_model->is_expire = $request->is_expire;
            if ($request->is_expire) {
                try {
                    $carbon_date = Carbon::createFromFormat('d/m/Y', $request->expire_date);
                    if ($carbon_date) {
                        $format_date = $carbon_date->format('Y-m-d');
                        $url_model->expire_date = $format_date;
                    }
                } catch (\Exception $e) {
                    //
                }
            } else {
                $url_model->expire_date = null;
            }
            $url_model->link_name = $request->link_name;
            $url_model->user_id = Auth::user()->id;
        }

        $url_model->save();

        return redirect(route('shortest-links.index'));
    }

    public function edit(Url $shortest_link)
    {
        $prefix_url = request()->getSchemeAndHttpHost() . '/myshort-';
        return view('short-links.short-link-form', [
            'd' => $shortest_link,
            'prefix_url' => $prefix_url,
        ]);
    }

    public function destroy($id)
    {
        $url = Url::find($id);
        $url->delete();
        return response()->json(['success' => true]);
    }
}
