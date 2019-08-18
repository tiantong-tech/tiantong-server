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
    $type = $this->get('type', Rule::in(Enums::quotationTypes), 'mechanics');
    $name = $this->get('name', 'string', '');
    $content = $this->get('content', 'string', '');
    $deadline = $this->get('deadline', 'nullable');
    $schema = $this->getDesignSchema();
    $quotation = new Quotation;
    $quotation->type = $type;
    $quotation->name = $name;
    $quotation->content = $content;
    $quotation->deadline = $deadline;
    $quotation->project_id = $schema->project_id;
    $quotation->design_schema_id = $schema->id;

    Transaction::begin();
    $quotation->save();
    DesignSchema::where('id', $schema->id)->update([
      'quotation_ids' => DB::raw("quotation_ids || $quotation->id")
    ]);
    Transaction::commit();

    return $this->success('success to add quotation');
  }

  public function update()
  {
    $quotation = $this->getQuotation();
    $name = $this->get('name', 'string', '');
    $content = $this->get('content', 'string', '');
    $deadline = $this->get('deadline', 'nullable');

    $quotation->name = $name;
    $quotation->content = $content;
    $quotation->deadline = $deadline;
    $quotation->save();

    return $this->success('success to update quotation information');
  }

  public function delete()
  {
    $quotation = $this->getQuotation();

    Transaction::begin();
    $quotation->delete();
    DesignSchema::where('id', $quotation->design_schema_id)->update([
      'quotation_ids' => DB::raw("array_remove(quotation_ids, $quotation->id)")
    ]);
    Transaction::commit();

    return $this->success('success to delete quotation');
  }
}
