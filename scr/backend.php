<?php
require("database.php");
class backend
{
    public function doLogin($user,$pass){
        return self::login($user,$pass);
    }
    public function doRegister($firstname,$lastname,$user,$pass){
        return self::register($firstname,$lastname,$user,$pass);
    }
    public function doAddStudents($subjects,$midterms,$finals){
        return self::addStudents($subjects,$midterms,$finals);
    }
    public function doAddStudentsAdmin($studentid,$midterms,$finals){
        return self::addStudentsAdmin($studentid,$midterms,$finals);
    }
    public function doUpdateGrades($subject,$studentid,$midterms,$finals){
        return self::updateGrades($subject,$studentid,$midterms,$finals);
    }
    public function doViewUser(){
        return self::getUser();
    }
    public function doViewAdmin(){
        return self::getStudents();
    }
    public function doDropStud($id,$subj)
    {
        return self::dropStud($id,$subj);
    }
    public function viewAllStudents()
    {
        return self::allStudents();
    }
    private function allStudents()
    {
        try {
            if ($this->checkIfValid($_SESSION["username"], $_SESSION["password"])) {
                $db = new database();
                if ($db->getStatus()) {
                    $stmt = $db->getCon()->prepare($this->getAllStudentsQuery());
                    $stmt->execute();
                    $res = $stmt->fetchAll();

                    $db->closeConnection();
                    return json_encode($res);
                } else {
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $e) {
            return $e;
        }
    }
    private function dropStud($id,$subj)
    {
        try {
            if ($id != '') {
                if ($this->checkIfValid($_SESSION["username"], $_SESSION["password"])) {
                    $db = new database();
                    if ($db->getStatus()) {
                        $stmt = $db->getCon()->prepare($this->dropStudQuery());
                        $stmt->execute(array($id, $subj, $id));
                        $res = $stmt->fetch();
                        if (!$res) {
                            $db->closeConnection();
                            return "200";
                        } else {
                            $db->closeConnection();
                            return "403";
                        }
                    } else {
                        return "403";
                    }
                } else {
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $e) {
            return $e;
        }
    }
    private function login($user,$pass)
    {
            try {
                if ($this->checkIfValid($user,$pass)){
                    $db = new database();
                    if ($this->getAdminUser($user)) {
                        if ($db->getStatus()) {
                            $stmt = $db->getCon()->prepare($this->adminLoginQuery());
                            $stmt->execute(array($user,md5($pass)));
                            $result = $stmt->fetch();
                            if ($result) {
                                $_SESSION['username'] = $user;
                                $_SESSION['password'] = md5($pass);
                                $db->closeConnection();
                                return "admin";
                            }else{
                                $db->closeConnection();
                                return $this->getAdminUser($user);
                            }
                        } else {
                            return "403";
                        }
                    } else {
                        if ($db->getStatus()) {
                            $tmp = md5($pass);
                            $stmt = $db->getCon()->prepare($this->loginQuery());
                            $stmt->execute(array($user,$tmp));
                            $result = $stmt->fetch();
                            if ($result) {
                                $_SESSION['username'] = $user;
                                $_SESSION['password'] = $tmp;
                                self::addStudents($result['ID']);
                                $db->closeConnection();
                                return "200";
                            }else{
                                $db->closeConnection();
                                return $this->getAdminUser($user);
                            }
                        }else{
                            return "403";
                        }
                    }
            }else{
                return "403";
            }
        } catch (PDOException $th){
            return $th;
        }
    }

    private function register($firstname,$lastname,$user,$pass){
        try {
            if ($this->checkIfValid($firstname,$lastname,$user,$pass)) {
                $db = new database();
                if ($firstname == 'admin') {
                    if ($db->getStatus()) {
                        $stmt = $db->getCon()->prepare($this->adminRegisterQuery());
                        $stmt->execute(array($lastname,$user,md5($pass), $this->getCurrentDate()));
                        $res = $stmt->fetch();
                        if (!$res) {
                            $db->closeConnection();
                            return "200";
                        } else {
                            $db->closeConnection();
                            return "404";
                        }
                    } else {
                        return "403";
                    }
                } else {
                    if ($db->getStatus()) {
                        $stmt = $db->getCon()->prepare($this->registerQuery());
                        $stmt->execute(array($firstname,$lastname,$user,md5($pass),$this->getCurrentDate()));
                        $result = $stmt->fetch();
                        if (!$result) {
                            $db->closeConnection();
                            return "200";
                        }else{
                            $db->closeConnection();
                            return "404";
                        }
                    }else{
                        return "403";
                    }
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return $th;
        }
    }
    private function addStudents($id){

        try {
            $db = new database();
            if ($db->getStatus()) {
                if (self::getOneStudent($id)) {
                    return "200";
                } else {
                    $stmt = $db->getCon()->prepare($this->addStudentsQuery());
                    $stmt->execute(array($id,$this->getCurrentDate()));
                    $result = $stmt->fetch();
                    if (!$result) {
                        $db->closeConnection();
                        return "200";
                    }else{
                        $db->closeConnection();
                        return "404";
                    }
                }
            }else{
                return "403";
            }
        } catch (PDOException $th) {
            return $th;
        }
    }

    private function getOneStudent($id)
    {
        try {
            $db = new database();
            if ($db->getStatus()) {
                $stmt = $db->getCon()->prepare($this->getStudentQuery());
                $stmt->execute(array($id));
                $res = $stmt->fetch();
                if (!$res) {
                    $db->closeConnection();
                    return json_encode($res);
                } else {
                    $db->closeConnection();
                    return json_encode($res);
                }
            } else {
                return "403";
            }
        } catch (PDOException $e) {
            return $e;
        }
    }

    private function addStudentsAdmin($studentid,$midterms,$finals){
        try {
            if ($this->checkIfValid($_SESSION["username"], $_SESSION["password"])) {
                if ($this->getOneStudent($studentid) == true) {
                    $db = new database();
                    if ($db->getStatus()) {
                        $stmt = $db->getCon()->prepare($this->addStudentsAdminQuery());
                        $stmt->execute(array($studentid,$this->getAdminSubj(),$midterms,$finals, $this->getCurrentDate()));
                        $result = $stmt->fetch();
                        if (!$result) {
                            $db->closeConnection();
                            return "200";
                        }else{
                            $db->closeConnection();
                            return $this->getOneStudent($studentid);
                        }
                    }else{
                        return "403";
                    }
                } else {
                    return $this->getOneStudent($studentid);
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return $th;
        }
    }
    private function updateGrades($subjects,$studentid,$midterms,$finals){
        try {
            try {
                if ($this->checkIfValid($_SESSION["username"], $_SESSION["password"])) {
                    $db = new database();
                    if ($db->getStatus()) {
                        $stmt = $db->getCon()->prepare($this->updateGradesQuery());
                        $stmt->execute(array($subjects,$midterms,$finals,$studentid));
                        $result = $stmt->fetch();
                        if (!$result) {
                            $db->closeConnection();
                            return "200";
                        }else{
                            $db->closeConnection();
                            return "404";
                        }
                    }else{
                        return "403";
                    }
                } else {
                    return "403";
                }
            } catch (PDOException $th) {
                return $th;
            }
        } catch (PDOException $th) {
            return "501";
        }
    }
    private function getUser(){
        try {
            if ($this->checkIfValid($_SESSION['username'], $_SESSION['password'])) {
                $db = new database();
                if ($db->getStatus()) {
                    $stmt = $db->getCon()->prepare($this->getUserQuery());
                    $stmt->execute(array($this->getId()));
                    $result = $stmt->fetchAll();
                    $db->closeConnection();
                    return json_encode($result);
                }else{
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return "501";
        }
    }
    private function getId(){
        try {
            $db = new database();
            if ($db->getStatus()) {
                $stmt = $db->getCon()->prepare($this->loginQuery());
                $stmt->execute(array($_SESSION['username'],$_SESSION['password']));
                $tmp = null;
                while ($row = $stmt->fetch()) {
                    $tmp = $row['ID'];
                }
                $db->closeConnection();
                return $tmp;
            }
        } catch (PDOException $th) {
            echo $th;
        }        
    }
    private function getStudents(){
        try {
            if ($this->checkIfValid($_SESSION['username'], $_SESSION['password'])) {
                $db = new database();
                if ($db->getStatus()) {
                    $stmt = $db->getCon()->prepare($this->getStudentsQuery());
                    $stmt->execute(array($this->getAdminSubj()));
                    $result = $stmt->fetchAll();
                    $db->closeConnection();
                    return json_encode($result);
                }else{
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return "501";
        }
    }
    private function getAdminSubj(){
        try {
            $db = new database();
            if ($db->getStatus()) {
                $stmt = $db->getCon()->prepare($this->adminLoginQuery());
                $stmt->execute(array($_SESSION['username'],$_SESSION['password']));
                $tmp = null;
                while ($row = $stmt->fetch()) {
                    $tmp = $row['subject'];
                }
                $db->closeConnection();
                return $tmp;
            }
        } catch (PDOException $th) {
            echo $th;
        }        
    }
    private function getCurrentDate(){
        return date("Y/m/d");
    }
    private function getAdminUser($user)
    {
        try {
            if ($user != '') {
                $db = new database();
                if ($db->getStatus()) {
                    $stmt = $db->getCon()->prepare($this->getAdminUserQuery());
                    $stmt->execute(array($user));
                    $res = $stmt->fetch();
                    if ($res) {
                        $db->closeConnection();
                        return true;
                    } else {
                        $db->closeConnection();
                        return false;
                    }
                } else {
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $e) {
            return $e;
        }
    }
    private function checkIfValid($user, $pass)
    {
        if ($user != "" && $pass != "")
            return true;
        else
            return false;
    }
    private function loginQuery()
    {
        return "SELECT * FROM `people` WHERE `user` = ? AND `pass` = ?";
    }
    private function registerQuery()
    {
        return "INSERT INTO `people` (`firstname`,`lastname`,`user`,`pass`,`created`) VALUES (?,?,?,?,?)";
    }
    private function addStudentsQuery()
    {
        return "INSERT INTO `subject` (`user_id`,`created`) 
                VALUES (?,?)";  
    }
    private function addStudentsAdminQuery()
    {
        return "INSERT INTO `students` (`user_id`,`subjects`,`midterms`,`finals`, `created`) 
                VALUES (?,?,?,?,?)";  
    }
    private function adminLoginQuery()
    {
        return "SELECT * FROM `admin` WHERE `user` = ? AND `pass` = ?";
    }
    private function getUserQuery()
    {
        return "SELECT * FROM `people` JOIN `students` ON `students`.`user_id` = `people`.`ID` WHERE `students`.`user_id` = ?;";
    }
    private function getStudentQuery()
    {
        return "SELECT * FROM `people` WHERE `ID` = ?";
    }
    private function getStudentsQuery()
    {
        return "SELECT * FROM `people` JOIN `students` ON `students`.`user_id` = `people`.`ID` WHERE `students`.`subjects` = ?;";
    }
    private function getAllStudentsQuery()
    {
        return "SELECT * FROM `people`;";
    }
    private function updateGradesQuery()
    {
        return "UPDATE `students` SET `subjects` = ?, `midterms` = ?, `finals` = ? WHERE `user_id` = ?";
    }
    private function dropStudQuery()
    {
        return "DELETE FROM `students` WHERE `students`.`user_id` = ? AND `students`.`subjects` = ?; DELETE FROM `people` WHERE `people`.`ID` = ?";
    }
    private function adminRegisterQuery()
    {
        return "INSERT INTO `admin` (`subject`,`user`,`pass`,`created`) VALUES (?,?,?,?)";
    }
    private function getAdminUserQuery()
    {
        return "SELECT * FROM `admin` WHERE `subject` = ?";
    }
}
?>