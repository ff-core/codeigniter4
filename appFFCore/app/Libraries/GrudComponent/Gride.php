<?php 
namespace App\Libraries\GrudComponent;

use App\Libraries\GrudComponent\Modal;
use App\Libraries\GrudComponent\Filtro;

class Gride {

    protected $_titulo;

    protected $tabela;

    protected $_where = [];

    protected $_model;

    private $_links;

    private $_display_as;

    protected $_buttonAdd = false;

    protected $_modal = null;

    protected $_url;

    protected $_alias;

    protected $_primaryKey;

    protected $_addReferencesKey = [];

    protected $_filtro;

    public function __construct($titulo, $tabela, Modal $modal = null)
    {
        if ($modal === null)
            $this->_modal = new Modal($tabela);
        else
            $this->_modal = $modal;

        $this->_titulo = $titulo;
        $this->_tabela = $tabela;
        $this->_alias = md5($tabela);
        $this->_url = base_url();

        $this->_primaryKey = $this->getPK($tabela);

        $this->_model = new \App\Models\FactoryTableModel($this->_tabela);
    }
    
    public function modal(Modal $modal = null){
        if ($modal !== null){
            $this->campos = $modal;
        }

        if($this->_modal == null){
            $this->_modal = new modal($this->_tabela);
        }

        return $this;
    }

    public function where(array $where){
        foreach ($where as $key => $value) {
            $this->_where[$key] = $value;
        }
        return $this;
    }

    public function buttonModalAdd(Formulario $form){
        if($form == null)
            throw new \Exception("Gride inicializado como modal. É Obrigatório informar o formulário para o Modal Adicionar");

        $this->_buttonAdd = true;
        $this->_modal->add('Adicionar', $form);
    }

    public function buttonModalEdit(Formulario $form){
        if($form == null)
            throw new \Exception("Gride inicializado como modal. É Obrigatório informar o formulário para o Modal Editar");
        $this->_modal->edit('Editar', $form);
        $id = $this->_tabela.'.'.$this->_primaryKey;
        $this->_links[] = "<a href=\"#',{$id},'\" class=\"text-info idActionEdit{$this->_alias}\" data-id=\"',{$id},'\" data-bs-toggle=\"modal\" data-bs-target=\"#modalEdit{$this->_alias}\"> Editar </a>";
    }

    public function buttonModalDelete(Formulario $form){
        if($form == null)
            throw new \Exception("Gride inicializado como modal. É Obrigatório informar o formulário para o Modal Deletar");
        $this->_modal->delete('Deletar', $form);
        $id = $this->_tabela.'.'.$this->_primaryKey;
        $this->_links[] = "<a href=\"#',{$id},'\" class=\"text-danger idActionDelete{$this->_alias}\" data-id=\"',{$id},'\" data-bs-toggle=\"modal\" data-bs-target=\"#modalDelete{$this->_alias}\"> Deletar </a>";
    }

    public function buttonModalView(Formulario $form ){
        if($form == null)
            throw new \Exception("Gride inicializado como modal. É Obrigatório informar o formulário para o Modal Visualizar");
        $this->_modal->view('Visualizar', $form);
        $id = $this->_tabela.'.'.$this->_primaryKey;
        $this->_links[] = "<a href=\"#',{$id},'\" class=\"text-secondary idActionView{$this->_alias}\" data-id=\"',{$id},'\" data-bs-toggle=\"modal\" data-bs-target=\"#modalView{$this->_alias}\"> Visualizar </a>";
    
    }

    public function buttonModalCustom(String $display, string $titulo, String $html, string $target = ''){
        $this->_modal->custom($titulo, $html, $target);
        $id = $this->_tabela.'.'.$this->_primaryKey;
        $this->_links[] = "<a href=\"#',{$id},'\" data-id=\"',{$id},'\" data-bs-toggle=\"modal\" class=\"idAction{$target}{$this->_alias}\" data-bs-target=\"#modal{$target}{$this->_alias}\"> {$display} </a>";
    }

    public function addLink($display, $href){
        $id = $this->_tabela.'.'.$this->_primaryKey;
        $this->_links[] = "<a href=\"{$this->_url}{$href}/',{$id},'\" data-id=\"',{$id},'\" > $display </a>";
    }

    public function addFiltro(Filtro $filtro){
        $this->_filtro = $filtro;
    }

    public function addReferencesKey($addReferencesKey){
        $this->_addReferencesKey = $addReferencesKey;
    }

