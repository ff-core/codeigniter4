<?php 
namespace App\Libraries\GrudComponent;

class Campos {

    protected $_inputs = [];

    public $_group_form = 'form-group';

    protected $_display_label = [];

    protected $_disabled = false;

    protected $_alias;

    protected $_groupSubmit;

    protected $_addReferencesKey = [];
    
    public function __construct($tabela, $groupSubmit){
        helper(['html','form']);
        $this->_tabela = $tabela;
        $this->_alias = md5($tabela);
        $this->_groupSubmit = $groupSubmit;
        $this->init($tabela);
    }

    public function disabled($bool = false){
        $this->_disabled = $bool;
        return $this;
    }

    public function defaultValue($fieldName, $displayAs = null)
    {
        if (is_array($fieldName)) {
            foreach ($fieldName as $field => $displayAs) {
                $this->_default_value[$field] = $displayAs;
            }
        } elseif ($displayAs !== null) {
            $this->_default_value[$fieldName] = $displayAs;
        }
        return $this;
    }

    public function displayAs($fieldName, $displayAs = null)
    {
        if (is_array($fieldName)) {
            foreach ($fieldName as $field => $displayAs) {
                $this->_display_label[$field] = $displayAs;
            }
        } elseif ($displayAs !== null) {
            $this->_display_label[$fieldName] = $displayAs;
        }
        return $this;
    }

    public function addReferencesKey($addReferencesKey){
        $this->_addReferencesKey = $addReferencesKey;
        return $this;
    }

    private function addInput($data = ''){
        $display = isset($this->_display_label[$data['name']]) ? $this->_display_label[$data['name']] : $data['name'];
        $this->_inputs[] = $this->divOpen();
        if ($data['type'] !== 'hidden')
            $this->_inputs[] = form_label($display, $data['id']);
        $this->_inputs[] = form_input($data);
        $this->_inputs[] = $this->divClose();
    }

    private function addDropdow($field, $options = [], $selected = [], $data = ''){
        $display = isset($this->_display_label[$field]) ? $this->_display_label[$field] : $field;
        $this->_inputs[] = $this->divOpen();
        $this->_inputs[] = form_label($display, $data['id']);
        $this->_inputs[] = form_dropdown($field, $options, $selected, $data);
        $this->_inputs[] = $this->divClose();
    }

    private function divOpen(){
        return "<div class='{$this->_group_form}'>";
    }

    private function divClose(){
        return "</div>";
    }

    private function init($tabela){
        $db = \Config\Database::connect();
    
        if (!$db->tableExists($tabela)){
            throw new \Exception("Tabela não existe na database.");
        }
        
        $this->fields = $db->getFieldData($tabela);
    }

    private function getType($field){
        return $field->type == "int" ? 'number' : 
                 ($field->type == "varchar" ? "text" : 
                 ($field->type == "char" ? "text" : 
                 ($field->type == "float" ? "number" : 
                 ($field->type == "decimal" ? "number" : 
                 ($field->type == "double" ? "numeric|" : 
                 ($field->type == "datetime" ? "date" : 
                 ($field->type == "date" ? "date" : "")))))));
    }

    public function render(){
        /*
            [name] => me_id
            [type] => int
            [max_length] => 11
            [nullable] => 
            [default] => 
            [column_type] => int(11
            [primary_key] => 1
         */
        
        foreach ($this->fields as $field) {
            
            if(!in_array($field->name, ['created_at', 'updated_at', 'deleted_at'])){
                if ($field->primary_key !== 1){
                    $type = $this->getType($field);
                    $required = empty($field->nullable) ? 'True' : 'False';

                    $data = [
                        'id'    => 'id_'.$field->name.'_'.$this->_groupSubmit.'_'.$this->_alias,
                        'class' => 'form-control class_'.$field->name.'_'.$this->_alias,
                        'required' => $required
                    ];

                    $data['type'] = $type;

                    if (!isset($this->_display_label[$field->name]))
                        $data['type'] = 'hidden';

                    if($this->_disabled)
                        $data['disabled'] = 'disabled';

                    if ($field->type !== "enum"){
                        
                        $data['name'] = $field->name;
                        $data['value'] = (isset($this->_default_value) && isset($this->_default_value[$field->name])) ? $data['value'] = $this->_default_value[$field->name] : $field->default;

                        if ($field->type == "int"){
                            if(!isset($this->_addReferencesKey[$field->name]))
                                $this->addInput($data);
                            else {
                                $hasRef = $this->_addReferencesKey[$field->name];
                                $db = \Config\Database::connect();
                                $sql = "SELECT {$hasRef['colunaReferenciada']}, {$hasRef['displayReferenciada']} FROM {$hasRef['tabelaReferenciada']} WHERE deleted_at is null ";
                                if (!empty($hasRef['where'])) {
                                    if(is_array($hasRef['where'])){
                                        $sql .= " WHERE ";
                                        foreach($hasRef['where'] as $key => $param){
                                            if(is_array($param)){
                                                $sinal = $param['sinal'];
                                                $valor = $param['valor'];
                                                $sql .= " {$key} {$sinal} '{$valor}' AND";
                                            } else {
                                                $sql .= " {$key} = '{$param}' AND";
                                            }
                                        }
                                        $sql = rtrim($sql, "AND");
                                    }
                                }

                                $result = $db->query($sql)->getResult('array');
                                
                                $options = [];
                                foreach ($result as $key => $value) {
                                    $options[$value[$hasRef['colunaReferenciada']]] = $value[$hasRef['displayReferenciada']];
                                }

                                $this->addDropdow($field->name,$options,"",$data);
                            }
                        } else {
                            $this->addInput($data);
                        }

                    } else {
                        if ($data['type'] !== 'hidden'){
                            unset($data['type']);
                            $partValue = explode(",", $field->column_type);
                            $options = [];
                            foreach ($partValue as $value) {
                                $options[$value] = $value;
                            }

                            $this->addDropdow($field->name,$options,"",$data);
                        } else {
                            $data['name'] = $field->name;
                            $data['value'] = (isset($this->_default_value) && isset($this->_default_value[$field->name])) ? $data['value'] = $this->_default_value[$field->name] : $field->default;
                            
                            $this->addInput($data);
                        }
                    }
                } else {
                    $data = [
                        'id'    => 'id_'.$field->name.'_'.$this->_groupSubmit.'_'.$this->_alias,
                        'class' => 'form-control class_'.$field->name.'_'.$this->_alias,
                        'required' => 'false',
                        'type' => 'hidden',
                        'name' => $field->name,
                        'value' => $field->default
                    ];
                    $this->addInput($data);
                }
            }
        }
        $html = "";
        foreach ($this->_inputs as $input) {
            $html .= $input . "\n";
        }
        return $html;
    }
    
}