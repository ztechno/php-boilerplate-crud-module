<?php

namespace Modules\Crud\Libraries\Repositories;

use Core\Database;
use Core\Utility;

class CrudRepository
{
    private $table;
    private $module;
    private $db;
    function __construct($table)
    {
        $this->table = $table;
        $this->db = new Database;
    }

    function setModule($module)
    {
        $this->module = $module;
    }

    function additionalButtonBeforeCreate()
    {
        $file = Utility::parentPath() . "modules/$this->module/hooks/additional-button-before-create-$this->table.php";
        if(file_exists($file))
        {
            return require $file;
        }
        return ;
    }
    
    function additionalButtonAfterCreate()
    {
        $file = Utility::parentPath() . "modules/$this->module/hooks/additional-button-after-create-$this->table.php";
        if(file_exists($file))
        {
            return require $file;
        }
        return ;
    }

    function beforeCreate(&$data)
    {
        $file = Utility::parentPath() . "modules/$this->module/hooks/before-create-$this->table.php";
        if(file_exists($file))
        {
            require $file;
        }
    }
    
    function afterCreate(&$data)
    {
        $file = Utility::parentPath() . "modules/$this->module/hooks/after-create-$this->table.php";
        if(file_exists($file))
        {
            require $file;
        }
    }
    
    function beforeUpdate(&$data)
    {
        $file = Utility::parentPath() . "modules/$this->module/hooks/before-update-$this->table.php";
        if(file_exists($file))
        {
            require $file;
        }
    }
    
    function afterUpdate(&$data)
    {
        $file = Utility::parentPath() . "modules/$this->module/hooks/after-update-$this->table.php";
        if(file_exists($file))
        {
            require $file;
        }
    }
    
    function beforeDelete(&$data)
    {
        $file = Utility::parentPath() . "modules/$this->module/hooks/before-delete-$this->table.php";
        if(file_exists($file))
        {
            require $file;
        }
    }
    
    function afterDelete(&$data)
    {
        $file = Utility::parentPath() . "modules/$this->module/hooks/after-delete-$this->table.php";
        if(file_exists($file))
        {
            require $file;
        }
    }

    function actionButton($data)
    {
        $file = Utility::parentPath() . "modules/$this->module/hooks/action-button-$this->table.php";
        if(file_exists($file))
        {
            return require $file;
        }
        return '';
    }

    function get($clause = [], $order = [], $limit = 0)
    {
        return $this->db->all($this->table, $clause, $order, $limit);
    }

    function find($clause)
    {
        return $this->db->single($this->table, $clause);
    }

    function create($data)
    {
        $this->beforeCreate($data);
        $data = $this->db->insert($this->table, $data);
        $this->afterCreate($data);

        return $data;
    }

    function update($data, $clause)
    {
        $this->beforeUpdate($data);
        $data = $this->db->update($this->table, $data, $clause);
        $this->afterUpdate($data);

        return $data;
    }

    function delete($clause)
    {
        $this->beforeDelete($clause);
        $this->db->delete($this->table, $clause);
        $this->afterDelete($clause);

        return true;
    }

    function dataTable($fields, $customAction = false)
    {
        $draw    = $_GET['draw'];
        $start   = $_GET['start'];
        $length  = $_GET['length'];
        $search  = $_GET['search']['value'];
        $order   = $_GET['order'];
        $filter  = isset($_GET['filter']) ? $_GET['filter'] : [];
        
        $columns = [];
        $search_columns = [];
        foreach($fields as $key => $field)
        {
            $columns[] = is_array($field) ? $key : $field;
            if(is_array($field) && isset($field['search']) && !$field['search']) continue;
            $search_columns[] = is_array($field) ? $key : $field;
        }

        $where = "";

        if(!empty($search))
        {
            $_where = [];
            foreach($search_columns as $col)
            {
                $_where[] = "$col LIKE '%$search%'";
            }

            $where = "WHERE (".implode(' OR ',$_where).")";
        }

        

        $col_order = $order[0]['column']-1;
        $col_order = $col_order < 0 ? 'id' : $columns[$col_order];

        $hookFile = Utility::parentPath() . "modules/$this->module/hooks/index-$this->table.php";
        if(file_exists($hookFile))
        {
            $db = $this->db;
            $table = $this->table;
            $override = require $hookFile;
            extract($override);
        }
        else
        {
            if($filter)
            {
                $filter_query = [];
                foreach($filter as $f_key => $f_value)
                {
                    $filter_query[] = "$f_key = '$f_value'";
                }

                $filter_query = implode(' AND ', $filter_query);

                $where = (empty($where) ? 'WHERE ' : ' AND ') . $filter_query;
            }

            $this->db->query = "SELECT * FROM $this->table $where ORDER BY ".$col_order." ".$order[0]['dir']." LIMIT $start,$length";
            $data  = $this->db->exec('all');
    
            $total = $this->db->exists($this->table,$where,[
                $col_order => $order[0]['dir']
            ]);
        }


        $results = [];
        
        foreach($data as $key => $d)
        {
            $results[$key][] = $key+1;
            foreach($columns as $col)
            {
                $field = '';
                if(isset($fields[$col]))
                {
                    $field = $fields[$col];
                }
                else
                {
                    $field = $col;
                }
                $data_value = "";
                if(is_array($field))
                {
                    $data_value = \Core\Form::getData($field['type'],$d->{$col},true);
                    if($field['type'] == 'number')
                    {
                        $data_value = (int) $data_value;
                        $data_value = number_format($data_value);
                    }

                    if($field['type'] == 'file')
                    {
                        $data_value = '<a href="'.asset($data_value).'" target="_blank">Lihat File</a>';
                    }
                }
                else
                {
                    $data_value = $d->{$field};
                }

                $results[$key][] = $data_value;
            }

            $action = '';

            $action .= $this->actionButton($d);

            if($customAction)
            {
                $action .= $customAction($d);
            }
            else
            {
                $params = ['table'=>$this->table,'id'=>$d->id];
                if(isset($_GET['filter']))
                {
                    $params['filter'] = $_GET['filter'];
                }
                $action .= '<a href="'.routeTo('crud/edit',$params).'" class="btn btn-sm btn-warning"><i class="fas fa-pencil-alt"></i> '.__('crud.label.edit').'</a> ';
                $action .= '<a href="'.routeTo('crud/delete',$params).'" onclick="if(confirm(\''.__('crud.label.confirm_msg').'\')){return true}else{return false}" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> '.__('crud.label.delete').'</a>';
            }

            
            $results[$key][] = $action;
        }

        return [
            "draw" => $draw,
            "recordsTotal" => (int)$total,
            "recordsFiltered" => (int)$total,
            "data" => $results
        ];
    }
}