    public function displayAs($fieldName, $displayAs = null)
    {
        if (is_array($fieldName)) {
            foreach ($fieldName as $field => $displayAs) {
                $this->_display_as[$field] = $displayAs;
            }
        } elseif ($displayAs !== null) {
            $this->_display_as[$fieldName] = $displayAs;
        }
        return $this;
    }

    private function getFieldSelect(){
        $link = $this->renderLink();
        if(!isset($this->_display_as)){
            $select = $this->_model->allowedFields;
            $header = $this->_model->allowedFields;
            foreach ($select as $key => $value) 
                $newselect[$this->_tabela.'.'.$key] = $value;
            if(!empty($link)){
                array_push($newselect, $link);
                array_push($header, 'Ação');
            }
        } else {
            $header = $this->_display_as;
            foreach ($header as $key => $value) 
                $newHeader[$this->_tabela.'.'.$key] = $value;
            if(!empty($link)){
                $newHeader[$link] = 'Ação';
            }
            $newselect = array_keys($newHeader);
        }

        return $newselect;
    }

    private function getHeaderes(){
        $link = $this->renderLink();

        if(!isset($this->_display_as)){
            $select = $this->_model->allowedFields;
            $header = $this->_model->allowedFields;
            if(!empty($link)){
                array_push($select, $link);
                array_push($header, 'Ação');
            }
        } else {
            $header = $this->_display_as;
            if(!empty($link)){
                $header[$link] = 'Ação';
            }
            $select = array_keys($header);
        }

        return $header;
    }

    public function getDadosGrid($ajax = true){
        $select = $this->getFieldSelect();

        foreach ($select as $key => $valor) {
            $valor = str_replace($this->_tabela.'.', "", $valor);
            if (isset($this->_addReferencesKey[$valor])){
                $ref = $this->_addReferencesKey[$valor];
                $select[$key] = $ref['tabelaReferenciada'].'.'.$ref['displayReferenciada'] . ' as ' . $valor;
            }    
        }        

        $this->_model->select($select);
       
        foreach ($select as $key => $valor) {
            foreach ($this->_addReferencesKey as $campo => $ref) {
                if (strpos($valor, $campo) !== false)
                    $this->_model->join($ref['tabelaReferenciada'], $ref['tabelaReferenciada'].'.'.$ref['colunaReferenciada'] . ' = ' . $this->_tabela . '.' . $campo);    
            }                
        }

        foreach ($this->_where as $key => $value) {
            $this->_model->where($key,$value);
        }
        
        try
        {
            $dados = $this->_model->findAll();
            //die($this->_model->getLastQuery());
        }
        catch (\Exception $e)
        {
            //die($this->_model->getLastQuery());
            die($e->getMessage());
        }
        
        
        if (!$ajax){
            return $dados;
        } else {
            $data = [];
            foreach($dados as $dado){
                $result = [];
                foreach($dado as $value){
                    $result[] = $value;
                }
                $data[] = $result;
            }

            return $data;
        }
    }

    public function render(){
        $request = \Config\Services::request();

        //$dados = $this->getDadosGrid();
        $dados = [$this->getHeaderes()];

        $table = new \CodeIgniter\View\Table();   
        $template = [
            'table_open' => '<table border="1" cellpadding="2" cellspacing="1" class="table table-bordered table-hover " id="datatable-primary">',
            'thead_open'         => '<thead class="table-dark">',
        ];
        $table->setTemplate($template);

        $html = "";
        if (isset($this->_filtro))
            $html .= $this->_filtro->render();
        $html .= "
        <div class='d-flex justify-content-between my-4'> ";
        if ($request->uri->getSegment(3) !== '')
            $html .= "
                <button type='button' class='btn btn-outline-dark' id='btnVoltar'>Voltar</button>";
        $html .= "            
            <h2>{$this->_titulo}</h2> ";
        if ($this->_buttonAdd)
            $html .= "<button type='button' class='btn btn-outline-primary idActionAdd{$this->_alias}' data-bs-toggle='modal' data-bs-target='#modalAdd{$this->_alias}'> Novo </button>";
        $html .= "</div>";
        $html .= $table->setCaption('')->generate($dados);
        if($this->_modal !== null )
            $html .= $this->_modal->render();
        return $html;
    }

    private function renderLink(){
        if (!empty($this->_links)){
            $html = "concat('";
            foreach ($this->_links as $value) {
                $html .= $value;
            }
            $html .= "') as Ação";
            return $html;
        }
        return "";
    }

    private function getPK($tabela){
        $db = \Config\Database::connect();
        $fields = $db->getFieldData($tabela);

        foreach ($fields as $key => $field)
        {
            if ($field->primary_key === 1) {
                return $field->name;
            }
        }

        return null;
    }
}