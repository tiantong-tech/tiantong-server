<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Qiniu;
use Transaction;
use Carbon\Carbon;
use App\Services\Enums;
use App\Models\File;
use App\Models\Project;
use App\Models\Quotation;
use App\Models\CadDrawing;
use App\Models\DesignSchema;
use Illuminate\Validation\Rule;

class ProjectController extends _Controller
{
  use Traits\FileTrait;
  use Traits\ProjectTrait;

  public function search()
  {
    $search = $this->get('search', 'nullable|string');
    $query = Project::from('projects as p')
      ->select('p.*')
      ->addSelect(DB::raw('jsonb_agg(u.name) as members'))
      ->leftJoin('users as u', 'p.member_ids', '@>', DB::raw('ARRAY[u.id]'))
      ->orderBy('p.id', 'asc')
      ->groupBy('p.id');

    if ($search) {
      $columns = ['p.name', 'p.company', 'u.name', 'u.email', 'p.id'];

      foreach ($columns as $column) {
        $query->orWhere($column, 'like', "%$search%");
      }
    }

    return $query->paginate();
  }

  public function create()
  {
    $data = $this->getData('');
    $attrs = $this->getAttrs();
    $type = $this->get('type', Rule::in(Enums::projectTypes), 'sale');
    $saleType = $this->get('sale_type', RUle::in(Enums::projectSaleTypes));

    $project = new Project;
    $project->fill($data);
    $project->fill($attrs);
    $project->type = $type;
    $project->sale_type = $saleType;
    $project->save();

    return $this->success([
      'project_id' => $project->id,
      'message' => 'success to create project',
    ]);
  }

  public function update()
  {
    $data = $this->getData();
    $attrs = $this->getAttrs();
    $project = $this->getProject();
    $project->fill($data);
    $project->fill($attrs);
    $project->save();

    return $this->success('success to update project information');
  }

  public function delete()
  {
    $project = $this->getProject();

    Transaction::begin();
    $project->delete();
    Quotation::where('project_id', $project->id)->delete();
    CadDrawing::where('project_id', $project->id)->delete();
    DesignSchema::where('project_id', $project->id)->delete();
    Transaction::commit();

    return $this->success('success to delete project');
  }

  public function detail()
  {
    $id = $this->get('project_id', 'required');

    $with = [
      'design_schemas:*',
      'design_schemas.quotations',
      'design_schemas.cad_drawing',
    ];
    $project = Project::with($with)
      ->select('projects.*')
      ->addSelect(DB::raw('jsonb_agg(users.name) as members'))
      ->leftJoin('users', 'projects.member_ids', '@>', DB::raw('ARRAY[users.id]'))
      ->groupBy('projects.id')
      ->find($id);

    if (!$project) {
      return $this->failure('fail to find project by id', 404);
    }

    return $project;
  }

  /**
   * 1. 获取七牛上传 token
   * 2. 创建 file 数据
   * 3. 返回 token
   * 4. 前端上传文件
   * 5. 前端确认上传
   */
  public function fileUpload()
  {
    $userId = Auth::user()->id;
    $data = $this->via([
      'size' => 'integer',
      'name' => 'required',
      'comment' => 'string',
    ], '');

    $file = new File;
    $file->fill($data);
    $file->user_id = $userId;
    $file->namespace = 'sale_projects';
    $file->save();

    $token = Qiniu::getUploadToken('tiantong');

    return $this->success([
      'token' => $token,
      'file_id' => $file->id
    ]);
  }

  public function fileUploadConfirm()
  {
    $file = $this->getFile();
    $project = $this->getProject();

    $file->is_uploaded = true;
    $file->link = $this->get('link', 'required');

    Transaction::begin();
    Project::where('id', $project->id)->update([
      'file_ids' => DB::raw("file_ids || $file->id")
    ]);
    $file->save();
    Transaction::commit();

    return $this->success('success to confirm file upload');
  }

  public function fileDelete()
  {
    $project = $this->getProject();
    $fileIds = $this->get('file_ids', 'array');
    $project->file_ids = array_diff($project->file_ids, $fileIds);

    Transaction::begin();
    $project->save();
    File::whereIn('id', $fileIds)->update([
      'deleted_at' => Carbon::now()
    ]);
    Transaction::commit();

    return $this->success('success to delete files');
  }

  public function fileSearch()
  {
    $project = $this->getProject();
    $files = File::with('user:id,name')
      ->whereIn('id', $project->file_ids)
      ->orderBy('created_at', 'desc')
      ->get();

    return $files;
  }

  protected function getData($default = false)
  {
    return $this->via([
      'name' => 'string',
      'notes' => 'string',
      'company' => 'string',
      'customer_information' => 'string'
    ], $default);
  }

  protected function getAttrs()
  {
    return $this->via([
      'status' => 'array',
      'member_ids' => 'array',
      'progress' => 'string',
      'delivery_date' => 'date',
      'signature_date' => 'date',
    ]);
  }
}
