<?php

require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = $_POST['question'];
    $options = $_POST['options'];
    $correct = $_POST['correct'];

    $question = new Question($question, $options, $correct);
    $question = $question->add($conn);
    
    echo json_encode($question);
}