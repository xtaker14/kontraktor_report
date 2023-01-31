<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrTable extends Model
{
    use HasFactory;

    protected $table = 'cr_table';
    public $fillable = [
        'slug',
        'name',
        'form_type',
        'export_type',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    public static function generateFormInput()
    {
        $form = [
            [
                'field' => 'slug',
                'type' => 'text',
                'label' => 'Kode Form',
                'info' => '*pakai huruf, tanpa spasi dan simbol, boleh garisbawah, contoh: contractor_report'
            ],
            [
                'field' => 'name',
                'type' => 'text',
                'label' => 'Nama Form'
            ],
            [
                'field' => 'form_type',
                'type' => 'dropdown',
                'label' => 'Jenis Form',
                'source' => [
                    0 => 'Single Insert',
                    5 => 'Multiple Insert'
                ],
                'info' => 'Pilih "Multi Insert" untuk isian yang bersifat berulang-ulang.'
            ],
            [
                'field' => 'export_type',
                'type' => 'dropdown',
                'label' => 'Jenis Export Excel',
                'source' => [
                    0 => 'Single Export',
                    5 => 'Multiple Export'
                ],
                'info' => 'Pilih "Multi Export" jika dalam satu sheet Excel ada banyak perusahaan.'
            ],
            [
                'type' => 'alert',
                'class' => 'info',
                'header' => 'Info!',
                'fontawesome_header_class' => 'fa fa-info-circle',
                'description' => 'Guna menampilkan nama perusahaan di hasil export, gunakan kata "created_by" di dalam template. Untuk contohnya bisa lihat di <a href="'.url('template/example.xlsx').'">sini</a>'
            ],
            [
                'field' => 'excel_file',
                'type' => 'file',
                'label' => 'Template',
                'info' => '(*file berakhiran .xlsx)'
            ],
        ];

        return $form;
    }

    public static function viewData($table_id)
    {
        $table = self::find($table_id);
        $table_field = CrField::where('table_id', $table_id)->get()->toArray();
        $list_type = CrField::listType();

        foreach ($table_field as $key => $value) {
            $table_field[$key]['type'] = $list_type[$value['type']];
        }
        
        return ['table' => $table, 'field' => $table_field];
    }
}
