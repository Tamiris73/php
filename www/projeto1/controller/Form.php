<?php
class Form
{
  public function __construct()
  {
    Transaction::open();
  }
  public function controller()
  {
    $form = new Template("view/form.html");
    $form->set("id", "");
    $form->set("titulo", "");
    $form->set("duracao", "");
    $form->set("assunto", "");
    $retorno["msg"] = $form->saida();
    return $retorno;
  }

  public function salvar()
  {
    if (isset($_POST["titulo"]) && isset($_POST["duracao"]) && isset($_POST["assunto"])) {
      try {
        $conexao = Transaction::get();
        $titulo = $conexao->quote($_POST["titulo"]);
        $duracao = $conexao->quote($_POST["duracao"]);
        $assunto = $conexao->quote($_POST["assunto"]);
        $crud = new Crud();
        if (empty($_POST["id"])) {
          $retorno = $crud->insert(
            "videos",
            "titulo,duracao,assunto",
            "{$titulo},{$duracao},{$assunto}"
          );
        } else {
          $id = $conexao->quote($_POST["id"]);
          $retorno = $crud->update(
            "videos",
            "titulo={$titulo}, duracao={$duracao}, assunto={$assunto}",
            "id={$id}"
          );
        }
      } catch (Exception $e) {
        $retorno["msg"] = "Ocorreu um erro! " . $e->getMessage();
        $retorno["erro"] = TRUE;
      }
    } else {
      $retorno["msg"] = "Preencha todos os campos! ";
      $retorno["erro"] = TRUE;
    }
    return $retorno;
  }

  public function __destruct()
  {
    Transaction::close();
  }
}
