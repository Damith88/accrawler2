<?php

class News extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('news_model');
        $this->load->library('session');
    }

    public function paginate($page = 0) {
        $searchParams = $this->session->userdata('filter_criteria');
        $this->searchWithParams($searchParams, false);
    }

    public function index() {
        $searchParams = $this->config->item('default_filters');
        $this->searchWithParams($searchParams);
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

        $this->searchWithParams($searchParams);
    }

    protected function searchWithParams(array $searchParams = array(), $updateSession = true) {
        $pageLimit = $this->config->item('items_per_page');
        $data['title'] = 'News archive';
        $data['locations'] = $this->news_model->getNameEntityWithType("location");
        
        if ($updateSession) {
            $this->saveFilterCriteriaToSession($searchParams);
            $numberOfResults = $this->session->userdata('pageCount');
        } else {
            $numberOfResults = $this->news_model->getRecordCount($searchParams);
        }
        
        $numberOfResults = $this->news_model->getRecordCount($searchParams);

        $this->load->library('pagination');

        $config['base_url'] = site_url('news/paginate');
        $config['total_rows'] = $numberOfResults;
        $config['per_page'] = $pageLimit;
        $config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li><span><b>";
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);
        $data['pagination_helper'] = $this->pagination;
        
        $page = $this->uri->segment(3, 0);

        $data['news'] = $this->news_model->searchNews($searchParams, $pageLimit, $page);
        $data['latest_accidents'] = $this->news_model->getLatestAccidents();

        $this->load->view('news/index2', $data);
    }

    private function saveFilterCriteriaToSession($filterCriteria) {
        $this->session->set_userdata('filter_criteria', $filterCriteria);
    }

}
