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
        
        $fromDateObj = new DateTime($from);
        $toDateObj = new DateTime($to);
        
        $searchParams = array(
            'keyWords' => $key,
            'fromDate' => empty($from) ? null : $fromDateObj->format('Y-m-d'),
            'toDate' => empty($to) ? null : $toDateObj->format('Y-m-d'),
            'location' => $location
        );
        
        $data['news'] = $this->news_model->searchNews($searchParams);
        $data['title'] = 'News archive';
        $data['locations'] = $this->news_model->getNameEntityWithType("location");

        $this->load->view('news/index2', $data);
    }

}
