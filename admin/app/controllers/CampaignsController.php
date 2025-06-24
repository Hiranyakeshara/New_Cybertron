<?php
class CampaignsController extends Controller
{
    // 📊 View Campaign Results
    public function index()
    {
        $this->view('campaigns/view');
    }

    // 📢 Create Email Campaign
    public function create()
    {
        $this->view('campaigns/create');
    }

    // 👥 Create User Group for Campaign
    public function user()
    {
        $this->view('campaigns/user');
    }
}
