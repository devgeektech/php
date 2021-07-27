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
}
