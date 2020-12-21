<?php

namespace App\Libraries\GrudComponent;

use App\Libraries\GrudComponent\Filtro;
use App\Libraries\GrudComponent\Formulario;
use App\Libraries\GrudComponent\Gride;

use CodeIgniter\Controller;

class Controlador extends Controller
{
    public $tabela;

    public $titulo;

    public $nome;

    public $_where = [];

    public $tableConfig = [];

    private $_display_as_add;

    private $_display_as_edit;

    private $_display_as_view;

    private $_display_as_gride;

    private $_fieldname_default_value;

    private $_display_as_filtro;

    private $_display_as_link;

    private $_display_delete = false;

    protected $helpers = ['form', 'html', 'uri'];

    private $_owner;

    protected $data = [];

    protected $_addReferencesKey = [];

    public function __construct($owner)
    {
        $request = \Config\Services::request();
        $params = $request->uri->getSegments();
        
        array_shift($params);
        array_shift($params);
        
        $this->data['controller'] = $request->uri->getSegment(1);
        $this->data['function'] = $request->uri->getSegment(2);
        $this->data['params'] = $params;

        $this->_owner = $owner;
    }

    public function displayAsAdd($fieldName, $displayAsAdd = null)
    {
        if (is_array($fieldName)) {
            foreach ($fieldName as $field => $displayAsAdd) {
                $this->_display_as_add[$field] = $displayAsAdd;
            }
        } elseif ($displayAsAdd !== null) {
            $this->_display_as_add[$fieldName] = $displayAsAdd;
        }
        return $this;
    }

    public function displayAsEdit($fieldName, $displayAsEdit = null)
    {
        if (is_array($fieldName)) {
            foreach ($fieldName as $field => $displayAsEdit) {
                $this->_display_as_edit[$field] = $displayAsEdit;
            }
        } elseif ($displayAsEdit !== null) {
            $this->_display_as_edit[$fieldName] = $displayAsEdit;
        }
        return $this;
    }

    public function displayAsDelete($bool = false)
    {
        $this->_display_delete = $bool;
        return $this;
    }

    public function displayAsView($fieldName, $displayAsView = null)
    {
        if (is_array($fieldName)) {
            foreach ($fieldName as $field => $displayAsView) {
                $this->_display_as_view[$field] = $displayAsView;
            }
        } elseif ($displayAsView !== null) {
            $this->_display_as_view[$fieldName] = $displayAsView;
        }
        return $this;
    }

    public function displayAsGride($fieldName, $displayAsGride = null)
    {
        if (is_array($fieldName)) {
            foreach ($fieldName as $field => $displayAsGride) {
                $this->_display_as_gride[$field] = $displayAsGride;
            }
        } elseif ($displayAsGride !== null) {
            $this->_display_as_gride[$fieldName] = $displayAsGride;
        }
        return $this;
    }

    public function displayAsfiltro($fieldName, $displayAsfiltro = null){
        if (is_array($fieldName)) {
            foreach ($fieldName as $field => $displayAsfiltro) {
                $this->_display_as_filtro[$field] = $displayAsfiltro;
            }
        } elseif ($displayAsfiltro !== null) {
            $this->_display_as_filtro[$fieldName] = $displayAsfiltro;
        }
        return $this;
    }

    public function displayAsLink($link, $displayAs = null)
    {
        if (is_array($link)) {
            foreach ($link as $field => $displayAs) {
                $this->_display_as_link[$field] = $displayAs;
            }
        } elseif ($link !== null) {
            $this->_display_as_link[$link] = $displayAs;
        }
        return $this;
    }

    public function fieldNameDefaultValue($fieldName, $valueDefault = null)
    {
        if (is_array($fieldName)) {
            foreach ($fieldName as $field => $displayAsGride) {
                $this->_fieldname_default_value[$field] = $displayAsGride;
            }
        } elseif ($valueDefault !== null) {
            $this->_fieldname_default_value[$fieldName] = $valueDefault;
        }
        return $this;
    }
    
    public function addReferencesKey($colunaReferencia, $tabelaReferenciada, $colunaReferenciada, $displayReferenciada, $where = []) {
        $this->_addReferencesKey[$colunaReferencia] = [
            'tabelaReferenciada' => $tabelaReferenciada,
            'colunaReferenciada' => $colunaReferenciada,
            'displayReferenciada' => $displayReferenciada,
            'where' => $where
        ];
    }

    public function where($where = []){
        foreach ($where as $key => $value) {
            $this->_where[$key] = $value;
        }
        return $this;
    }

