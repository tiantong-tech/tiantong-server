<?php

namespace App\Providers;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	public function register()
	{
			//
	}

	public function boot()
	{
		Builder::macro('page', function ($pageSize = null, $columns = ['*'], $pageName = 'page', $page = null) {
			!$page && ($page = request('page'));
			!$pageSize && ($pageSize = request('page_size'));
			!$page && ($page = 1);
			!$pageSize && ($pageSize = 15);
	
			$total = $this->getCountForPagination();
			$data = $total ? $this->forPage($page, $pageSize)->get() : collect();

			return [
				'page' => $page,
				'page_size' => $pageSize,
				'total' => $total,
				'data' => $data
			];
		});
	}
}
