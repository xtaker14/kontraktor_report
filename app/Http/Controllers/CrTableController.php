<?php

namespace App\Http\Controllers;

use App\Http\Requests\CrTable\StoreCrTablesRequest;
use App\Services\CrTableService;
use App\Models\CrTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

/**
 * Class CrTableController.
 */
class CrTableController
{ 
    public $form;
    /**
     * @var CrTableService
     */
    protected $crTableService;
    
    /**
     * CrTableController constructor.
     *
     * @param  CrTableService  $crTableService
     */
    public function __construct(CrTableService $crTableService)
    {
        $this->crTableService = $crTableService;
        $this->form = CrTable::generateFormInput();
    }

    public function index()
    {
        return view('cr-table.index');
    }

    public function create()
    {
        return view('cr-table.create', [
            'action' => 'insert',
            'form' => $this->form
        ]);
    }

    public function store(StoreCrTablesRequest $request)
    {
        $this->crTableService->store($request->validated());

        return redirect()->route('menu.cr-table.index')->withFlashSuccess(__('The form was successfully created.'));
    }

    public function view_data(Request $request)
    {
        $view = CrTable::viewData($request->id);
        
        return response()->json(['table' => $view['table'], 'field' => $view['field']]);
    }

    public function edit($table_id)
    {
        $table = CrTable::findOrFail($table_id)->toArray();
        foreach ($this->form as $key => $value) {
            if (in_array($key, [0, 1])) {
                $this->form[$key]['readonly'] = true;
            }
            
            if (!isset($value['field']) || !isset($table[$value['field']])) {
                $this->form[$key]['value'] = "";
            } else {
                $this->form[$key]['value'] = $table[$value['field']];
            }
        }
        // dd($this->form);
        return view('cr-table.edit', [
            'action' => 'edit',
            'form' => $this->form,
            'table' => $table
        ]);
    }

    public function update(Request $request, $table_id)
    {
        $table_detail = CrTable::findOrFail($table_id);
        
        if (!empty($request->file())) {
            $file_name = $table_detail->id.".".$request->excel_file->extension();
            if (File::exists(public_path('files/'.$file_name))) {
                unlink(public_path('files/'.$file_name));
            }
            $request->file('excel_file')->move(public_path('files'), $file_name);
        }

        $update_data = $request->except(['_token', '_method', 'slug', 'name', 'excel_file']);

        $update = CrTable::where('id', $table_detail->id)->update($update_data);

        if ($update) {
            return redirect()->route('menu.cr-table.index')->withFlashSuccess(__('The form was successfully updated.'));
        } else {
            return redirect()->route('menu.cr-table.edit', ['table_id' => $table_detail->id])->withFlashDanger(__('Something wrong when update data.'));
        }
        
    }
}