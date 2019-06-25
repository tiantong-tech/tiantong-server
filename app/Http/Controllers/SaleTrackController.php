<?php

namespace App\Http\Controllers;

use App\Services\Enums;
use App\Models\SaleTrack; 
use Illuminate\Validation\Rule;

class SaleTrackController extends Controller
{
  public function create()
  {
    $data = $this->get('data', 'nullable|array');

    $filling = $this->via([
      'name' => 'nullable|string',
      'email' => 'nullable|email',
      'message' => 'nullable|string',
      'company' => 'nullable|string',
      'phone_number' => 'nullable|string',
      'project_name' => 'nullable|string',
      'type' => ['required', Rule::in(Enums::saleTrackTypes)],
    ]);

    $saleTrack = new SaleTrack;
    $saleTrack->data = $data;
    $saleTrack->fill($filling);
    $saleTrack->save();

    return $this->success([
      'message' => 'success_to_create_sale_track',
      'id' => $saleTrack->id
    ]);
  }

  public function delete()
  {
    $id = $this->get('id', 'required|exists:sale_tracks,id');

    SaleTrack::where('id', $id)->delete($id);

    return $this->success('success_to_delete_sale_track');
  }

  public function update()
  {
    $id = $this->get('id', 'required|exists:sale_tracks,id');
    $data = $this->via([
      'name' => 'nullable|string',
      'email' => 'nullable|email',
      'message' => 'nullable|string',
      'company' => 'nullable|string',
      'phone_number' => 'nullable|string',
      'project_name' => 'nullable|string',
      'type' => ['nullable', Rule::in(Enums::saleTrackTypes)],
      'status' => ['nullable', Rule::in(Enums::saleTrackStatuses)]
    ]);

    $saleTrack = SaleTrack::find($id);
    $saleTrack->fill($data);
    $saleTrack->save();

    return $this->success('success_to_update_sale_track');
  }

  public function search()
  {
    $search = $this->get('search', 'nullable|string');
    $query = SaleTrack::orderBy('id', 'desc');
    if ($search) {
      $query->withSearch($search);
    }
    return $query->paginate();
  }
}
