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

    $drawing->save();

    return $this->success([
      'message' => 'success to create cad drawing',
      'cad_drawing_id' => $drawing->id
    ]);
  }

  public function update()
  {
    $drawing = $this->getCadDrawing();
    $deadline = $this->get('deadline', 'nullable');
    $offeredAt = $this->get('offered_at', 'nullable');
    $data = $this->via([
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

    $drawing->delete();

    return $this->success('success to delete project');
  }
}
