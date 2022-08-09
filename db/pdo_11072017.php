<?php 

include "CatalogVisit.php";
include "PageVisit.php";
include "Customer.php";

class BIRepository
{	
	private $db_config = array(
		"DatabaseName"=>	"furniturenews", 
		"Host"=>			"localhost",
		"UserName"=>		"rootlive",
		"Password"=>		"root1234"
	);
	
	private $connection;
    public function __construct(PDO $connection = null)
    {
        $this->connection = $connection;
		
        if ($this->connection === null) {
            $this->connection = new PDO(
                    'mysql:host='.$this->db_config['Host']. ';dbname='. $this->db_config['DatabaseName'], 
                    $this->db_config['UserName'], 
                    $this->db_config['Password']
                );
				
            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE, 
                PDO::ERRMODE_EXCEPTION
            );
        }
    }
	
	public function switchDatabase($db_name)
	{
		//echo "Database name: " . $db_name;
		$this->connection = null;
		$this->connection = new PDO(
                    'mysql:host='.$this->db_config['Host']. ';dbname='.$db_name, 
                    $this->db_config['UserName'], 
                    $this->db_config['Password']
                );
		
		//echo 'mysql:host='.$this->db_config['Host']. ';dbname='.$db_name;
		
		$this->connection->setAttribute(
                PDO::ATTR_ERRMODE, 
                PDO::ERRMODE_EXCEPTION
            );
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
	
	public function getTotalCatalogVisit($dbName)
	{
		if(strlen($dbName) > 0)
		{
			$this->connection->exec('USE '.$dbName);
		}

		$nRows = $this->connection->query('select count(*) from catalogvisit')->fetchColumn(); 
		return $nRows;
	}
	
	public function getTotalCatalogVisitPerIp($ipAddress)
	{
		$nRows = $this->connection->query('select count(*) from catalogvisit WHERE IPAddress = \'' . $ipAddress . '\'')->fetchColumn(); 
		return $nRows;
	}
	
	public function getTotalCatalogVisitPerCustomerId($customerId)
	{
		$nRows = $this->connection->query('select count(*) from catalogvisit WHERE CustomerId = \'' . $customerId . '\'')->fetchColumn(); 
		return $nRows;
	}
	
	public function getTotalPageVisits()
	{
		$nRows = $this->connection->query('select count(*) from pagevisit')->fetchColumn(); 
		return $nRows;
	}
	
	public function getTotalPageVisitsPerIP($ipAddress, $visitId)
	{
		//echo 'select count(*) from catalogvisit WHERE IPAddress = ' . $ipAddress;
		$nRows = $this->connection->query('select count(*) from catalogvisit WHERE IPAddress = \'' . $ipAddress . '\' AND Id =' . $visitId)->fetchColumn(); 
		return $nRows; 
	}
	
	public function getTotalPageVisitsPerCustomerId($customerId, $visitId)
	{
		//echo 'select count(*) from catalogvisit WHERE CustomerId = ' . $customerId;
		$nRows = $this->connection->query('select count(*) from catalogvisit WHERE CustomerId = \'' . $customerId . '\' AND Id =' . $visitId)->fetchColumn(); 
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
			 ORDER BY VisitCount DESC, PageNumber ASC  
			 LIMIT ' . $numberOfMostVisitedPagesToShow
        );
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        
        // fetchAll() will do the same as above, but we'll have an array. ie:
        //    $users = $repository->findAll();
        //    echo $users[0]->firstname;
        return $stmt->fetchAll();
    }
	
	public function getMostVisitPagesPerIP($numberOfMostVisitedPagesToShow, $ipAddress, $visitId)
    {
        $stmt = $this->connection->prepare(
			'SELECT PageNumber, 
				COUNT(*) AS VisitCount 
			 FROM pagevisit pv
			 LEFT JOIN catalogvisit cv on cv.Id = pv.CatVisitId
			 WHERE cv.IPAddress = \'' . $ipAddress . '\' AND cv.Id = ' . $visitId .'
			 GROUP BY PageNumber 
			 ORDER BY VisitCount DESC, PageNumber ASC  
			 LIMIT ' . $numberOfMostVisitedPagesToShow
        );
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        
        // fetchAll() will do the same as above, but we'll have an array. ie:
        //    $users = $repository->findAll();
        //    echo $users[0]->firstname;
        return $stmt->fetchAll();
    }
	
	public function getMostVisitPagesPerCustomerId($numberOfMostVisitedPagesToShow, $customerId, $visitId)
    {
        $stmt = $this->connection->prepare(
			'SELECT PageNumber, 
				COUNT(*) AS VisitCount 
			 FROM pagevisit pv
			 LEFT JOIN catalogvisit cv on cv.Id = pv.CatVisitId
			 WHERE cv.CustomerId = \'' . $customerId . '\' AND cv.Id = ' . $visitId .'
			 GROUP BY PageNumber 
			 ORDER BY VisitCount DESC, PageNumber ASC  
			 LIMIT ' . $numberOfMostVisitedPagesToShow
        );
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        
        // fetchAll() will do the same as above, but we'll have an array. ie:
        //    $users = $repository->findAll();
        //    echo $users[0]->firstname;
        return $stmt->fetchAll();
    }
	
	// Created: 3/23/2017
	// Updated: 6/13/2017
	// Get the list of all visits grouped by page #
	// $pageNum, $pageSize, $searchCol, $searchVal
	public function getCatalogVisitsSummary($pageNum, $pageSize, $sortCriteria, $searchVal)
    {
		$sortOrder = strlen($sortCriteria) > 0 
				? $sortCriteria 
				: ' VisitDateTime DESC,
			        PageNumber ASC, 
			        VisitCount DESC ';
					
		$whereClause = strlen($searchVal) > 0 
				? " WHERE cv.IpAddress LIKE '%". $searchVal . "%' " .
				        "OR pv.PageNumber LIKE '%". $searchVal . "%' "
				: ' ';
		
		$query = 'SELECT   
			    DATE_FORMAT(pv.VisitDateTime, "%m/%d/%Y") AS VisitDateTime, 
			    cv.IpAddress,
                pv.PageNumber, 				
				COUNT(pv.PageNumber) AS VisitCount,
				cv.Domain,
				cv.Id,
				cv.CustomerId
			 FROM 
				catalogvisit cv
             LEFT JOIN pagevisit pv on cv.Id = pv.CatVisitId ' 
			 . $whereClause .
			 ' GROUP BY 
				cv.IpAddress, 
				pv.CatVisitId, 
				VisitDateTime
			 ORDER BY ' 
			 . $sortOrder . ' 
			 LIMIT ' . ($pageNum > 0 ? $pageNum : 0) . ',' . ($pageSize > 0 ? $pageSize : 0);
		
        $stmt = $this->connection->prepare($query);
		
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        
        return $stmt->fetchAll();
    }
	
	// 5/24/2017
	// Count the list of all visits grouped by page #
	// $pageNum, $pageSize, $searchCol, $searchVal
	public function getAllCatalogVisitsSummary($pageNum, $pageSize, $sortCriteria, $searchVal)
    {
		$sortOrder = strlen($sortCriteria) > 0 
				? $sortCriteria 
				: ' VisitDateTime DESC,
			        PageNumber ASC, 
			        VisitCount DESC ';
					
		$whereClause = strlen($searchVal) > 0 
				? " WHERE cv.IpAddress LIKE '%". $searchVal . "%' " .
				        "OR pv.PageNumber LIKE '%". $searchVal . "%' "
				: ' ';
		
		$query = 'SELECT  
			    DATE_FORMAT(pv.VisitDateTime, "%m/%d/%Y") AS VisitDateTime, 
			    cv.IpAddress,
                pv.PageNumber, 				
				COUNT(pv.PageNumber) AS VisitCount,
				cv.Domain,
				cv.Id,
				cv.CustomerId
			 FROM 
				catalogvisit cv
             LEFT JOIN pagevisit pv on cv.Id = pv.CatVisitId ' 
			 . $whereClause .
			 ' GROUP BY 
				cv.IpAddress, 
				pv.CatVisitId,
				VisitDateTime
			 ORDER BY ' 
			 . $sortOrder;
		
        $stmt = $this->connection->prepare($query);
		
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        
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
                (IPAddress, VisitDateTime, CustomerId) 
            VALUES 
                (:IPAddress, :VisitDateTime, :CustomerId)
        ');
        $stmt->bindParam(':IPAddress', $catVisit->IPAddress);
        $stmt->bindParam(':VisitDateTime', $catVisit->VisitDateTime);
		$stmt->bindParam(':CustomerId', $catVisit->CustomerId == '' ? 0 : $catVisit->CustomerId);
		
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
	
	public function deleteAllVisits()
	{
        $stmt = $this->connection->prepare('DELETE FROM pagevisit');
		$stmt->execute();
		$stmt = $this->connection->prepare('DELETE FROM catalogvisit');
		return $stmt->execute();
	}
	
	public function isValidCustomerId($id)
	{
       $nRows = $this->connection->query('select count(*) from customer where Id=' . $id)->fetchColumn(); 
	   return count($nRows) > 0;
	}
}