<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;
use PDOException;

class ClienteController extends Controller
{
    public function cadastro(Request $request){
        //requisição do forms
        $nome = $request->input('nome');
        $nascimento = $request->input('nascimento');
        $cpf = $request->input('cpf');
        $celular = $request->input('celular');
        $endereco = $request->input('endereco');
        $email = $request->input('email');
        $observacao = $request->input('observacao'); //pode ser nulo

        $pdo = ClienteController::connectPDO();

        //inserção no banco
        $stmt = $pdo->prepare("INSERT INTO cliente (nome, nascimento, cpf, celular, endereco, email, observacao) VALUES (:nome, :nascimento, :cpf, :celular, :endereco, :email, :observacao)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':nascimento', $nascimento);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':celular', $celular);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':observacao', $observacao);
        $stmt->execute();

        $pdo = null;

        return response()->json(['status' => 'success', 'message' => 'Dados inseridos com sucesso']);
    }

    public function getAllClientes(){
        ClienteController::resetId();
        $pdo = ClienteController::connectPDO();
        $clientes = $pdo->query('SELECT * FROM cliente')->fetchAll(PDO::FETCH_ASSOC);;
        $pdo = null;

        return response()->json($clientes);
    }

    public function deleteCliente($id){
        $pdo = ClienteController::connectPDO();
        $pdo->prepare("DELETE FROM cliente WHERE id=?")->execute([$id]);
        $pdo = null;

        return response()->json(['status' => 'success', 'message' => 'usuário correspondente ao id:'.$id.' deletado']);
    }

    public function pesquisarPorNomeOrEmail($dado){
        $pdo = ClienteController::connectPDO();
        $nome = $dado;
        if (empty($nome)) {
            return response()->json([]);
        }
        $nome = '%' . $nome . '%';
        $stmt = $pdo->prepare('SELECT * FROM cliente WHERE nome LIKE :nome OR email LIKE :nome');
        $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
        $stmt->execute();
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo = null;

        return response()->json($clientes);
    }

    public function getClienteId($id){
        $pdo = ClienteController::connectPDO();
        $cliente = $pdo->query('SELECT * FROM cliente WHERE id IN ('.$id.')')->fetchAll(PDO::FETCH_ASSOC);
        $pdo = null;

        return response()->json($cliente);
    }

    public function updateCliente(Request $request, $id){
        $nome = $request->input('nome');
        $nascimento = $request->input('nascimento');
        $cpf = $request->input('cpf');
        $celular = $request->input('celular');
        $endereco = $request->input('endereco');
        $email = $request->input('email');
        $observacao = $request->input('observacao');

        $pdo = ClienteController::connectPDO();
        $sql = "UPDATE cliente SET nome=:nome, email=:email, endereco=:endereco, cpf=:cpf, nascimento=:nascimento, celular=:celular, observacao=:observacao WHERE id=:id";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':nascimento', $nascimento);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':celular', $celular);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':observacao', $observacao);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $pdo = null;

        return response()->json(['status' => 'success', 'message' => 'Cliente atualizado']);
    }

    public function connectPDO(){
        try{
            //acesso ao banco
            $host = env('DB_HOST');
            $port = env('DB_PORT');
            $dbname = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');

            //start pdo
            $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        }catch(PDOException $e){
            echo 'ERROR: ' . $e->getMessage();
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }
    }
    public function resetId(){
        $pdo = ClienteController::connectPDO();
        $stmt = $pdo->prepare('ALTER TABLE cliente DROP COLUMN id;');
        $stmt->execute();
        $stmt = $pdo->prepare('ALTER TABLE cliente ADD id INT AUTO_INCREMENT PRIMARY KEY FIRST;');
        $stmt->execute();
    }
}
