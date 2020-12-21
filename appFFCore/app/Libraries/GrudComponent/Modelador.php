<?php 
namespace App\Libraries\GrudComponent;

class Modelador {
  
  public $table;
  public $primaryKey;
  public $allowedFields;
  public $validationRules;

  public $returnType     = 'array';
  public $useSoftDeletes = true;

  public $useTimestamps = true;
  public $createdField  = 'created_at';
  public $updatedField  = 'updated_at';
  public $deletedField  = 'deleted_at';

  
  public $validationMessages = [];

  public $skipValidation     = false;

  public function __construct($tabela)
  {
    $dataTableConfig = $this->buildModel($tabela);
    $this->table = $dataTableConfig['table'];
    $this->primaryKey = $dataTableConfig['primaryKey'];
    $this->allowedFields = $dataTableConfig['allowedFields'];
    $this->validationRules = $dataTableConfig['validationRules'];
  }

  private function getMyValidationRules($field){
    $rules = "";
    $rules .= $field->nullable == "1" ? "" : "required|";
    $rules .= $field->type == "int" ? 'integer|' : 
             ($field->type == "varchar" ? "max_length[{$field->max_length}]|" : 
             ($field->type == "char" ? "max_length[{$field->max_length}]|" : 
             ($field->type == "float" ? "numeric|" : 
             ($field->type == "decimal" ? "decimal|" : 
             ($field->type == "double" ? "numeric|" : 
             ($field->type == "datetime" ? "valid_datetime|" : 
             ($field->type == "date" ? "valid_date|" : 
             ($field->type == "enum" ? "in_list[{$field->column_type}]|" : ""))))))));
    return substr($rules, 0, -1);
  }

  private function buildModel($tabela){
    $db = \Config\Database::connect();
    
    if (!$db->tableExists($tabela)){
      return false;
    }
    
    $fields = $db->getFieldData($tabela);
    
    $table = $this->getTabela($tabela);

    $allowedFields = [];
    $validationRules = [];
    $fieldPK = null;
    $Empresa = '';

    $indexKeys = $db->getIndexData($tabela);
    
    $FKDatas = $db->getForeignKeyData($tabela);

    $tableOwner = "";
    foreach ($FKDatas as $key => $FK) {
      if (empty($tableOwner)) {
        $tableOwner = strpos($FK->constraint_name,'owner') !== false ? $FK->foreign_table_name : '';
      }
    }
    
    foreach ($fields as $key => $field)
    {
      if(!in_array($field->name, ['created_at', 'updated_at', 'deleted_at'])){

        if ($field->primary_key !== 1) {
          $validationRules[$field->name] = $this->getMyValidationRules($field);
          $validationRules[$field->name] .= $this->getValidationRulesIsUnique($tabela, $field, $indexKeys);
        }
        
        if ($field->primary_key === 1) {
          $fieldPK = $field;
        }

        $allowedFields[] = $field->name;

        if(empty($Empresa))
          $Empresa = strpos($field->name, 'id_empresa') !== false ? $field->name : '';
      }
    }
    return ['table' => $table, 'primaryKey' => $fieldPK->name, 'allowedFields' => $allowedFields, 'validationRules' => $validationRules];
  }

  private function getTabela($tabela){
    return str_replace('tbl_', '', $tabela);
  }

  private function getValidationRulesIsUnique($table, $field, $indexKeys){
    foreach ($indexKeys as $key) {
      if ($key->type == 'UNIQUE'){
        if ($key->fields[0] == $field->name){
          return "|is_unique[{$table}.{$field->name}]";
        }
      }
    }
    return "";
  }
}

  