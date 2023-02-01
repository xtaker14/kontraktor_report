<?php

namespace App\Models;

use app\Helpers\MyHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CrField extends Model
{
    use HasFactory;

    const TYPE_TEXT = 1;
    const TYPE_DROPDOWN = 2;
    const TYPE_DATEPICKER = 3;

    protected $table = 'cr_field';
    public $fillable = [
        'table_id',
        'name',
        'slug_name',
        'is_mandatory',
        'type',
        'option_id',
        'data_type',
        'disabled',
        'disabled_source',
        'order',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    public static function generateFormInput()
    {
        $form = [
            [
                'field' => 'name',
                'type' => 'text',
                'label' => 'Nama Field'
            ],
            [
                'field' => 'slug_name',
                'type' => 'text',
                'label' => 'Kode Field',
                'info' => 'Gunakan huruf kecil, awali dengan huruf, tanpa spasi, boleh pakai simbol underscore(_)'
            ],
            [
                'field' => 'is_mandatory',
                'type' => 'dropdown',
                'label' => 'Wajib Isi',
                'source' => [
                    0 => 'No',
                    1 => 'Yes'
                ],
            ],
            [
                'field' => 'type',
                'type' => 'dropdown',
                'label' => 'Jenis',
                'source' => [
                    1 => 'Text',
                    2 => 'Dropdown',
                    3 => 'Date'
                ]
            ],
            [
                'field' => 'option_id',
                'type' => 'dropdown',
                'label' => 'Opsi-Opsi',
                'source' => self::getCrOption()
            ],
            [
                'field' => 'data_type',
                'type' => 'dropdown',
                'label' => 'Jenis Data',
                'source' => [
                    0 => 'Huruf & Angka',
                    1 => 'Angka'
                ]
            ],
            [
                'field' => 'disabled',
                'type' => 'dropdown',
                'label' => 'Disabled',
                'source' => [
                    0 => 'No',
                    1 => 'Yes'
                ]
            ],
            [
                'field' => 'order',
                'type' => 'text',
                'label' => 'Urutan'
            ],
            [
                'field' => 'disabled_source',
                'type' => 'textarea',
                'label' => 'Disabled Source'
            ],
        ];

        return $form;
    }

    public static function getCrOption()
    {
        $option = DB::table('cr_option')->get();
        $result = [];

        foreach ($option as $key => $value) {
            $result[$value->id] = $value->name;
        }
        return $result;
    }

    public static function validateField($data, $table_id)
    {
        $get_previews = CrFieldPreview::where('table_id', $table_id)->get();
        $get_fields = CrField::where('table_id', $table_id)->get();
        $fields = $get_fields->toArray();
        
        if (self::isSpecialField($data['slug_name'])) {
            return ['status' => false, 'message' => 'Gagal menambah field. Field sudah ada.'];
        }

        if ($get_fields->count() > 0) {
            foreach ($get_fields as $field) {
                if ($field->slug_name == $data['slug_name']) {
                    return ['status' => false, 'message' => 'Gagal menambah field. Field sudah ada.'];
                }

                if ($field->order == $data['order']) {
                    return ['status' => false, 'message' => 'Gagal menambah field. Urutan tidak boleh duplikat.'];
                }
            }
        }

        if ($get_previews->count() > 0) {
            foreach ($get_previews as $field) {
                if ($field->slug_name == $data['slug_name']) {
                    return ['status' => false, 'message' => 'Gagal menambah field. Field sudah ada.'];
                }

                if ($field->order == $data['order']) {
                    return ['status' => false, 'message' => 'Gagal menambah field. Urutan tidak boleh duplikat.'];
                }
            }
            $fields = array_merge($get_fields->toArray(), $get_previews->toArray());
        }

        return ['status' => true, 'fields' => $fields];
    }

    public static function isSpecialField($field)
    {
        $list = ['id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'assign_date', 'due_date', 'submit_date', 'export_date'];

        if (!in_array($field, $list)) {
            return false;
        }

        return true;
    }

    public static function listType()
    {
        return [
            self::TYPE_TEXT => 'Text',
            self::TYPE_DROPDOWN => 'Dropdown',
            self::TYPE_DATEPICKER => 'Date',
        ];
    }
}
