<?php 
namespace App\Libraries\GrudComponent;

class Botao {

    protected $_buttons = [];

    protected $_tabela;

    public function __construct($tabela = '')
    {
        helper(['html','form']);
        $this->_tabela = $tabela;
        $this->_alias = md5($tabela);
    }
    public function custom($data = '', string $content = '', $extra = ''){
        $this->set($data, $content, $extra);
        return $this;
    }

    public function save($action = ''){
        $extra = [
            "type" => "button",
            "id" => "idBtnSave".$action.$this->_alias,
            "class" => "btn btn-outline-primary"
        ];
        $this->set("btnSave","Salvar",$extra);
        return $this;
    }

    public function cancel($action = ''){
        $extra = [
            "type" => "button",
            "id" => "idBtnCancel".$action.$this->_alias,
            "class" => "btn btn-outline-secondary"
        ];
        $this->set("btnCancel","Cancelar",$extra);
        return $this;
    }

    public function delete(){
        $extra = [
            "type" => "button",
            "id" => "idBtnDelete".$this->_alias,
            "class" => "btn btn-outline-danger"
        ];
        $this->set("btnDelete","Deletar",$extra);
        return $this;
    }

    public function render(){
        $html = "<hr class='my-4'>";
        $html .= "<div class='d-flex justify-content-between'>";
        foreach ($this->_buttons as $button) {
            $html .= $button . "\n";
        }
        return $html . "</div>";
    }

    private function set($name = '', string $display = '', $extra = ''){
        $this->_buttons[] = form_button($name, $display, $extra);
    }

}