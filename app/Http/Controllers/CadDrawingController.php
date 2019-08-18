<?php

namespace App\Http\Controllers;

use DB;
use Transaction;
use App\Services\Enums;
use App\Models\Project;
use App\Models\CadDrawing;
use App\Models\DesignSchema;
use Illuminate\Validation\Rule;

class CadDrawingController extends Controller
{
  use Traits\ProjectTrait;

  public function search()
  {
    return CadDrawing::all();
  }

  public function create()
  {
    $deadline = $this->get('deadline', 'nullable');
    $schema = $this->getDesignSchema();
    $drawing = new CadDrawing;
    $drawing->design_schema_id = $schema->id;
    $drawing->project_id = $schema->project_id;
    $drawing->deadline = $deadline;

    Transaction::begin();
    $drawing->save();
    DesignSchema::where('id', $schema->id)->update([
      'cad_drawing_ids' => DB::raw("cad_drawing_ids || $drawing->id")
    ]);
    Transaction::commit();

    return $this->success('success to add design schema');
  }

  public function update()
  {
    $drawing = $this->getCadDrawing();
    $deadline = $this->get('deadline', 'nullable');
    $offeredAt = $this->get('offered_at', 'nullable');
    $data = $this->get([
      'deadline' => 'nullable',
      'offered_at' => 'nullable'
    ]);

    $drawing->fill($data);
    $drawing->save();

    return $this->success('success to update project information');
  }

  public function delete()
  {
    $drawing = $this->getCadDrawing();

    Transaction::begin();
    $drawing->delete();
    DesignSchema::where('id', $drawing->design_schema_id)->update([
      'cad_drawing_ids' => DB::raw("array_remove(cad_drawing_ids, $drawing->id)")
    ]);
    Transaction::commit();

    return $this->success('success to delete project');
  }
}
