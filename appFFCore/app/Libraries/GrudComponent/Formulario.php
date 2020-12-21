<?php 
namespace App\Libraries\GrudComponent;

use App\Libraries\GrudComponent\Botao;
use App\Libraries\GrudComponent\Campos;

class Formulario {

    protected $_campos = null;

    protected $_botao = null;
       
    protected $_tabela;

    protected $_titulo;

    protected $_formAction;

    protected $_methodSubmit;

    protected $_alias;

    public function __construct(string $titulo, string $tabela, string $methodSubmit, Campos $campos = null)
    {
        helper(['form']);
        $this->init($titulo, $tabela, $methodSubmit, $campos);
    }

    private function init(string $titulo, string $tabela, string $methodSubmit, Campos $campos = null){
        $this->methodSubmit($methodSubmit);
        $this->_tabela = $tabela;
        $this->_alias = md5($tabela);
        $this->_campos = $campos;
        $this->_titulo = $titulo;
        $this->action();

        $db = \Config\Database::connect();
    
        if (!$db->tableExists($tabela)){
            throw new \Exception("Tabela não existe na database.");
        }
    }

    public function action($action = ''){
        if (empty($action)){
            $segments = "";
            $request = \Config\Services::request();
            
            if($request->uri->getSegment(1) !== "")
                $segments = $request->uri->getSegment(1) .'/'. 
                            $request->uri->getSegment(2) .'/'. 
                            $request->uri->getSegment(3);
            $this->_formAction = $segments;
            return $this;
        }
        $this->_formAction = base_url($action);
        return $this;
    }

    private function methodSubmit($methodSubmit){
        if (!in_array($methodSubmit, ['Add','Edit','Delete', 'View']))
            throw new \Exception("typeForm nao permite outros valores além dos Add, Edit, Delete e View.");
        $this->_methodSubmit = $methodSubmit;
        return $this;
    }
    

    public function addAllCampos(Campos $campos = null){
        if ($campos !== null)
            $this->_campos = $campos;

        if($this->_campos == null)
            $this->_campos = new Campos($this->_tabela, $this->_methodSubmit);

        return $this->_campos;
    }

    private function open(){
        $attributes = ['class' => 'frm_'.$this->_alias, 'id' => 'frm_'.$this->_alias.'_'.$this->_methodSubmit];
        $render = "";
        if(isset($this->_titulo))
            $render = "<h2> {$this->_titulo} </h2>";
        $render .= form_open($this->_formAction.'/', $attributes);

        $render .= form_input([
            'type'    => 'hidden',
            'name' => 'action',
            'value' => $this->_methodSubmit
        ]);
        return $render;    
    }

    private function close(){
        return form_close();
    }

    public function render(){
        if(in_array($this->_methodSubmit, ['Add', 'Edit'])){
            $this->_botao = new Botao($this->_tabela);
            $this->_botao->cancel($this->_methodSubmit)->save($this->_methodSubmit);
        } else if ($this->_methodSubmit == 'Delete'){
            $this->_botao = new Botao($this->_tabela);
            $this->_botao->cancel($this->_methodSubmit)->delete();
        }

        $html = $this->open();
        if($this->_campos !== null)
            $html .= $this->_campos->render();
        if($this->_botao !== null)
            $html .= $this->_botao->render();
        $html .= $this->close();
        return $html;
     }


}