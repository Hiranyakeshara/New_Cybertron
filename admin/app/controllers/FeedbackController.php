<?php
class FeedbackController extends Controller {
    public function index() {
        $model = $this->model('Feedback');
        $feedbacks = $model->getAllFeedback();
        $this->view('feedback/view', ['feedbacks' => $feedbacks]);
    }
}



   
   
   
