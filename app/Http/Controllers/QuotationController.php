<?php

namespace App\Http\Controllers;

use DB;
use Transaction;
use App\Services\Enums;
use App\Models\Project;
use App\Models\Quotation;
use App\Models\DesignSchema;
use Illuminate\Validation\Rule;

class QuotationController extends Controller
{
  use Traits\ProjectTrait;

  public function search()
  {
    return Quotation::all();
  }

  public function create()
  {
    // $type = $this->get('type', Rule::in(Enums::quotationTypes), 'mechanics');
    $name = $this->get('name', 'string', '');
    $content = $this->get('content', 'string', '');
    $deadline = $this->get('deadline', 'nullable');
    $schema = $this->getDesignSchema();

    $quotations = [];
    $types = ['mechanics', 'electronic_control', 'engineering'];
    foreach ($types as $type) {
      $quotation = new Quotation;
      $quotation->type = $type;
      $quotation->name = $name;
      $quotation->content = $content;
      $quotation->deadline = $deadline;
      $quotation->design_schema_id = $schema->id;
      $quotation->project_id = $schema->project_id;
      $quotations[] = $quotation;
    }
    Transaction::begin();
    $data = [];
    $ids = [];
    foreach ($quotations as $quotation) {
      $quotation->save();
      $ids[] = $quotation->id;
      $data[] = "$quotation->id::bigint";
    }
    $data = "array[" . implode(", ", $data) . "]";
    DesignSchema::where('id', $schema->id)->update([
      'quotation_ids' => DB::raw("quotation_ids || $data")
    ]);
    Transaction::commit();

    return $this->success([
      'message' => 'success to add quotation',
      'quotation_ids' => $ids
    ]);
  }

  public function update()
  {
    $quotation = $this->getQuotation();
    $data = $this->via([
      'name' => 'string',
      'content' => 'string',
      'deadline' => 'string',
      'offered_at' => 'nullable'
    ]);
    $quotation->fill($data);
    $quotation->save();

    return $this->success('success to update quotation information');
  }

  public function delete()
  {
    $quotation = $this->getQuotation();

    Transaction::begin();
    $quotation->delete();
    DesignSchema::where('id', $quotation->design_schema_id)->update([
      'quotation_ids' => DB::raw("array_remove(quotation_ids, $quotation->id::bigint)")
    ]);
    Transaction::commit();

    return $this->success('success to delete quotation');
  }
}
