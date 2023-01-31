<?php

namespace App\Services;

use App\Events\CrTableEvent;
use App\Exceptions\GeneralException;
use App\Models\CrTable;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class CrTableService.
 */
class CrTableService extends BaseService
{
    /**
     * CrTableService constructor.
     *
     * @param  CrTable  $crTable
     */
    public function __construct(CrTable $crTable)
    {
        $this->model = $crTable;
    }

    /**
     * @param  array  $data
     * @return CrTable
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function store(array $data = []): CrTable
    {
        DB::beginTransaction();

        try {
            $data_insert = [
                'slug' => $data['slug'], 
                'name' => $data['name'],
                'form_type' => $data['form_type'],
                'export_type' => $data['export_type'],
                'created_by' => \Auth::user()->id
            ];

            $form = $this->model::create($data_insert);

            if ($form) {
                $filename = $form->id.'.'.$data['excel_file']->extension();
                $data['excel_file']->move(public_path('files'), $filename);
                $this->createTableAfterSave($data['slug']);
            }
        } catch (Exception $e) {
            DB::rollBack(); 

            throw new GeneralException(__('There was a problem creating the form. (' . $e->getMessage() . ')'));
        }

        event(new CrTableEvent($form));

        DB::commit();

        return $form;
    }

    /**
     * @param  Categories  $categories
     * @param  array  $data
     * @return Categories
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function update(Categories $categories, array $data = []): Categories
    {
        DB::beginTransaction();

        $exp_controller_method = [];
        $controller_name = null;
        $method_name = null;
        if($data['is_parent_menu'] == 'no'){
            if($data['is_module'] == 'yes'){
                $exp_controller_method = explode('@', $data['m_controller_name']);
            }else if($data['is_module'] == 'no'){
                $exp_controller_method = explode('@', $data['non_m_controller_name']);
            }

            if(empty($exp_controller_method) || count($exp_controller_method) == 1){
                DB::rollBack(); 
                throw new GeneralException(__('There was a problem creating the categories. (Controller not found)'));
            }
            $controller_name = $exp_controller_method[0];
            $method_name = $exp_controller_method[1];
        }

        try {
            $data_update = [
                'type' => $data['type'], 
                'name' => $data['name'],
                'description' => $data['description'],
                'parent_id' => $data['parent_id'],
                'sort' => $data['sort'],
                'is_active' => $data['is_active'],
                'is_editable' => 'yes',
                'is_menu' => $data['is_menu'],
                'is_module' => $data['is_module'],
                'module_name' => $data['module_name'],
                'controller_name' => $controller_name,
                'method_name' => $method_name,
                'menu_url' => $data['menu_url'],
                'menu_route' => $data['menu_route'],
            ];

            if($data['is_parent_menu'] == 'yes'){
                $data_update['is_module'] = 'no';
                $data_update['module_name'] = null;
            }
            
            $categories->update($data_update);
        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem updating the categories. (' . $e->getMessage() . ')'));
        }

        event(new CrTableUpdated($categories));

        DB::commit();

        return $categories;
    }

    /**
     * @param  Categories  $categories
     * @return bool
     *
     * @throws GeneralException
     */
    public function destroy(Categories $categories): bool
    {
        if ($categories->users()->count()) {
            throw new GeneralException(__('You can not delete a categories with associated users.'));
        }

        // dd($categories->getAttributes());
        // $this->getAllChildren($categories);
        // exit;

        if ($this->deleteById($categories->id)) {
            event(new CrTableDeleted($categories));

            return true;
        }

        throw new GeneralException(__('There was a problem deleting the categories.'));
    }

    public function createTableAfterSave($slug)
    {
        $createTableQuery = "
            CREATE TABLE IF NOT EXISTS crg_".$slug." (
                `id` int(11) NOT NULL,
                `created_at` datetime NULL,
                `created_by` int(11) NULL,
                `updated_at` datetime NULL,
                `updated_by` int(11) NULL,
                `assign_date` date NULL,
                `due_date` date NULL,
                `submit_date` date NULL,
                `export_date` date NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=latin1
        ";
        DB::statement($createTableQuery);

        $alterTableQuery = " ALTER TABLE `crg_".$slug."` ADD PRIMARY KEY (`id`)";
        DB::statement($alterTableQuery);

        $modifyTableQuery = "ALTER TABLE `crg_".$slug."` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT";
        DB::statement($modifyTableQuery);

        return true;
    }
}
