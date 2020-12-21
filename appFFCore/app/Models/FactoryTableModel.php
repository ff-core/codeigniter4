<?php namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;
use App\Libraries\GrudComponent\Modelador;

class FactoryTableModel extends Model
{
  public $factory;

  public function __construct($tabela, ConnectionInterface &$db = null, ValidationInterface $validation = null){
    $factory = new Modelador($tabela);
    
    $this->factory = $factory;

    $this->table              = $factory->table;

    $this->primaryKey         = $factory->primaryKey;

    $this->allowedFields      = $factory->allowedFields;

    $this->validationRules    = $factory->validationRules;

    $this->returnType         = $factory->returnType;

    $this->useSoftDeletes     = $factory->useSoftDeletes;
  
    $this->useTimestamps      = $factory->useTimestamps;

    $this->createdField       = $factory->createdField;
    $this->updatedField       = $factory->updatedField;
    $this->deletedField       = $factory->deletedField;
      
    $this->validationMessages = $factory->validationMessages;
  
    $this->skipValidation     = $factory->skipValidation;

    parent::__construct($db, $validation);

  }

  

}