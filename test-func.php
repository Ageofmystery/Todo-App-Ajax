<?php
//load .json file for use
function loadTasks() {
    $arrayJson = file_get_contents('tasks.json');
    return json_decode($arrayJson, true);
}

//code and save data in .json
function saveTasks($data) {
    $arrayJson = json_encode(array_values($data));
    file_put_contents('tasks.json', $arrayJson);
}

//put concrete id from array, return new id no conflict
function newId($arrayTaskList) {
    $ids = array_map(function ($task) {
        return $task['id'];
    }, $arrayTaskList);
    return empty($ids) ? 0 : max($ids) + 1;
}

//get key of needent id
function findTodoById($arrayTaskList, $id) {
    foreach ($arrayTaskList as $key => $val) {
       if ($val['id'] == $id) {
           return $key;
       }
   }
}

//create new element and save
function createTodo() {
    $arrayTaskList = loadTasks();
    $id = newId($arrayTaskList);
    $name = isset($_POST["name"]) ? $_POST["name"] : null;
    $status = isset($_POST["status"]) ? $_POST["status"] : null;
    if (!$name || !$status) {
        exit('name and status are required');
    }

    $arrayTaskList[] = [
        "id" => $id,
        "task" => $name,
        "status" => $status
    ];
    saveTasks($arrayTaskList);
}

//find elem and replace data
function updateTodo() {
    parse_str(file_get_contents("php://input"), $data);
    $arrayTaskList = loadTasks();
    $id = isset($data["id"]) ? $data["id"] : null;
    $name = isset($data["name"]) ? $data["name"] : null;
    $status = isset($data["status"]) ? $data["status"] : null;
    if (is_null($id) || !$name || !$status) {
        exit('id, name and status are required');
    }

    $key = findTodoById($arrayTaskList, $id);
    $arrayTaskList[$key] = [
        "id" => $id,
        "task" => $name,
        "status" => $status
    ];
    saveTasks($arrayTaskList);
}

//unset array by task id
function deleteTodo() {
    $arrayTaskList = loadTasks();
    parse_str(file_get_contents("php://input"), $data);
    $id = isset($data['id']) ? $data['id'] : null;
    $key = findTodoById($arrayTaskList, $id);
    unset($arrayTaskList[$key]);
    saveTasks($arrayTaskList);
}

//get response method and run need func
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        createTodo();
        break;
    case 'PUT':
        updateTodo();
        break;
    case 'DELETE':
        deleteTodo();
        break;
    default:
        echo 'Method not implemented.';
}