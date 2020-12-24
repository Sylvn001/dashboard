<?php 


class Dashboard{

    public $data_inicio; 
    public $data_fim; 
    public $numeroVendas; 
    public $totalVendas;
    public $clientesAtivos;
    public $clientesInativos;
    public $reclamacoes; 
    public $elogios;
    public $sugestoes;
    public $despesas;
    
    public function __get($attr){
        return $this->$attr;
    }

    public function __set($attr, $value){
        $this->$attr = $value; 
        return $this;
    }

}

class Connection {
    private $host = 'localhost'; 
    private $dbname = 'dashboard';
    private $user = 'root';
    private $pass = '';

    public function connect(){
        try{
            $connection = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname", 
                "$this->user", 
                "$this->pass"
            );
            
            //

            $connection->exec('set charset set utf8');

            return $connection;

        } catch (PDOException $e) {
            echo "<p>".$e->getMessage()."</p>";
		}
    }
}


//classe model 

class BD{
    private $connection;
    private $dashboard; 
    
    public function __construct($connection, $dashboard){
        $this->connection = $connection->connect();
        $this->dashboard = $dashboard;
    }

    public function getNumeroVendas(){
        $query = "
            SELECT 
                count(*) as numero_vendas
            FROM 
                tb_vendas 
            WHERE data_venda BETWEEN :data_inicio and :data_fim "; 

        $stmt = $this->connection->prepare($query);

        $stmt->bindValue(':data_inicio' , $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim' , $this->dashboard->__get('data_fim'));
        
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
        
    }
    public function getTotalVendas(){
        $query = "
            SELECT 
                SUM(total) as total_vendas
            FROM 
                tb_vendas 
            WHERE data_venda BETWEEN :data_inicio and :data_fim "; 

        $stmt = $this->connection->prepare($query);

        $stmt->bindValue(':data_inicio' , $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim' , $this->dashboard->__get('data_fim'));
        
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
        
    }

    public function getClientesAtivos(){
        $query = "
        SELECT
            count(*) as total 
        FROM 
            tb_clientes 
        WHERE 
            cliente_ativo = ?"; 

        $stmt = $this->connection->prepare($query);

        $stmt->bindValue(1, 1);
        
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total;
    }

    public function getClientesInativos(){
        $query = "
        SELECT
            count(*)  as total
        FROM 
            tb_clientes 
        WHERE 
            cliente_ativo = ?";       

        $stmt = $this->connection->prepare($query);

        $stmt->bindValue(1, 0);
        
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total;
    }

    public function getReclamacoes(){
        $query = "
        SELECT
            count(*)  as total
        FROM 
            tb_contatos 
        WHERE 
            tipo_contato = ?"; 

        $stmt = $this->connection->prepare($query);

        $stmt->bindValue(1, 1);
        
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total;
    }

    public function getElogios(){
        $query = "
        SELECT
            count(*)  as total
        FROM 
            tb_contatos 
        WHERE 
            tipo_contato = ?"; 

        $stmt = $this->connection->prepare($query);

        $stmt->bindValue(1, 2);
        
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total;
    }

    public function getSugestoes(){
        $query = "
        SELECT
            count(*)  as total
        FROM 
            tb_contatos 
        WHERE 
            tipo_contato = ?"; 

        $stmt = $this->connection->prepare($query);

        $stmt->bindValue(1, 3);
        
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total;
    }

    public function getDespesas(){
        $query = "
            SELECT 
                SUM(total) as despesas
            FROM 
                tb_despesas 
            WHERE data_despesa BETWEEN :data_inicio and :data_fim "; 

        $stmt = $this->connection->prepare($query);

        $stmt->bindValue(':data_inicio' , $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim' , $this->dashboard->__get('data_fim'));
        
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->despesas;
        
    }
    
}

$dashboard = new Dashboard(); 
$connection = new Connection();

$competencia = explode('-', $_GET['competencia']);

$ano = $competencia[0];
$mes = $competencia[1];

$dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano); 

$dashboard->__set('data_inicio', $ano.'-'.$mes.'-01');
$dashboard->__set('data_fim',  $ano.'-'.$mes.'-'.$dias_do_mes);

$bd = new BD($connection, $dashboard);
$dashboard->__set('numeroVendas', $bd->getNumeroVendas());
$dashboard->__set('totalVendas', $bd->getTotalVendas());
$dashboard->__set('clientesAtivos', $bd->getClientesAtivos()); 
$dashboard->__set('clientesInativos', $bd->getClientesInativos()); 
$dashboard->__set('reclamacoes', $bd->getReclamacoes()); 
$dashboard->__set('elogios', $bd->getElogios()); 
$dashboard->__set('sugestoes', $bd->getSugestoes()); 
$dashboard->__set('despesas', $bd->getDespesas()); 

echo json_encode($dashboard);