    public function show($data = [], $view = 'cadastro')
    {
        $this->data['titulo'] = $this->titulo;
        $this->data['alias'] = md5($this->tabela);
        
        $gride = new Gride($this->titulo, $this->tabela);
        $gride->where($this->_where);
        $gride->displayAs($this->_display_as_gride)->addReferencesKey($this->_addReferencesKey);

        if(isset($this->_display_as_filtro)){
            $filtro = new Filtro($this->tabela, $this->nome);
            $filtro->displayAs($this->_display_as_filtro)->addReferencesKey($this->_addReferencesKey);

            $gride->addFiltro($filtro);

            foreach ($this->_display_as_filtro as $campo => $display) {
                if ($this->_owner->request->getPost($campo) !== null && $this->_owner->request->getPost($campo) !== '')
			        $gride->where([$campo => $this->_owner->request->getPost($campo)]);
            }
        }

        if(isset($this->_display_as_add)){
            $form = new Formulario("Adicionar {$this->nome}", $this->tabela, 'Add');
            $form->addAllCampos()
                ->defaultValue($this->_fieldname_default_value)
                ->displayAs($this->_display_as_add)
                ->addReferencesKey($this->_addReferencesKey);
            $gride->buttonModalAdd($form);
        }

        if(isset($this->_display_as_link)){
            foreach ($this->_display_as_link as $link => $display) {
                $gride->addLink($display, $link);
            }
        }

        if(isset($this->_display_as_edit)){
            $form = new Formulario("Editar {$this->nome}", $this->tabela, 'Edit');
            $form->addAllCampos()
                ->defaultValue($this->_fieldname_default_value)
                ->displayAs($this->_display_as_edit)
                ->addReferencesKey($this->_addReferencesKey);
            $gride->buttonModalEdit($form);
        }

        if(isset($this->_display_as_view)){
            $form = new Formulario( "Visualizar {$this->nome}", $this->tabela,'View');
            $form->addAllCampos()
                ->defaultValue($this->_fieldname_default_value)
                ->displayAs($this->_display_as_view)
                ->disabled(true)
                ->addReferencesKey($this->_addReferencesKey);
            $gride->buttonModalView($form);
        }

        if($this->_display_delete){
            $form = new Formulario("Deseja Deletar o {$this->nome}?", $this->tabela, 'Delete');
            $form->addAllCampos();
            $gride->buttonModalDelete($form);
        }

        if($this->_owner->request->isAJAX()){
            if ($this->_owner->request->getMethod() === 'post') {
                $model = new \App\Models\FactoryTableModel($this->tabela);
                //echo $model->factory->primaryKey;
                //echo json_encode($this->_owner->request->getPost());
                
                if ($this->_owner->request->getPost('action') == 'Add'){
                    if ($model->insert($this->_owner->request->getPost()) === false){
                        $json = [
                            'status' => 'false',
                            'message' => 'Falha na tentativa de inserir um novo registro.',
                            'errors' => $model->errors()
                        ];
                        echo json_encode($json);
                    } else {
                        echo json_encode([
                            'status' => 'true',
                            'message' => 'Registro inserido com sucesso!']);
                    }
                }
                
                if ($this->_owner->request->getPost('action') == 'Edit'){
                    if ($model->update($this->_owner->request->getPost($model->factory->primaryKey), $this->_owner->request->getPost())  === false){
                        $json = [
                            'status' => 'false',
                            'message' => 'Falha na tentativa de alterar um registro.',
                            'errors' => $model->errors()
                        ];
                        echo json_encode($json);
                    } else {
                        echo json_encode([
                            'status' => 'true',
                            'message' => 'Registro alterado com sucesso!']);
                    }
                }

                if ($this->_owner->request->getPost('action') == 'Delete'){
                    $model->delete($this->_owner->request->getPost($model->factory->primaryKey));
                    
                    echo json_encode([
                        'status' => 'true',
                        'message' => 'Registro foi deletado com sucesso!']);
                    
                }

                if ($this->_owner->request->getPost('action') == 'Get'){
                    $data = $model->find($this->_owner->request->getPost('id'));
                    echo json_encode($data);
                }

                if ($this->_owner->request->getPost('action') == 'GetAll'){
                    $gride->where($this->_where);
                    $dados = $gride->getDadosGrid();
                    $total = count($dados);
                    echo json_encode([
                        'draw' => 1,
                        'recordsTotal' => $total,
                        'recordsfiltroed' => $total,
                        'data' => $dados]);
                }

                die();
            }
        } else {
            $this->data['data'] = $gride->render();
        }

        foreach ($data as $key => $value) {
            $this->data[$key] = $value;
        }
        
        return view($view, $this->data);
    }

}
