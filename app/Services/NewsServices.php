<?php

namespace App\Services;

use App\Contract\NewsInterface;
use App\File;
use App\Helper;
use App\Http\Requests\NewsRequest;
use App\News;
use App\NewsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsServices implements NewsInterface {

    private $news;

    public function __construct()
    {
        $this->news = new News();
    }

    public function categoryNews($id)
    {
        // TODO: Implement categoryNews() method.

        $news = $this->news::whereHas('category', function ($query) use ($id) {
            $query->where('category_id', $id);
        })->with('category')->get();

        if (count($news) > 0) {
            return view('admin.category.showNewsCateg', compact('news'));
        } else {
            return back()->with('message', 'chka norutyun'); //??
        }
    }

    public function index()
    {
        // TODO: Implement index() method.

        return $this->news::paginate(10);
    }

    public function show($id)
    {
        // TODO: Implement show() method.

        $news = $this->news::find($id);

        if (isset($news)) {
            return view('admin.news.showNews', compact('news'));
        } else {
            return back();
        }

    }

    public function edit($id)
    {
        // TODO: Implement edit() method.

        $news = $this->news::find($id);

        if (isset($news)) {
            return view('admin.news.update', compact('news'));
        } else {
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        // TODO: Implement update() method.

        $news = $this->news::find($id);
        if ($news) {
            $news->update($request->all());
        } else {
            return back();
        }

    }

    public function store(NewsRequest $request)
    {
        // TODO: Implement store() method.
        $request['category_id'] = $request->category;
        $news = News::create($request->all());

         File::create([
            'news_id' => $news->id,
            'name' => Helper::image_upload($request),
        ]);

         NewsUser::create([
            'user_id' => Auth::id(),
            'news_id' => $news->id
        ]);

        return back();
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.

        $news = $this->news::find($id);
        if ($news) {
            $news->delete();
            return redirect('/news');
        } else {
            return back();
        }
    }
}
