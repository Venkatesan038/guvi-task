<?php 

if(isset($_POST['fname']) &&
   isset($_POST['dob'])&&
   isset($_POST['age'])&&
   isset($_POST['cno'])&&
   isset($_POST['uname']) &&  
   isset($_POST['pass'])){

    include "../db_conn.php";

    $fname = $_POST['fname'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $cno = $_POST['cno'];
    $uname = $_POST['uname'];
    $pass = $_POST['pass'];

    $data = "fname=".$fname."&dob=".$dob."&age=".$age."&cno=".$cno."&uname=".$uname;
    
    if (empty($fname)) {
    	$em = "Full name is required";
    	header("Location: ../index.php?error=$em&$data");
	    exit;
      }else if(empty($dob)){
         $em = "Date of birth is required";
         header("Location: ../index.php?error=$em&$data");
         exit;
      }else if(empty($age)){
         $em = "Age is required";
         header("Location: ../index.php?error=$em&$data");
         exit;
      }else if(empty($cno)){
         $em = "Cotact No is required";
         header("Location: ../index.php?error=$em&$data");
         exit;   
    }else if(empty($uname)){
    	$em = "User name is required";
    	header("Location: ../index.php?error=$em&$data");
	    exit;
    }else if(empty($pass)){
    	$em = "Password is required";
    	header("Location: ../index.php?error=$em&$data");
	    exit;
    }else {
      // hashing the password
      $pass = password_hash($pass, PASSWORD_DEFAULT);

      if (isset($_FILES['pp']['name']) AND !empty($_FILES['pp']['name'])) {
         
         
         $img_name = $_FILES['pp']['name'];
         $tmp_name = $_FILES['pp']['tmp_name'];
         $error = $_FILES['pp']['error'];
         
         if($error === 0){
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_to_lc = strtolower($img_ex);

            $allowed_exs = array('jpg', 'jpeg', 'png');
            if(in_array($img_ex_to_lc, $allowed_exs)){
               $new_img_name = uniqid($uname, true).'.'.$img_ex_to_lc;
               $img_upload_path = '../upload/'.$new_img_name;
               move_uploaded_file($tmp_name, $img_upload_path);

               // Insert into Database
               $stmt = $conn->prepare("INSERT INTO users(fname, dob, age, cno, username, password, pp) 
               VALUES(?,?,?,?,?,?,?)");
               $stmt->execute([$fname, $dob, $age, $cno, $uname, $pass, $new_img_name]);
               header("Location: ../index.php?success=Your account has been created successfully");
                exit;
            }else {
               $em = "You can't upload files of this type";
               header("Location: ../index.php?error=$em&$data");
               exit;
            }
         }else {
            $em = "unknown error occurred!";
            header("Location: ../index.php?error=$em&$data");
            exit;
         }

        
      }else {
       	$stmt = $conn->prepare("INSERT INTO users(fname, dob, age, cno, username, password) 
          VALUES(?,?,?,?,?,?)");
       	$stmt->execute([$fname, $dob, $age, $cno, $uname, $pass]);

       	header("Location: ../index.php?success=Your account has been created successfully");
   	    exit;
      }
    }


}else {
	header("Location: ../index.php?error=error");
	exit;
}
