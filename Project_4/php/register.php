<?php
require "db.php"; #requires the db.php file to be created within the same directory and used.

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST["newid"];
    $password = $_POST["createpassword"];

    $users = getUserStore();

    // Check duplicate user
<<<<<<< Updated upstream
    $existing = $users->where("student_id", "=", $id)->fetch();

    if ($existing) {
        echo "User already exists.";
        exit();
=======
    $existing = $users->findBy(["student_id", "=",  $id]);

    if ($existing) {
        echo("<script>
                alert(\"Please enter a unique Account ID\")
                window.location=\"../html/index.html\"
                </script>");  
    }
    else{
        $users->insert([
            "student_id" => $id,
            "password" => password_hash($password, PASSWORD_DEFAULT)
        ]);
        echo("<script>alert(\"Account Created!\")
                window.location=\"../html/user.html\"</script>");
        //header("Location: ../html/user.html");
>>>>>>> Stashed changes
    }

<<<<<<< Updated upstream
    $users->insert([
        "student_id" => $id,
        "password" => password_hash($password, PASSWORD_DEFAULT)
    ]);

    echo "Account created!";
}
=======
    

    }
>>>>>>> Stashed changes
?>