<?php
/*
*   location: admin/model
*/

class ModelExtensionModuleDVisualDesignerModule extends Model {

    private $codename = 'd_visual_designer_module';
    
    public function installTemplate($module_id, $language_id, $config, $template_id) {
       $this->load->model('extension/d_visual_designer/designer');

       $this->model_extension_d_visual_designer_designer->installTemplate($this->codename, $module_id, 'description['.$language_id.'][description]', $config, $template_id);
    }

    public function ajax($link){
        return str_replace('&amp;', '&', $link);
    }

    public function addModuleToLayout($module_id, $layout_id, $position, $sort_order)
    {
        $this->db->query(sprintf("INSERT INTO `".DB_PREFIX."layout_module` SET 
            layout_id = '%s', 
            code = '%s', 
            position = '%s', 
            sort_order = '%s'", (int)$layout_id, $this->codename.'.'.(int)$module_id, $position, (int)$sort_order));
    }

    public function getLayoutsByRoute($route)
    {
        $query = $this->db->query("SELECT * FROM `".DB_PREFIX."layout_route` lr LEFT JOIN `".DB_PREFIX."layout` l ON (l.layout_id = lr.layout_id)  WHERE lr.`route`='".$route."'");
        $layout_data = array();
        if ($query->num_rows) {
            foreach ($query->rows as $row) {
                $layout_data[] = array(
                    'layout_id' => $row['layout_id'],
                    'name' => $row['name']
                    );
            }
        }
        return $layout_data;
    }

    public function saveSuccess(){
        $this->session->data['success'] = $this->language->get('text_success');
    }
        
    public function getGroupId(){
        if(VERSION == '2.0.0.0'){
            $user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . $this->user->getId() . "'");
            $user_group_id = (int)$user_query->row['user_group_id'];
        }else{
            $user_group_id = $this->user->getGroupId();
        }

        return $user_group_id;
    }
}