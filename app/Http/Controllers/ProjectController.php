<?php

namespace App\Http\Controllers;

use DB;
use Transaction;
use App\Services\Enums;
use App\Models\Project;
use App\Models\Quotation;
use App\Models\CadDrawing;
use App\Models\DesignSchema;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
  use Traits\ProjectTrait;

  public function search()
  {
    return Project::from('projects as p')
      ->select('p.*')
      ->addSelect(DB::raw('jsonb_agg(users.name) as members'))
      ->leftJoin('users', 'p.member_ids', '@>', DB::raw('ARRAY[users.id]'))
      ->orderBy('p.created_at', 'desc')
      ->groupBy('p.id')
      ->paginate();
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
      'progress' => 'string'
    ]);
  }
}
