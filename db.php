<?php
	class db
	{
		function connect()
		{
			$username = 'undecided_php';
			$password = 'sp13cs411';
			try {
				$conn = new PDO('mysql:host=engr-cpanel-mysql.engr.illinois.edu;dbname=undecided_main', $username, $password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				#echo 'Connection is good!';
			} catch(PDOException $e) {
				echo 'ERROR: ' . $e->getMessage();
			}
			return $conn;
		}
		
		function queryWithCourses($courses)
		{
			// count how many non-NULL values there are
			$count = 0;
			$sql = "SELECT combo_id FROM Combinations WHERE";
			foreach($courses as $course)
			{
				if (!empty($course))
				{
					$sql.="course_name = '$course' OR";
					$count++;
				}
			}
			$conn = $this->connect();
			$sql = "SELECT combo_id FROM Combinations WHERE course_name = '$courses[0]' OR course_name = '$courses[1]' OR course_name = '$courses[2]' GROUP BY combo_id HAVING COUNT(course_name) <= $count";
			$ids = array();
			foreach($conn->query($sql) as $row)
				array_push($ids, $row[0]);
			return $ids;	
		}

                function addCombination($courses, $user_id)
                {
                	if($user_id == -1)
                	{
                		echo"You aren't logged in, idiot!";
                		return -1;
                	}
                	if($this->duplicateCourse($courses))
                	{
                		echo"Duplicate courses, idiot!";
                		return -1;
                	}
                	$conn = $this->connect();
                	foreach($conn->query("SELECT MAX(combo_id) FROM Combinations") as $max)
                		$combo_id = $max[0]+1;
                	foreach($courses as $course)
                	{
            			$sql = "INSERT INTO Combinations (combo_id, user_id, course_name)
            		Values ('$combo_id', $user_id, '$course')";
            			$conn->query($sql); 
                	}
                }
                
                function duplicateCourse($courses)
                {
                	$array = array_count_values($courses);
                	foreach($courses as $course)
                	{
				if($array[$course] !=1)
					return true;
                	}
                	return false;
                	
                }
                
                function login($username, $password)
                {
                	$conn = $this->connect();
			$sql = "SELECT user_id FROM Students WHERE username='$username' AND password='$password'";
			$count = 0;
			foreach($conn->query($sql) as $q)
			{
				$user_id = $q[0];
				$count+=1;
			}
			if($count==1)
				return $user_id;
			else if($count==0)
				return null;

                }
                
                function register($username, $password, $degree)
                {
                	echo "<br>";
                	$conn = $this->connect();
                	$sql = "SELECT user_id FROM Students WHERE username='$username'";
			foreach($conn->query($sql) as $q)
			{
				echo "That user name is already taken.";
				
				return -1;
			}   
                	foreach($conn->query("SELECT MAX(user_id) FROM Students") as $max)
                		$user_id = $max[0]+1;        
			$sql = "INSERT INTO Students (user_id, username, password, degree)
			VALUES ('$user_id', '$username', '$password', '$degree')";
			$conn->query($sql);
			echo "Registered!";    	
                }
                
                                
                function getReview($combo_id)
                {
                        $conn = $this->connect();
                        $sql = "SELECT review_id, text, user_id, combo_rating, helpful_rating, rating_count FROM Reviews WHERE combo_id='$combo_id'";
                        $array = array();
			foreach($conn->query($sql) as $row)
			{
				array_push($array, $row);
			}
			return $array;
			
		}
		
		function createReview($combo_id, $user_id, $text, $combo_rating)
		{
                        $conn = $this->connect();
                        $text = $this->stripQuotes($text);
                        foreach($conn->query("SELECT MAX(review_id) FROM Reviews") as $max)
                        	$review_id = $max[0]+1;
                        $sql = "INSERT INTO Reviews (review_id, combo_id, user_id, text, combo_rating, helpful_rating, rating_count) 
                        VALUES ('$review_id', '$combo_id', '$user_id', '$text', '$combo_rating', 0, 0)";
                        $conn->query($sql);			
		}
		
		function stripQuotes($text)
		{
			while(strpos($text, "'")!==false)
			{
				$i = strpos($text, "'");
				$str1 = substr($text, 0, $i);
				$str2 = substr($text, $i+1);
				$text = $str1.$str2;
			}
			return $text;
		}
		
		function deleteReview($review_id, $user_id)
		{
			$conn = $this->connect();
			foreach($conn->query("SELECT user_id FROM Reviews WHERE review_id='$review_id'") as $id);
			{
				if($user_id!=$id[0])
					return -1;
			}
			$sql = "DELETE FROM Reviews WHERE review_id='$review_id'";
			$conn->query($sql);
		}
		
		function updateReview($review_id, $rating, $user_id)
		{
			$conn = $this->connect();
			foreach($conn->query("SELECT user_id FROM Reviews WHERE review_id='$review_id'") as $id);
			{
				if($user_id==$id[0])
					return -1;
			}
			foreach($conn->query("SELECT helpful_rating, rating_count FROM Reviews WHERE review_id='$review_id'") as $row);	
			{
				$helpful_rating = $row['helpful_rating'] + $rating;
				$rating_count = $row['rating_count'] + 1;		
			}
			$sql = "UPDATE Reviews SET helpful_rating='$helpful_rating', rating_count='$rating_count' WHERE review_id='$review_id'";
			$conn->query($sql);
		}
		
		function getHelpfulRating($review_id)
		{
			$conn = $this->connect();
			$sql = "SELECT helpful_rating from Reviews WHERE review_id = '$review_id'";
			foreach($conn->query($sql) as $q)
			{
				return $q[0];
			}
			

		}
		
		function getRatingCount($review_id)
		{
			$conn = $this->connect();
			$sql = "SELECT rating_count from Reviews WHERE review_id = '$review_id'";
			foreach($conn->query($sql) as $q)
				return $q[0];

		}
		
		function getUsername($user_id)
		{
			$conn = $this->connect();
			$sql = "SELECT username FROM Students WHERE user_id='$user_id'";
			foreach($conn->query($sql) as $row)
				return $row[0];
	
		}
	}
?>