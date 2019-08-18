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
      throw new HttpException('fail to find project by id', 404);
    }

    return $project;
  }

  protected function getDesignSchema()
  {
    $id = $this->get('design_schema_id');
    $schema = DesignSchema::find($id);
    if (!$schema) {
      throw new HttpException('fail to find design schema by id', 404);
    }

    return $schema;
  }

  protected function getCadDrawing()
  {
    $id = $this->get('cad_drawing_id');
    $drawing = CadDrawing::find($id);
    if (!$drawing) {
      throw new HttpException('fail to find cad drawing by id', 404);
    }

    return $drawing;
  }

  protected function getQuotation()
  {
    $id = $this->get('quotation_id');
    $quotation = Quotation::find($id);
    if (!$quotation) {
      throw new HttpException('fail to find quotation by id', 404);
    }

    return $quotation;
  }
}
