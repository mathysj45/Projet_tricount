<?php

class CategoryManager extends AbstractManager
{
    public function findAll() : array
    {
        $query = $this->db->prepare('SELECT * FROM category');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $categories = [];

        foreach($result as $item)
        {
            $categories[] = new Category($item["label"], $item["id"]);
        }

        return $categories;
    }
}