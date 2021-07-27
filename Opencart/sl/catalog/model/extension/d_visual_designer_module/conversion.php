<?php
class ModelExtensionDVisualDesignerModuleConversion extends Model
{

    public function checkInstalledDB(){
        $this->db->query("CREATE TABLE  IF NOT EXISTS `".DB_PREFIX."dvdl_conversion` (
            `description_id` VARCHAR(50) NOT NULL,
            `type` VARCHAR(50) NOT NULL,
            `count` INT(11) NOT NULL,
            `date_added` DATE NOT NULL
            )
            COLLATE='utf8_general_ci' ENGINE=MyISAM;
            ");

        $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."dvdl_conversion_ip` (
              description_id varchar(50) not null,
              type           varchar(50) not null,
              ip             varchar(40) not null,
              date_added     datetime    not null,
              primary key (description_id, type, ip)
            )
            COLLATE='utf8_general_ci' ENGINE=MyISAM;
            ");
    }

    public function getConversions($description_id){
        $this->checkInstalledDB();
        $sql = "SELECT type, sum(count) as total FROM ".DB_PREFIX."dvdl_conversion WHERE description_id= '".$description_id."' GROUP BY type UNION SELECT TYPE, SUM(COUNT) FROM ( SELECT description_id, IF(TYPE <> 'view', 'all', 'view') AS TYPE, COUNT, date_added FROM ".DB_PREFIX."dvdl_conversion WHERE type<>'all') c WHERE description_id = '".$description_id."' GROUP BY type";
        $query = $this->db->query($sql);

        $conversion_data = array();

        if($query->num_rows){
            foreach ($query->rows as $row) {
                $conversion_data[$row['type']] = $row['total'];
            }
        }

        return $conversion_data;
    }

    public function addConversion($description_id, $type){
        $this->load->model('extension/d_opencart_patch/user');
        if (!$this->model_extension_d_opencart_patch_user->isLogged()) {
            $client_ip = $this->request->server['REMOTE_ADDR'];
            if($type == 'view'){
                $query = $this->db->query("SELECT * FROM ".DB_PREFIX."dvdl_conversion_ip WHERE `description_id` = '".$description_id."' AND `type`='view' AND `ip`='".$client_ip."'");
                if($query->num_rows){
                    return;
                }
            }
            else{
                $query = $this->db->query("SELECT * FROM `".DB_PREFIX."dvdl_conversion_ip` WHERE `description_id` = '".$description_id."' AND `type` != 'view' AND `ip`='".$client_ip."'");

                if($query->num_rows){
                    return;
                }
            }
            $this->db->query("INSERT INTO `".DB_PREFIX."dvdl_conversion_ip` SET `description_id` = '".$description_id."', `type` = '".$type."', `ip` = '".$client_ip."', date_added =NOW()");

            $query = $this->db->query("SELECT * FROM ".DB_PREFIX."dvdl_conversion WHERE description_id='".$description_id."' AND type='".$type."' AND date_added=CURDATE()");

            if($query->num_rows){
                $this->db->query("UPDATE ".DB_PREFIX."dvdl_conversion SET count=count+1 WHERE description_id='".$description_id."' AND type='".$type."'");
            }
            else{
                $this->db->query("INSERT INTO ".DB_PREFIX."dvdl_conversion SET description_id='".$description_id."', type='".$type."', count='1', date_added =NOW()");
            }

        }
    }
}
