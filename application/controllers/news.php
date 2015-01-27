<?php

class News extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('news_model');
    }

    public function index() {
        $data['news'] = $this->news_model->get_news();
        $data['title'] = 'News archive';

        //$this->load->view('templates/header', $data);
        $this->load->view('news/index2', $data);
        //$this->load->view('templates/footer');
    }

    public function view($id) {
        $data['news_item'] = $this->news_model->get_news((int) $id);
        if (empty($data['news_item'])) {
            show_404();
        }

        $data['title'] = $data['news_item']['heading'];
        
        $this->load->library('pagination');

        $config['base_url'] = 'http://example.com/index.php/test/page/';
        $config['total_rows'] = 200;
        $config['per_page'] = 20;

        $this->pagination->initialize($config);

        $data['paginator'] = $this->pagination->create_links();

        $this->load->view('templates/header', $data);
        $this->load->view('news/view', $data);
        $this->load->view('templates/footer');
    }

    public function create() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['title'] = 'Create a news item';

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('text', 'text', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('news/create');
            $this->load->view('templates/footer');
        } else {
            $this->news_model->set_news();
            $this->load->view('news/success');
        }
    }

}
