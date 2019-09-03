<?php

namespace App\Http\Controllers\Traits;

use App\Models\Project;
use App\Models\Quotation;
use App\Models\CadDrawing;
use App\Models\DesignSchema;
use App\Exceptions\HttpException;

trait ProjectTrait
{
  protected function getProject()
  {
    $id = $this->get('project_id');
    $project = Project::find($id);
    if (!$project) {
      $this->failure('fail to find file by id', 404);
    }

    return $project;
  }

  protected function getDesignSchema()
  {
    $id = $this->get('design_schema_id');
    $schema = DesignSchema::find($id);
    if (!$schema) {
      $this->failure('fail to find file by id', 404);
    }

    return $schema;
  }

  protected function getCadDrawing()
  {
    $id = $this->get('cad_drawing_id');
    $drawing = CadDrawing::find($id);
    if (!$drawing) {
      $this->failure('fail to find file by id', 404);
    }

    return $drawing;
  }

  protected function getQuotation()
  {
    $id = $this->get('quotation_id');
    $quotation = Quotation::find($id);
    if (!$quotation) {
      $this->failure('fail to find file by id', 404);
    }

    return $quotation;
  }
}
