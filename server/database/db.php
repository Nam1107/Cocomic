<?php
require('connect.php');
$keyadmin = 'lkjhnm';
$keyuser = 'dsdvef';
 function dd($value){
    echo json_encode($value);
}

function executeQuerry($sql,$conditions=[]){
    global $conn;
    $stmt = $conn->prepare($sql);
    $value = array_values($conditions);
    $stmt -> execute($value);
    return $stmt;
}
 function selectAll($table,$conditions = [],$order=""){
    global $conn;
    $sql = "SELECT * FROM $table";
    if(empty($conditions)){
        $sql = $sql . $order;
        $stmt = $conn->prepare($sql);
        $stmt -> execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    else{
        $i=0;
        foreach($conditions as $key => $value){
            if($i===0){
                $sql = $sql . " WHERE $key=?";
            }else{
                $sql = $sql . " AND $key=?";
            }
            $i++;
        }
        $sql = $sql . $order;
        $stmt = executeQuerry($sql,$conditions);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    
 }
 function selectOne($table,$conditions){
    $sql = "SELECT TOP 1 * FROM $table";
    $i=0;
        foreach($conditions as $key => $value){
            if($i===0){
                $sql = $sql . " WHERE $key=?";
            }else{
                $sql = $sql . " AND $key=?";
            }
            $i++;
        }
    $stmt = executeQuerry($sql,$conditions);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$result) {
        return null;
    } else {
        return $result[0];
    }
    
 }
function create($table,$conditions){
    global $conn;
    $col = "(";
    $val = "(";
    $i = 0;
    foreach ($conditions as $column => $value){
        
        if($i===0){
            $col = $col . " $column";
            $val = $val . " ?";
        }else{
            $col = $col  . " ,$column";
            $val = $val . " ,?";
        }
        $i++;
    }
    $col = $col . " )";
    $val = $val . " )";
    $sql = "INSERT INTO $table $col VALUES $val ";
    $stmt = executeQuerry($sql,$conditions);
    $last_id = $conn->lastInsertId();
    return $last_id;
} 
function update($table,$where,$conditions){
    $sql = "UPDATE $table SET ";
    $i=0;
        foreach($conditions as $key => $val){
            if($i===0){
                $sql = $sql . " $key=?";
            }else{
                $sql = $sql . ", $key=?";
            }
            $i++;
        }

        foreach($where as $key => $val){
            $sql = $sql . " WHERE $key = ?";
        }
    $conditions = $conditions + $where;
    $stmt = executeQuerry($sql,$conditions);
 }

 function delete($table,$conditions){
    $sql = "DELETE FROM $table";
    $i=0;
        foreach($conditions as $key => $value){
            if($i===0){
                $sql = $sql . " WHERE $key=?";
            }else{
                $sql = $sql . " AND $key=?";
            }
            $i++;
        }
    $stmt = executeQuerry($sql,$conditions);
    return $stmt -> rowCount();
 }
 function custom($sql){
    global $conn;
    $stmt = $conn->prepare($sql);
        $stmt -> execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
 }
// $conditions = [
//     'Username' => 'H???i T???c',
//     'Password' => '123456',
//     'FullName' => 'Truong Minh Huy',
//     'Email' => 'Huy3@gmail.com',
//     'IsAdministrator' => '1',

// ];

// $user = selectAll('[User]',$conditions);
// $test = selectOne('[Chapter_Page]',['PageID'=>1203]);
// dd($test);
// $user = create('[User]',$conditions);
// $user = update('[User]','UserID',1054,$conditions);
// $user = delete('[User]',1004);
// dd($user);
// $test = custom("SELECT * FROM [Comic]
// WHERE [AnotherName] LIKE '%pie%'");
// dd($test);