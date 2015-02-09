<?php

class News extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('news_model');
    }

    public function index() {
        $pageLimit = $this->config->item('items_per_page');
        $data['news'] = $this->news_model->fetchNewsArticles($pageLimit);
        $data['title'] = 'News archive';
        $data['locations'] = $this->news_model->getNameEntityWithType("location");
        $this->load->view('news/index2', $data);
        $this->load->library('pagination');

        $config['base_url'] = 'http://example.com/index.php/test/page/';
        $config['total_rows'] = 200;
        $config['per_page'] = $pageLimit;

        $this->pagination->initialize($config);
        
        $data['paginator'] = $this->pagination->create_links();
    }

    public function view($id) {
        $data['news_item'] = $this->news_model->fetchNewsArticle((int) $id);
        if (empty($data['news_item'])) {
            show_404();
        }

        $data['title'] = $data['news_item']['heading'];

        $this->load->view('news/view', $data);
    }
    
    public function search() {
        $key = filter_input(INPUT_POST, 'keyWords');
        $from = filter_input(INPUT_POST, 'fromDate');
        $to = filter_input(INPUT_POST, 'toDate');
        $location = filter_input(INPUT_POST, 'location');
        
        $searchParams = array(
            'keyWords' => $key,
            'from' => $from,
            'to' => $to,
            'location' => $location,
        );
        $data['news'] = $this->news_model->searchNews($searchParams);
        $data['title'] = 'News archive';
        $data['locations'] = $this->news_model->getNameEntityWithType("location");

        $this->load->view('news/index2', $data);
    }

//    public function create() {
//        $this->load->helper('form');
//        $this->load->library('form_validation');
//
//        $data['title'] = 'Create a news item';
//
//        $this->form_validation->set_rules('title', 'Title', 'required');
//        $this->form_validation->set_rules('text', 'text', 'required');
//
//        if ($this->form_validation->run() === FALSE) {
//            $this->load->view('templates/header', $data);
//            $this->load->view('news/create');
//            $this->load->view('templates/footer');
//        } else {
//            $this->news_model->set_news();
//            $this->load->view('news/success');
//        }
//    }

}
