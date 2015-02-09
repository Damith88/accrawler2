<?php

class News_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function fetchNewsArticles($pageLimit = 50) {
        $sql = "select id, heading, content, url as sourceUrl from article where category_id = 2 order by date desc limit $pageLimit";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    public function fetchNewsArticle($id) {
        $query = $this->db->query("select a.heading, a.content, a.url as sourceUrl, ai.sentences, ai.named_entity_info, ai.has_overlapping_entities "
                . "from article a "
                . "left join article_info ai on ai.article_id = a.id "
                . "where a.id = $id");
        return $query->row_array();
    }

    public function searchNews($searchParams, $pageLimit = 10, $offset = 0) {
        list($whereClause, $params) = $this->getWhereClauseWithParams($searchParams);
        $query = $this->db->query("select id, heading, content, url as sourceUrl from article $whereClause limit $offset, $pageLimit", $params);
        return $query->result_array();
    }
    
    public function getRecordCount($searchParams) {
        list($whereClause, $params) = $this->getWhereClauseWithParams($searchParams);
        return $this->db->query("select count(*) as count from article $whereClause", $params)->row()->count;
    }
    
    protected function getWhereClauseWithParams(array $searchParams = array()) {
        $whereParts = array();
        $params = array();
        
        if (!empty($searchParams['accidentOnly'])) {
            $whereParts[] = 'category_id = 2';
        }
        
        if (!empty($searchParams['childOnly'])) {
            $whereParts[] = 'id IN (select article_id from article_tag where tag_id = 1)';
        }
        
        if (!empty($searchParams['keyWords'])) {
            $whereParts[] = 'MATCH (heading, content) AGAINST (?)';
            $params[] = $searchParams['keyWords'];
        }
        
        if (!empty($searchParams['fromDate'])) {
            if (!empty($searchParams['toDate'])) {
                $whereParts[] = 'date between ? AND ?';
                $params[] = $searchParams['fromDate'];
                $params[] = $searchParams['toDate'];
            } else {
                $whereParts[] = 'date >= ?';
                $params[] = $searchParams['fromDate'];
            }
        } else if (!empty($searchParams['toDate'])) {
            $whereParts[] = 'date <= ?';
            $params[] = $searchParams['toDate'];
        }
        
        if (!empty($searchParams['location'])) {
            $whereParts[] = 'id IN (select article_id from named_entity where name LIKE ? and type = "location")';
            $params[] = '%' . $searchParams['location'] . '%';
        }
        
        $whereClause = implode(' AND ', $whereParts);
        if ($whereClause) {
            $whereClause = 'WHERE ' . $whereClause;
        }
        return array($whereClause, $params);
    }

    public function getNameEntityWithType($type) {
        $query = $this->db->query("select distinct(name) as name from named_entity "
                . "WHERE type = '$type' order by name");
        return array_map(function ($value) {
            return $value['name'];
        }, $query->result_array());
    }

}
