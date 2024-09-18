<?php

class Question {

    public $id;
    public $question;
    public $options;
    public $coption;

    public function __construct(string $question, $options, string $coption) {
        $this->question = $question;
        $this->options = $options;
        $this->coption = $coption;
    }

    public function add($conn) {

        try {
            $sql = "INSERT INTO questions (quest, options, coption) VALUES (:question, :options, :coption)";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':question', $this->question);
            $stmt->bindParam(':options', $this->options);
            $stmt->bindParam(':coption', $this->coption);
            $stmt->execute();

            return ['status' => 'success', 'message' => 'Pergunta adicionada com sucesso', 'id' => $conn->lastInsertId()];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Erro ao adicionar pergunta', 'error' => $e->getMessage()];
        }
    }

    public function delete($conn, $id) {
        $sql = "DELETE FROM question WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return ['status' => 'success', 'message' => 'Pergunta excluída com sucesso'];
    }

    
    public function verifyAnswer($answer) {

        $options = explode('|', $this->options);

        if ($options[$this->coption - 1] == $answer) {
            return ['status' => 'success', 'message' => 'Resposta correta'];
        } else {
            return ['status' => 'error', 'message' => 'Resposta incorreta'];
        }
    }

    public static function getQuestionsById($conn, $id, $text_questions = false) : array {
        try {
            $sql = "SELECT  qn.id AS questionnaire_id, qn.name AS questionnaire_name, qt.id AS question_id, qt.quest AS question_text, qt.options AS question_options, qt.coption AS correct_option FROM questionnaire qn JOIN conn_question cq ON qn.id = cq.id_questionnaire JOIN questions qt ON cq.id_quest = qt.id WHERE qn.id = :id";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $questions = [];
            //se $text_questions for verdadeiro, retorna apenas as perguntas como texto
            if ($text_questions) {
                foreach($result as $row) {
                    $questions[] = $row['question_text'];
                }
                return ['status' => 'success', 'message' => 'Perguntas encontradas com sucesso', 'questions' => $questions];
                die();
            }
            // se $text_questions for false, retorna um array com as perguntas em forma de objeto, se for true, retorna um array com as perguntas como texto.
            return ['status' => 'success', 'message' => 'Perguntas encontradas com sucesso', 'questions' => $result];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Erro ao buscar perguntas do questionário', 'error' => $e->getMessage()];
        }
    }

    public static function getAllQuestions($conn) {
        $sql = "SELECT * FROM questions";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ['status' => 'success', 'message' => 'Perguntas encontradas com sucesso', 'questions' => $result];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Erro ao buscar perguntas', 'error' => $e->getMessage()];
        }
    }
}