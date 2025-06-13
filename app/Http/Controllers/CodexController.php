<?php

namespace App\Http\Controllers;

use App\Models\Codex;
use Illuminate\Http\Request;

class CodexController extends Controller
{
  /**
   * Display a listing of codex entries, grouped by type.
   */
  public function index(Request $request)
  {
    $isHtmx = $request->hasHeader('HX-Request');

    $codexEntries = Codex::orderBy('type')->orderBy('name')->get()->groupBy('type');

    return view('outline.codex.index', compact('codexEntries', 'isHtmx'))
      ->fragmentIf($isHtmx, 'codex-entry-list');
  }

  /**
   * Show the form for creating a new codex entry.
   */
  public function create(Request $request)
  {
    $isHtmx = $request->hasHeader('HX-Request');

    return view('outline.codex.create', compact('isHtmx'))
      ->fragmentIf($isHtmx, 'create-codex-form');
  }

  /**
   * Store a newly created codex entry in storage.
   */
  public function store(Request $request)
  {
    $data = $request->validate([
      'name' => 'required|string|max:255',
      'type' => 'required|in:character,item,location',
      'description' => 'required|string',
    ]);

    $codex = Codex::create($data);

    $isHtmx = $request->hasHeader('HX-Request');

    if ($isHtmx) {
        $codexEntries = Codex::orderBy('type')->orderBy('name')->get()->groupBy('type');

        return view('outline.codex.index', compact('codexEntries', 'isHtmx'))
          ->fragments(['codex-entry-list', 'modal']);
    }

    return redirect()->route('outline.codex.show', $codex)
      ->with('success', 'Codex entry created successfully.');
  }

  /**
   * Display the specified codex entry.
   */
  public function show(Request $request, Codex $codex)
  {
    $isHtmx = $request->hasHeader('HX-Request');

    return view('outline.codex.show', compact('codex', 'isHtmx'))
      ->fragmentIf($isHtmx, 'codex-details');
  }

  /**
   * Show the form for editing the specified codex entry.
   */
  public function edit(Request $request, Codex $codex)
  {
    $isHtmx = $request->hasHeader('HX-Request');

    return view('outline.codex.edit', compact('codex', 'isHtmx'))
      ->fragmentIf($isHtmx, 'edit-codex-form');
  }

  /**
   * Update the specified codex entry in storage.
   */
  public function update(Request $request, Codex $codex)
  {
    $data = $request->validate([
      'name' => 'required|string|max:255',
      'type' => 'required|in:character,item,location',
      'description' => 'required|string',
    ]);

    $codex->update($data);

    $isHtmx = $request->hasHeader('HX-Request');

    if ($isHtmx) {
        $codexEntries = Codex::orderBy('type')->orderBy('name')->get()->groupBy('type');

        return view('outline.codex.index', compact('codexEntries', 'isHtmx'))
          ->fragments(['codex-entry-list', 'modal']);
    }

    return redirect()->route('outline.codex.show', $codex)
      ->with('success', 'Codex entry updated successfully.');
  }

  /**
   * Remove the specified codex entry from storage.
   */
  public function destroy(Request $request, Codex $codex)
  {
    $codex->delete();

    $isHtmx = $request->hasHeader('HX-Request');

    if ($isHtmx) {
        $codexEntries = Codex::orderBy('type')->orderBy('name')->get()->groupBy('type');

        return view('outline.codex.index', compact('codexEntries', 'isHtmx'))
          ->fragments(['codex-entry-list', 'modal']);
    }

    return redirect()->route('outline.codex.index')
      ->with('success', 'Codex entry deleted successfully.');
  }
}
