<?php 
namespace App\Controllers;

use App\Libraries\GrudComponent\Controlador;

class Exemples extends BaseController
{

	public function index()
	{
		return view('welcome_message');
	}

	public function Escritorios()
	{	
		$Ct = new Controlador($this);
		$Ct->tabela = 'escritorios';
		$Ct->nome = 'Escritórios';
		$Ct->titulo = 'Cadastrar de Escritórios';

		$Ct->displayAsFiltro([
			'cidade' => 'Cidade'
		]);
		$Ct->displayAsGride([
			'id' => 'Id', 
			'cidade' => 'Cidade', 
			'telefone' => 'Telefone', 
			'endereco' => 'Endereço'
		]);
		$Ct->displayAsAdd([
			'id' => 'Id', 
			'cidade' => 'Cidade', 
			'telefone' => 'Telefone', 
			'endereco' => 'Endereço'
		]);
		$Ct->displayAsEdit([
			'id' => 'Id', 
			'cidade' => 'Cidade', 
			'telefone' => 'Telefone', 
			'endereco' => 'Endereço'
		]);
		$Ct->displayAsView([
			'id' => 'Id', 
			'cidade' => 'Cidade', 
			'telefone' => 'Telefone', 
			'endereco' => 'Endereço'
		]);
		$Ct->displayAsDelete(true);
		
		$Ct->displayAsLink([
			'/Exemples/Funcionarios' => 'Funcionários'
		]);
		/*$Ct->addReferencesKey('pe_id_pessoa', 'tbl_pessoa', 'p_id', 'p_nome', []);
		$Ct->fieldNameDefaultValue(['pe_id_empresa' => '1' , 'pe_id_usuario' => '1']);
		$Ct->where(['pe_id' => '1']);*/	
		
		return $Ct->show();
	}

	public function Funcionarios($id){
		$Ct = new Controlador($this);
		$Ct->tabela = 'empregados';
		$Ct->nome = 'Funcionário';
		$Ct->titulo = 'Cadastrar de Funcionários';

		$Ct->displayAsFiltro([
			'email' => 'E-mail'
		]);
		$Ct->displayAsGride([
			'id' => 'Id', 
			'nome' => 'Nome', 
			'email' => 'E-mail', 
			'salario' => 'Salário'
		]);
		$Ct->displayAsAdd([
			'id' => 'Id', 
			'nome' => 'Nome', 
			'email' => 'E-mail', 
			'salario' => 'Salário'
		]);
		$Ct->displayAsEdit([
			'id' => 'Id', 
			'nome' => 'Nome', 
			'email' => 'E-mail', 
			'salario' => 'Salário'
		]);
		$Ct->displayAsView([
			'id' => 'Id', 
			'nome' => 'Nome', 
			'email' => 'E-mail', 
			'salario' => 'Salário'
		]);
		$Ct->displayAsDelete(true);
		
		$Ct->fieldNameDefaultValue(['id_escritorio' => $id]);
		$Ct->where(['id_escritorio' => $id]);

		return $Ct->show();
	}

	public function Filmes(){
		$Ct = new Controlador($this);
		$Ct->tabela = 'filme';
		$Ct->nome = 'Filmes';
		$Ct->titulo = 'Cadastrar Filmes';

		$Ct->displayAsFiltro([
			'ano' => 'Ano'
		]);
		$Ct->displayAsGride([
			'id' => 'Id', 
			'titulo' => 'Título', 
			'ano' => 'Ano'
		]);
		$Ct->displayAsAdd([
			'id' => 'Id', 
			'titulo' => 'Título', 
			'ano' => 'Ano'
		]);
		$Ct->displayAsEdit([
			'id' => 'Id', 
			'titulo' => 'Título', 
			'ano' => 'Ano'
		]);
		$Ct->displayAsView([
			'id' => 'Id', 
			'titulo' => 'Título', 
			'ano' => 'Ano'
		]);
		$Ct->displayAsDelete(true);
		
		$Ct->displayAsLink([
			'/Exemples/FilmAtoresView' => 'Atores do Filme'
		]);
				
		return $Ct->show();
	}

	public function FilmAtoresView($id){
		$Ct = new Controlador($this);
		$Ct->tabela = 'filme_atores';
		$Ct->nome = 'Atores do Filme';
		$Ct->titulo = 'Visualizar Atores do Filme';

		$Ct->displayAsGride([
			'id' => 'Id', 
			'id_filme' => 'Filme', 
			'id_atores' => 'Ator'
		]);
		
		$Ct->addReferencesKey('id_filme', 'filme', 'id', 'titulo', []);
		$Ct->addReferencesKey('id_atores', 'atores', 'id', 'nome', []);
		$Ct->where(['id_filme' => $id]);
		
		return $Ct->show();
	}

	public function Atores(){
		$Ct = new Controlador($this);
		$Ct->tabela = 'atores';
		$Ct->nome = 'Atores';
		$Ct->titulo = 'Cadastrar de Atores';

		$Ct->displayAsFiltro([
			'nome' => 'Nome'
		]);
		$Ct->displayAsGride([
			'id' => 'Id', 
			'nome' => 'Nome'
		]);
		$Ct->displayAsAdd([
			'id' => 'Id', 
			'nome' => 'Nome'
		]);
		$Ct->displayAsEdit([
			'id' => 'Id', 
			'nome' => 'Nome'
		]);
		$Ct->displayAsView([
			'id' => 'Id', 
			'nome' => 'Nome'
		]);
		$Ct->displayAsDelete(true);
		
		$Ct->displayAsLink([
			'/Exemples/AtoresFilmeView' => 'Filmes do Atores'
		]);
		
		return $Ct->show();
	}

	public function AtoresFilmeView($id){
		$Ct = new Controlador($this);
		$Ct->tabela = 'filme_atores';
		$Ct->nome = 'Atores do Filme';
		$Ct->titulo = 'Visualizar Filmes por Ator';

		$Ct->displayAsGride([
			'id' => 'Id', 
			'id_filme' => 'Filme', 
			'id_atores' => 'Ator'
		]);
		
		$Ct->addReferencesKey('id_filme', 'filme', 'id', 'titulo', []);
		$Ct->addReferencesKey('id_atores', 'atores', 'id', 'nome', []);
		$Ct->where(['id_atores' => $id]);
		
		return $Ct->show();
	}

	public function FilmeAtores(){
		$Ct = new Controlador($this);
		$Ct->tabela = 'filme_atores';
		$Ct->nome = 'Filme por Atores';
		$Ct->titulo = 'Cadastrar Atores por Filme';

		$Ct->displayAsGride([
			'id' => 'Id', 
			'id_filme' => 'Filme', 
			'id_atores' => 'Ator'
		]);
		$Ct->displayAsAdd([
			'id' => 'Id', 
			'id_filme' => 'Filme', 
			'id_atores' => 'Ator'
		]);
		$Ct->displayAsEdit([
			'id' => 'Id', 
			'id_filme' => 'Filme', 
			'id_atores' => 'Ator'
		]);
		$Ct->displayAsView([
			'id' => 'Id', 
			'id_filme' => 'Filme', 
			'id_atores' => 'Ator'
		]);
		$Ct->displayAsDelete(true);
		
		$Ct->addReferencesKey('id_filme', 'filme', 'id', 'titulo', []);
		$Ct->addReferencesKey('id_atores', 'atores', 'id', 'nome', []);
		
		return $Ct->show();
	}
}
