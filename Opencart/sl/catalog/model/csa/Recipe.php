<?php 
class ModelCsaRecipe extends Model {

    public function getAllRecipe($data) {
        $sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "recipes";

        if (isset($data['start']) && isset($data['limit'])) {

			if ($data['start'] < 0) {

				$data['start'] = 0;

			}

			

			if ($data['limit'] < 1) {

				$data['limit'] = 10;

			}	

		

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];

		}	

		

        $query = $this->db->query($sql);
        
        return $query->rows;
    }
    public function getRecipe($recipe_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "recipes WHERE recipe_id = '" . (int) $recipe_id . "'");

        return $query->row;
    }
    public function getRecipeByName($recipe_title) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "recipes WHERE recipe_title = '" .$recipe_title . "'");

        return $query->row;
    }

    public function getTotalRecipe() {

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "recipes");

	

		return $query->row['total'];

	}

}