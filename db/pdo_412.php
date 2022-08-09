<?php 
// $db_config = array(

	// "DatabaseName"=>	"furniturenews", 
	// "Host"=>			"localhost",
	// "UserName"=>		"root",
	// "Password"=>		"root"
	
// );

//$db_name = "furniturenews";
//include "db.php";
include "CatalogVisit.php";
include "PageVisit.php";

class BIRepository
{	
	private $db_config = array(
		"DatabaseName"=>	"furniturenews", 
		"Host"=>			"localhost",
		"UserName"=>		"rootlive",
		"Password"=>		"root1234"
	);
	
	// $database = new Database();
	// $db_config = $database->db_config;
	
	private $connection;
    public function __construct(PDO $connection = null)
    {
        $this->connection = $connection;
		//echo "DB object" . $this->db_config['DatabaseName'];
		//echo "database name:" . $this->db_name;
		
        if ($this->connection === null) {
            $this->connection = new PDO(
                    'mysql:host='.$this->db_config['Host']. ';dbname='.$this->db_config['DatabaseName'], 
                    $this->db_config['UserName'], 
                    $this->db_config['Password']
                );
				
            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE, 
                PDO::ERRMODE_EXCEPTION
            );
        }
    }

    public function getAllCatalogVisits()
    {
        $stmt = $this->connection->prepare('
            SELECT "Id", "IPAddress", "Domain", "VisitDateTime" 
             FROM catalogvisit
        ');
        //$stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Set the fetchmode to populate an instance of 'User'
        // This enables us to use the following:
        //     $user = $repository->find(1234);
        //     echo $user->firstname;
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'catalogvisit');
        return $stmt->fetch();
    }
	
	public function getTotalCatalogVisit()
	{
		$nRows = $this->connection->query('select count(*) from catalogvisit')->fetchColumn(); 
		return $nRows;
	}
	
	public function getTotalPageVisits()
	{
		$nRows = $this->connection->query('select count(*) from pagevisit')->fetchColumn(); 
		return $nRows;
	}
	
	public function getTotalPageVisitsForCatVisit($catVisitId)
	{
		$nRows = $this->connection->query('select count(*) from pagevisit where CatVisitId=' . $catVisitId)->fetchColumn(); 
		return $nRows;
	}

    public function findAll()
    {
        $stmt = $this->connection->prepare('
            SELECT * FROM users
        ');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        
        // fetchAll() will do the same as above, but we'll have an array. ie:
        //    $users = $repository->findAll();
        //    echo $users[0]->firstname;
        return $stmt->fetchAll();
    }
	
	 public function getMostVisitPages($numberOfMostVisitedPagesToShow)
    {
        $stmt = $this->connection->prepare(
			'SELECT PageNumber, 
				COUNT(*) AS VisitCount 
			 FROM pagevisit 
			 GROUP BY PageNumber 
			 ORDER BY PageNumber ASC, 
			          VisitCount DESC  
			 LIMIT ' . $numberOfMostVisitedPagesToShow
        );
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        
        // fetchAll() will do the same as above, but we'll have an array. ie:
        //    $users = $repository->findAll();
        //    echo $users[0]->firstname;
        return $stmt->fetchAll();
    }
	
	// 3/23/2017
	// Get the list of all visits grouped by page #
	//, $searchCol, $searchVal
	public function getCatalogVisitsSummary($pageNum, $pageSize)
    {
        $stmt = $this->connection->prepare(
			'SELECT 
			    DATE_FORMAT(pv.VisitDateTime, "%m/%d/%Y") AS VisitDateTime, 
			    cv.IpAddress,
                pv.PageNumber, 				
				COUNT(pv.PageNumber) AS VisitCount,
				cv.Domain
			 FROM 
				catalogvisit cv
             LEFT JOIN pagevisit pv on cv.Id = pv.CatVisitId
			 GROUP BY 
				cv.IpAddress, 
				pv.PageNumber, 
				VisitDateTime
			 ORDER BY 
				VisitDateTime DESC,
			    PageNumber ASC, 
			    VisitCount DESC   
			 LIMIT ' . ($pageNum > 0 ? $pageNum : 0) . ',' . ($pageSize > 0 ? $pageSize : 0)
			 
        );
		
		//WHERE cv.IPAddress like '%160.153.153.3%'
		
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        
        // fetchAll() will do the same as above, but we'll have an array. ie:
        //    $users = $repository->findAll();
        //    echo $users[0]->firstname;
        return $stmt->fetchAll();
    }
	
	public function insertCatalogVisit(CatalogVisit $catVisit)
	{
		// If the ID is set, we're updating an existing record
        if (isset($catVisit->id)) {
            return $this->update($catVisit);
        }

        $stmt = $this->connection->prepare('
            INSERT INTO catalogvisit 
                (IPAddress, VisitDateTime) 
            VALUES 
                (:IPAddress, :VisitDateTime)
        ');
        $stmt->bindParam(':IPAddress', $catVisit->IPAddress);
        $stmt->bindParam(':VisitDateTime', $catVisit->VisitDateTime);
		
        $stmt->execute();
		
		return $this->connection->lastInsertId();
	}
	
	public function insertPageVisit(PageVisit $pageVisit)
	{
		// If the ID is set, we're updating an existing record
        if (isset($pageVisit->id)) {
            return $this->update($pageVisit);
        }

        $stmt = $this->connection->prepare('
            INSERT INTO pagevisit 
                (PageNumber, VisitDateTime, CatVisitId) 
            VALUES 
                (:PageNumber, :VisitDateTime, :CatVisitId)
        ');
        $stmt->bindParam(':PageNumber', $pageVisit->PageNumber);
        $stmt->bindParam(':VisitDateTime', $pageVisit->VisitDateTime);
		$stmt->bindParam(':CatVisitId', $pageVisit->CatVisitId);
		
        return $stmt->execute();
	}
}