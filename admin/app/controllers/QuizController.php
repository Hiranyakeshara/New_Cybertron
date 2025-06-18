<?php
class QuizController extends Controller {
    public function index() {
        $model = $this->model('Quiz');
        $results = $model->getResults();
        $this->view('quiz/view', ['results' => $results]);
    }
}
