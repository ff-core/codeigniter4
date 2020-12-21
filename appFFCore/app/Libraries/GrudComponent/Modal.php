<?php
namespace App\Libraries\GrudComponent;

class Modal
{

  protected $titulos = [];

  protected $targets = [];

  protected $forms = [];

  protected $tabela;

  public function __construct($tabela)
  {
    $this->tabela = $tabela;
    $this->_alias = md5($tabela);
  }

  public function add(string $titulo, Formulario $form)
  {
    $this->titulos[] = $titulo;
    $this->forms[] = $form;
    $this->targets[] = "Add";
    return $this;
  }

  public function edit(string $titulo, Formulario $form)
  {
    $this->titulos[] = $titulo;
    $this->forms[] = $form;
    $this->targets[] = "Edit";
    return $this;
  }

  public function delete(string $titulo, Formulario $form)
  {
    $this->titulos[] = $titulo;
    $this->forms[] = $form;
    $this->targets[] = "Delete";
    return $this;
  }

  public function view(string $titulo, Formulario $form)
  {
    $this->titulos[] = $titulo;
    $this->forms[] = $form;
    $this->targets[] = "View";
    return $this;
  }

  public function custom(string $titulo, String $html, string $target = '')
  {
    $this->titulos[] = $titulo;
    $this->forms[] = $html;
    $this->targets[] = $target;
    return $this;
  }

  public function render()
  {
    $html = "";
    foreach ($this->forms as $key => $form) {
      
      $html_form = "";
      if(is_object(($form)))
        $html_form = $form->render();
      else if (is_string($form))
        $html_form = $form;

      $html .= $this->Html($html_form, $this->titulos[$key], $this->targets[$key].$this->_alias);
    }
    return $html;
  }

  private function Html($render, string $titulo, string $target)
  {
    return "
<!-- Modal -->
<div class='modal fade' id='modal{$target}' tabindex='-1' aria-labelledby='modal{$target}Label' aria-hidden='true'>
  <div class='modal-dialog modal-lg modal-dialog-centered'>
    <div class='modal-content'>
      <div class='modal-header'>
        <h5 class='modal-title' id='modal{$target}Label'>{$titulo}</h5>
        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
        </button>
      </div>
      <div class='modal-body'>
        {$render}
      </div>
      <div class='modal-footer'>
        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
      </div>
    </div>
  </div>
</div>";
  }
}
