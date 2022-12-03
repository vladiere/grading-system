<?php
session_start();
require("backend.php");
if (isset($_POST['choice'])) {
    switch ($_POST['choice']) {
        case 'login':
            $backend = new backend();
            echo $backend->doLogin($_POST['username'],$_POST['password']);
            break;
        case 'register':
            $backend = new backend();
            echo $backend->doRegister($_POST['firstname'],$_POST['lastname'],$_POST['user'],$_POST['pass']);
            break;
        case 'add':
            $backend = new backend();
            echo $backend->doAddStudents($_POST['subjects'],$_POST['midterms'],$_POST['finals']);
            break;
        case 'addadmin':
            $backend = new backend();
            echo $backend->doAddStudentsAdmin($_POST["studentid"],$_POST['midterms'],$_POST['finals']);
             break;  
        case 'update':
            $backend = new backend();
            echo $backend->doUpdateGrades($_POST["subject"],$_POST['studentID'],$_POST['midterms'],$_POST['finals']);
            break;
        case 'view':
            $backend = new backend();
            echo $backend->doViewUser();
            break;
        case 'viewadmin':
            $backend = new backend();
            echo $backend->doViewAdmin();
            break;
        case 'viewall':
            $backend = new backend();
            echo $backend->viewAllStudents();
            break;
        case 'dropstud':
            $backend = new backend();
            echo $backend->doDropstud($_POST["id"], $_POST["subj"]);
            break;
        case 'logout':
            session_unset();
            session_destroy();
            echo "200";
            break;
        default:
            echo "404";
            break;
    }
}