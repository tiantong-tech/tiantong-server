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

class DesignSchemaController extends _Controller
{
  use Traits\ProjectTrait;

  public function search()
  {
    return DesignSchema::all();
  }

  public function create()
  {
    $type = $this->get('type', Rule::in(Enums::designSchemaTypes), 'hoister');
    $isCompleted = $this->get('is_completed', 'boolean', false);
    $input = $this->getInputData('');
    $data = request('data');
    $project = $this->getProject();
    $schema = new DesignSchema;
    $schema->project_id = $project->id;
    $schema->is_completed = $isCompleted;
    $schema->data = $data;
    $schema->fill($input);
    $schema->type = $type;

    Transaction::begin();
    $schema->save();
    Project::where('id', $project->id)->update([
      'design_schema_ids' => DB::raw("design_schema_ids || $schema->id::bigInt")
    ]);
    Transaction::commit();

    return $this->success([
      'msg' => 'success to add design schema',
      'schema_id' => $schema->id
    ]);
  }

  public function update()
  {
    $schema = $this->getDesignSchema();

    $data = $this->via([
      'data' => 'json',
      'name' => 'string',
      'notes' => 'string',
      'company' => 'string',
      'is_completed' => 'boolean',
      'customer_information' => 'string',
    ]);

    $schema->fill($data);
    $schema->save();

    return $this->success('success to update project information');
  }

  public function delete()
  {
    $schema = $this->getDesignSchema();

    Transaction::begin();
    $schema->delete();
    Quotation::where('design_schema_id', $schema->id)->delete();
    CadDrawing::where('design_schema_id', $schema->id)->delete();
    Project::where('id', $schema->project_id)->update([
      'design_schema_ids' => DB::raw("array_remove(design_schema_ids, $schema->id::bigInt)")
    ]);
    Transaction::commit();

    return $this->success('success to delete project');
  }

  private function getInputData($default = false)
  {
    return $this->via([
      'quantity' => 'string',
      'name' => 'string',
      'notes' => 'string'
    ], $default);
  }
}
