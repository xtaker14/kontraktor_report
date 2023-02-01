<?php

namespace App\Http\Controllers;

use App\Http\Requests\CrField\CreateTableFieldsRequest;
use App\Http\Requests\CrField\StoreCrFieldsRequest;
use App\Services\CrFieldService;
use App\Models\CrField;
use App\Models\CrFieldPreview;
use App\Models\CrTable;
use Illuminate\Http\Request;

/**
 * Class CrFieldController.
 */
class CrFieldController
{ 
    public $form;
    /**
     * @var CrFieldService
     */
    protected $crFieldService;
    
    /**
     * CrFieldController constructor.
     *
     * @param  CrFieldService  $crFieldService
     */
    public function __construct(CrFieldService $crFieldService)
    {
        $this->crFieldService = $crFieldService;
        $this->form = CrField::generateFormInput();
    }

    public function list($table_id)
    {
        $table = CrTable::findOrFail($table_id);
        return view('cr-field.index', [
            'table' => $table
        ]);
    }

    public function create_field_table($table_id)
    {
        $detail_form = crTable::findOrFail($table_id);
        
        return view('cr-field.create', [
            'form' => $this->form,
            'detail_form' => $detail_form
        ]);
    }

    public function preview_field(CreateTableFieldsRequest $request, $table_id)
    {
        $get_table = CrTable::find($table_id);
        $data = $request->all();

        if ($get_table == null) {
            return response()->json([], 404);
        } else {
            $fields = CrField::validateField($data, $get_table->id);
            
            if ($fields['status']) {
                $listType = CrField::listType();
                $result = '';
                $i = 1;
                foreach ($fields['fields'] as $field) {

                    $result .= '<tr>';
                    $result .= '<td>' . $i . '</td>';
                    $result .= '<td>' . $field['name'] . '</td>';
                    $result .= '<td>' . $listType[$field['type']] . '</td>';
                    $result .= '</tr>';

                    $i++;
                }

                $result .= '<tr class="alert alert-success">';
                $result .= '<td>' . $i . '</td>';
                $result .= '<td>' . $data['name'] . ' <span class="label label-default"><strong>New</strong></span></td>';
                $result .= '<td>' . $listType[$data['type']] . ' <span class="label label-default"><strong>New</strong></span></td>';
                $result .= '</tr>';

                return response()->json(['fields' => $result]);
            } else {
                return response()->json(['message' => $fields['message']], 422);
            }
            
        }
        
    }

    public function save_preview_field(CreateTableFieldsRequest $request){
        $request->merge([
            'created_at' => now('UTC'),
            'created_by' => \Auth::user()->id
        ]);

        $insert = CrFieldPreview::insert($request->all());

        if ($insert) {
            return response()->json(['message' => 'success']);
        } else {
            return response()->json(['message' => 'fail'], 500);
        }
        
    }

    public function final_preview($table_id)
    {
        $get_previews = CrFieldPreview::where('table_id', $table_id)->get();
        $get_fields = CrField::where('table_id', $table_id)->get()->toArray();

        if ($get_previews->count() > 0) {
            $get_fields = array_merge($get_fields, $get_previews->toArray());
        }

        $listType = CrField::listType();
        $result = '';
        foreach ($get_fields as $key => $field) {
            $result .= '<tr>';
            $result .= '<td>' . ($key + 1) . '</td>';
            $result .= '<td>' . $field['name'] . '</td>';
            $result .= '<td>' . $listType[$field['type']] . '</td>';
            $result .= '</tr>';
        }

        return response()->json(['fields' => $result]);
    }

    public function save_final_preview(Request $request)
    {
        $table_id = $request->table_id;
        $get_previews = CrFieldPreview::where('table_id', $table_id)->get();
        if ($get_previews->count() > 0) {
            foreach ($get_previews as $key => $value) {
                CrField::insert([
                    'table_id' => $value->table_id,
                    'name' => $value->name,
                    'slug_name' => $value->slug_name,
                    'is_mandatory' => $value->is_mandatory,
                    'type' => $value->type,
                    'option_id' => $value->option_id,
                    'data_type' => $value->data_type,
                    'disabled' => $value->disabled,
                    'disabled_source' => $value->disabled_source,
                    'order' => $value->order,
                    'created_at' => now('UTC'),
                    'created_by' => \Auth::user()->id
                ]);
            }

            CrFieldPreview::where('table_id', $table_id)->delete();
        }
        return response()->json(['status' => 'success', 'message' => 'Berhasil menyimpan data!']);
    }

    public function store(StoreCrFieldsRequest $request)
    {
        $this->crFieldService->store($request->validated());

        // return redirect()->route('menu.cr-field.index')->withFlashSuccess(__('The field was successfully created.'));
    }
}