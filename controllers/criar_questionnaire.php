<?php

$_SESSION['user_login'] = 'Bob321';

require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $name = $_POST['name'];
    $description = $_POST['description'];
    $topic = $_POST['topic'];
    $questions = $_POST['questions'];

    $questionnaire = new Questionnaire($name, $description, $topic, $questions);
    $user = User::getUserByLogin($conn, $_SESSION['user_login']);
    $id = $user['user']['id'];
    $questionnaire = $questionnaire->create($conn, '1');
    if($questionnaire['status'] === 'success') {
    $questionnaire_id = $questionnaire['id'];
    } else {
        echo json_encode($questionnaire);
    }
    
    // adicionando as perguntas
    $sql = "INSERT INTO conn_question (`id_questionnaire`, `id_quest`) VALUES (:questionnaire, :question)";
    $stmt = $conn->prepare($sql);
    foreach ($questions as $question) {
        $stmt->bindParam(':questionnaire', $questionnaire_id);
        $stmt->bindParam(':question', $question);
        $stmt->execute();
    }

    echo json_encode($questionnaire);
}