<?php

class Questionnaire {
    public $name;
    public $description;
    public $topic;
    public $questions = array();

    public function __construct(string $name, string $description, string $topic, $questions = []) {
        $this->name = $name;
        $this->description = $description;
        $this->topic = $topic;
        $this->questions = $questions;
    }

    public function create($conn, $id) {
        try {
        $sql = "INSERT INTO questionnaire (`name`, `description`, id_topic, id_user_author) VALUES (:nome, :descrip, :topic, :id_user)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $this->name);
        $stmt->bindParam(':descrip', $this->description);
        $stmt->bindParam(':topic', $this->topic);
        $stmt->bindParam(':id_user', $id);
        $stmt->execute();

        $lastid = $conn->lastInsertId();
        return ['status' => 'success', 'message' => 'Questionário criado com sucesso', 'id' => $lastid];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Erro ao criar questionário', 'error' => $e->getMessage()];
        }
    }

    public function update($conn, $id) {
        $sql = "UPDATE questionnaire SET `name` = :nome, `description` = :descrip, topic = :topic WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $this->name);
        $stmt->bindParam(':descrip', $this->description);
        $stmt->bindParam(':topic', $this->topic);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return ['status' => 'success', 'message' => 'Questionário atualizado com sucesso'];
    }

    public function delete($conn, $id) {
        $sql = "DELETE FROM questionnaire WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return ['status' => 'success', 'message' => 'Questionário excluído com sucesso'];
    }

    public function insertQuestions($conn, $id) {
        $sql = "INSERT INTO questionnaire_question (questionnaire, question) VALUES (:questionnaire, :question)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':questionnaire', $id);
        foreach ($this->questions as $question) {
            $stmt->bindParam(':question', $question);
            $stmt->execute();
        }
    }
    public static function getQuestionnaireById($conn, $id) {
        $sql = "SELECT * FROM questionnaire WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $questionnaire = $stmt->fetch(PDO::FETCH_ASSOC);
        return $questionnaire;
    }

}