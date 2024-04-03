<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AlunniController
{
  public function index(Request $request, Response $response, $args){

    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $result = $mysqli_connection->query("SELECT * FROM alunni");
    $results = $result->fetch_all(MYSQLI_ASSOC);

    $response->getBody()->write(json_encode($results));
    return $response->withHeader("Content-type", "application/json")->withStatus(200);

  }

  public function alunno(Request $request, Response $response, $args){

    $id = intval($args["id"]);

    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $result = $mysqli_connection->query("SELECT * FROM alunni WHERE alunni.id = $id");
    $results = $result->fetch_all(MYSQLI_ASSOC);

    $response->getBody()->write(json_encode($results));
    return $response->withHeader("Content-type", "application/json")->withStatus(200);

  }

  public function create(Request $request, Response $response, $args){

    $body = $request->getBody()->getContents();
    $params = json_decode($body, true);
    $nome = $params["nome"];
    $cognome = $params["cognome"];

    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $result = $mysqli_connection->query("SELECT alunni.id FROM alunni WHERE alunni.nome = '$nome' AND alunni.cognome = '$cognome'");
    
    if($result->num_rows == 0) {
    
      $result = $mysqli_connection->query("Insert into alunni (nome, cognome) values ('$nome', '$cognome')");
      $response->getBody()->write("Alunno aggiunto con successo");
      return $response->withHeader("Content-type", "application/json")->withStatus(201);

    }

    else {

      $response->getBody()->write("Alunno già esistente");
      return $response->withHeader("Content-type", "application/json")->withStatus(201);

    }

  }

  public function update(Request $request, Response $response, $args){

    $body = $request->getBody()->getContents();
    $params = json_decode($body, true);
    $nome = $params["nome"];
    $cognome = $params["cognome"];
    $id = intval($args["id"]);

    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $result = $mysqli_connection->query("SELECT * FROM alunni WHERE alunni.id = $id");
    
    if($result->num_rows == 0) {
    
      $response->getBody()->write("Alunno inesistente");
      return $response->withHeader("Content-type", "application/json")->withStatus(200);

    }

    else {

      $result = $mysqli_connection->query("Update alunni set alunni.nome = '$nome', alunni.cognome = '$cognome' where alunni.id = $id");
      $response->getBody()->write("Alunno aggiornato con successo");
      return $response->withHeader("Content-type", "application/json")->withStatus(200);

    }

  }

  public function delete(Request $request, Response $response, $args){

    $id = intval($args["id"]);

    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $result = $mysqli_connection->query("Delete FROM alunni WHERE alunni.id = $id");

    $response->getBody()->write("Alunno eliminato con successo");
    return $response->withHeader("Content-type", "application/json")->withStatus(200);

  }

}
