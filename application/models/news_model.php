<?php

class News_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_news($id = FALSE) {
        if ($id === FALSE) {
            $query = $this->db->query('select id, heading, content, url as sourceUrl from article where category_id = 2 order by date desc limit 10');
            return $query->result_array();
        }

        $query = $this->db->query("select a.heading, a.content, a.url, ai.sentences, ai.named_entity_info, ai.has_overlapping_entities "
                . "from article a "
                . "left join article_info ai on ai.article_id = a.id "
                . "where a.id = $id");
        return $query->row_array();
    }

    public function set_news() {
        $this->load->helper('url');

        $slug = url_title($this->input->post('title'), 'dash', TRUE);

        $data = array(
            'title' => $this->input->post('title'),
            'slug' => $slug,
            'text' => $this->input->post('text')
        );

        return $this->db->insert('news', $data);
    }

}
