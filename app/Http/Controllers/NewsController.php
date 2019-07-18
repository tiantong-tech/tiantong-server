<?php

namespace App\Http\Controllers;

use Auth;
use Qiniu;
use App\Models\News;

class NewsController extends Controller
{
	public function create()
	{
		$news = new News;
		$news->anthor_id = Auth::user()->id;
		$news->title = $this->get('title', 'string');
		$news->article = $this->get('article', 'string');
		$news->save();

		return $this->success('success_to_create_news');
	}

	public function delete()
	{
    $id = $this->get('id', 'integer');
		$ids = $this->get('ids', 'array|nullable');

    if ($id) {
      News::where('id', $id)->delete();
    } else if ($ids) {
      News::whereIn('id', $ids)->delete();
    }

		return $this->success('success_to_delete_news');
	}

	public function update()
	{
		$id = $this->get('id', 'integer');
		$data = $this->get('data', 'array');

		$news = News::find($id);
		$news->fill($data);
		$news->save();

		return $this->success('success_to_update_news');
	}

	public function search()
	{
    $search = $this->get('search', 'nullable|string');
    $query = News::orderBy('created_at', 'desc');
    if ($search) {
      $query->withSearch($search);
    }

    return $query->paginate();
	}

	public function exhibit()
	{
		$search = $this->get('search', 'nullable|string');
		$query = News::onlyVisible()
			->orderBy('created_at', 'desc');

		if ($search) {
			$query->withSearch($search);
		}

		return $query->paginate();
	}

	public function find()
	{
		$id = $this->get('id', 'required');

		return News::findOrFail($id);
	}

	public function getUploadToken()
	{
		return Qiniu::getUploadToken();
	}
}
