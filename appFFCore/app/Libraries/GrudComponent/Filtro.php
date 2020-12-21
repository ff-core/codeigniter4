<?php 
namespace App\Libraries\GrudComponent;

class Filtro {

    protected $_display_filtro = [];
    protected $_nome;
    protected $_alias;
    protected $_inputs = [];
    protected $_addReferencesKey = [];

    public function __construct($tabela, $nome = ''){
        helper(['html','form']);
        $this->_tabela = $tabela;
        $this->_nome = empty($nome) ? 'Filtro' : "Filtro do {$nome}";

        $db = \Config\Database::connect();
    
        if (!$db->tableExists($tabela)){
            throw new \Exception("Tabela não existe na database.");
        }
        
        $this->fields = $db->getFieldData($tabela);
    }

    public function displayAs($fieldName, $displayAs = null)
    {
        if (is_array($fieldName)) {
            foreach ($fieldName as $field => $displayAs) {
                $this->_display_filtro[$field] = $displayAs;
            }
        } elseif ($displayAs !== null) {
            $this->_display_filtro[$fieldName] = $displayAs;
        }
        return $this;
    }

    public function addReferencesKey($addReferencesKey){
        $this->_addReferencesKey = $addReferencesKey;
        return $this;
    }

    private function addInput($data = ''){
        $this->_inputs[] = "<div class='col-2'>";
        $this->_inputs[] = form_input($data);
        $this->_inputs[] = "</div>";
    }

    private function addDropdow($field, $options = [], $selected = [], $data = ''){
        $this->_inputs[] = "<div class='col-4'>";
        $this->_inputs[] = form_dropdown($field->name, $options, $selected, $data);
        $this->_inputs[] = "</div>";
    }

    public function render(){
        $this->_inputs[] = "<h2 class='mt-2'>{$this->_nome}</h2>";
        $this->_inputs[] = "<hr class='my-3'>";
        $this->_inputs[] = "<form class='row g-3 align-items-center'>";

        foreach ($this->fields as $field) {
            foreach ($this->_display_filtro as $campo => $display) {
                if($field->name === $campo){
                    $data['name'] = $field->name;
                    $data['id'] = 'id_filtro_'.$field->name;
                    $data['class'] = "form-control";
                    $data['placeholder'] = $display;
                    
                    if (strpos($field->type, "enum") !== false){
                        $partValue = explode(",", $field->column_type);
                        $options[''] = 'Selecionar';
                        foreach ($partValue as $value) {
                            $options[$value] = $value;
                        }
                        $data['class'] = "form-select";
                        $this->addDropdow($field, $options, [], $data);
                        $options = [];
                    } else {
                        if (isset($this->_addReferencesKey[$field->name])){
                            $ref = $this->_addReferencesKey[$field->name];
                            $db = \Config\Database::connect();
                            $sql = "SELECT {$ref['colunaReferenciada']}, {$ref['displayReferenciada']} FROM {$ref['tabelaReferenciada']} WHERE deleted_at is null ";
                            if (!empty($ref['where'])) {
                                if(is_array($ref['where'])){                                    
                                    foreach($ref['where'] as $key => $param){
                                        if(is_array($param)){
                                            $sinal = $param['sinal'];
                                            $valor = $param['valor'];
                                            $sql .= " AND {$key} {$sinal} '{$valor}'";
                                        } else {
                                            $sql .= " AND {$key} = '{$param}'";
                                        }
                                    }
                                }
                            }

                            $result = $db->query($sql)->getResult('array');
                            
                            $options[''] = 'Selecionar';
                            foreach ($result as $key => $value) {
                                $options[$value[$ref['colunaReferenciada']]] = $value[$ref['displayReferenciada']];
                            }
                            $data['class'] = "form-select";
                            $this->addDropdow($field, $options, [], $data);
                            $options = [];
                        } else {
                            $data['type'] = "text";
                            $this->addInput($data);
                        }
                    }
                }
            }
        }

        $this->_inputs[] = "<div class='col align-self-end'>";
        $this->_inputs[] = "<button type='button' class='btn btn-primary' id='btnConsultar'>Consultar</button>";
        $this->_inputs[] = "</div>";
        $this->_inputs[] = "</form>";


        $html = "";
        foreach ($this->_inputs as $input) {
            $html .= $input . "\n";
        }
        return $html;
    }
}