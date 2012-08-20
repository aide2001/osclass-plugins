<?php
    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */

    /**
     * Model database for Jobs tables
     * 
     * @package OSClass
     * @subpackage Model
     * @since 3.0
     */
    class ModelJB extends DAO
    {
        /**
         * It references to self object: ModelJB.
         * It is used as a singleton
         * 
         * @access private
         * @since 3.0
         * @var ModelJB
         */
        private static $instance ;

        /**
         * It creates a new ModelJB object class ir if it has been created
         * before, it return the previous object
         * 
         * @access public
         * @since 3.0
         * @return ModelJB
         */
        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        /**
         * Construct
         */
        function __construct()
        {
            parent::__construct();
        }
        
        /**
         * Return table name jobs attributes
         * @return string
         */
        public function getTable_JobsAttr()
        {
            return DB_TABLE_PREFIX.'t_item_job_attr' ;
        }
        
        /**
         * Return table name jobs attributes description
         * @return string
         */
        public function getTable_JobsAttrDescription()
        {
            return DB_TABLE_PREFIX.'t_item_job_description_attr' ;
        }
        
        /**
         * Return table name jobs applicants
         * @return string
         */
        public function getTable_JobsApplicants()
        {
            return DB_TABLE_PREFIX.'t_item_job_applicant' ;
        }
        
        /**
         * Return table name jobs files
         * @return string
         */
        public function getTable_JobsFiles()
        {
            return DB_TABLE_PREFIX.'t_item_job_file' ;
        }
        
        /**
         * Return table name jobs notes
         * @return string
         */
        public function getTable_JobsNotes()
        {
            return DB_TABLE_PREFIX.'t_item_job_note' ;
        }
        
        /**
         * Import sql file
         * @param type $file 
         */
        public function import($file)
        {
            $path = osc_plugin_resource($file) ;
            $sql = file_get_contents($path);

            if(! $this->dao->importSQL($sql) ){
                throw new Exception( "Error importSQL::ModelJB<br>".$file ) ;
            }
        }
        
        /**
         *  Remove data and tables related to the plugin.
         */
        public function uninstall()
        {
            $this->dao->query('DROP TABLE '. $this->getTable_JobsFiles());
            $this->dao->query('DROP TABLE '. $this->getTable_JobsApplicants());
            $this->dao->query('DROP TABLE '. $this->getTable_JobsAttrDescription());
            $this->dao->query('DROP TABLE '. $this->getTable_JobsAttr());
        }
        
        /**
         * Get all entries from jobs attributes table
         *
         * @return array
         */
        public function getAllAttributes()
        {
            $this->dao->select();
            $this->dao->from($this->getTable_JobsAttr());
            
            $result = $this->dao->get();
            if( !$result ) {
                return array() ;
            }
            return $result->result();
        }
        
        /**
         * Get Jobs attributes given a item id
         *
         * @param int $item_id
         * @return array
         */
        public function getJobsAttrByItemId($item_id)
        {
            $this->dao->select();
            $this->dao->from($this->getTable_JobsAttr());
            $this->dao->where('fk_i_item_id', $item_id);
            
            $result = $this->dao->get();
            if( !$result ) {
                return array() ;
            }
            return $result->row();
        }
        
        /**
         * Get Jobs attributes descriptions given a item id
         *
         * @param int $item_id
         * @return array
         */
        public function getJobsAttrDescriptionsByItemId($item_id)
        {
            $this->dao->select();
            $this->dao->from($this->getTable_JobsAttrDescription());
            $this->dao->where('fk_i_item_id', $item_id);
            
            $result = $this->dao->get();
            if( !$result ) {
                return array() ;
            }
            
            return $result->result();
        }
        
        /**
         * Insert Jobs attributes
         *
         * @param int $item_id
         * @param string $relation
         * @param string $position_type
         * @param int $salaryText
         */
        public function insertJobsAttr($item_id, $relation, $position_type, $salaryText)
        {
            $aSet = array(
                'fk_i_item_id'      => $item_id,
                'e_position_type'   => $position_type,
                's_salary_text'     => $salaryText
            );
            
            return $this->dao->insert($this->getTable_JobsAttr(), $aSet);
        }
        
        /**
         * Insert Jobs attributes descriptions
         *
         * @param int $item_id
         * @param string $locale
         * @param string $desiredExp
         * @param string $studies
         * @param string $minRequirements
         * @param string $desiredRequirements
         * @param string $contract
         */
        public function insertJobsAttrDescription($item_id, $locale, $desiredExp, $studies, $minRequirements, $desiredRequirements, $contract)
        {
            $aSet = array(
                'fk_i_item_id'              => $item_id,
                'fk_c_locale_code'          => $locale,
                's_desired_exp'             => $desiredExp,
                's_studies'                 => $studies,
                's_minimum_requirements'    => $minRequirements,
                's_desired_requirements'    => $desiredRequirements,
                's_contract'                => $contract
            );
            
            return $this->dao->insert($this->getTable_JobsAttrDescription(), $aSet);
        }
        
        /**
         * Replace salary_min_hour, salary_max_hour given a item id
         *
         * @param type $item_id
         * @param type $salaryHourmin
         * @param type $salaryHourMax 
         */
        public function replaceJobsSalaryAttr($item_id, $salaryText)
        {
            $aSet = array(
                'fk_i_item_id'      => $item_id,
                's_salary_text'     => $salaryText
            );
            return $this->dao->replace($this->getTable_JobsAttr(), $aSet);
        }
        
        /**
         * Replace Jobs attributes 
         */
        public function replaceJobsAttr($item_id, $relation, $position_type, $salaryText)
        {
            $aSet = array(
                'fk_i_item_id'      => $item_id,
                'e_position_type'   => $position_type,
                's_salary_text'     => $salaryText
            );
            return $this->dao->replace( $this->getTable_JobsAttr(), $aSet);
        }
        
        /**
         * Replace Jobs attributes descriptions
         */
        public function replaceJobsAttrDescriptions($item_id, $locale, $desiredExp, $studies, $minRequirements, $desiredRequirements, $contract)
        {
            $aSet = array(
                'fk_i_item_id'              => $item_id,
                'fk_c_locale_code'          => $locale,
                's_desired_exp'             => $desiredExp,
                's_studies'                 => $studies,
                's_minimum_requirements'    => $minRequirements,
                's_desired_requirements'    => $desiredRequirements,
                's_contract'                => $contract
            );
            return $this->dao->replace($this->getTable_JobsAttrDescription(), $aSet);
        }
        
        /**
         * Insert files attached to an applicant
         * 
         * @param $applicantId
         * @param $fileName
         * @return boolean 
         */
        public function insertFile($applicantId, $fileName) {
            return $this->dao->insert(
                    $this->getTable_JobsFiles()
                    ,array(
                        'fk_i_applicant_id' => $applicantId
                        ,'dt_date' => date("Y-m-d H:i:s")
                        ,'s_name' => $fileName
                    ));
        }
        
        /**
         * Insert an applicant
         * 
         * @param $itemId
         * @param $name
         * @param $email
         * @param $coverLetter
         * @return applicant's ID 
         */
        public function insertApplicant($itemId, $name, $email, $coverLetter) {
            $date = date("Y-m-h H:i:s");
            $app = $this->dao->insert(
                    $this->getTable_JobsApplicants()
                    ,array(
                        'fk_i_item_id' => $itemId
                        ,'s_name' => $name
                        ,'s_email' => $email
                        ,'s_cover_letter' => $coverLetter
                        ,'dt_date' => $date
                        ,'i_status' => 0
                        ,'i_rating' => 0
                    ));
            if($app) {
                return $this->dao->insertedId();
            } else {
                false;
            }
        }
        
        
        /**
         * Get applicants
         * 
         * @param $start
         * @param $length
         * @param $conditions
         * 
         * @return array
         */
        public function search($start = 0, $length = 10, $conditions = null) {
            
            $this->dao->select( sprintf("a.*, d.*, FIELD(fk_c_locale_code, '%s') as locale_order", $this->dao->connId->real_escape_string(osc_current_user_locale()) ) ) ;
            $this->dao->from($this->getTable_JobsApplicants()." a");
            $this->dao->join(DB_TABLE_PREFIX."t_item_description d", "d.fk_i_item_id = a.fk_i_item_id");
            if($conditions!=null) {
                foreach($conditions as $k => $v) {
                    if($k=='item') {
                        $this->dao->where('a.fk_i_item_id', $v);
                    }
                    if($k=='item_text') {
                        $this->dao->where("d.s_title LIKE '%%".$v."%%'");
                    }
                    if($k=='email') {
                        $this->dao->where("a.s_email LIKE '%%".$v."%%'");
                    }
                    if($k=='name') {
                        $this->dao->where("a.s_name LIKE '%%".$v."%%'");
                    }
                    if($k=='status') {
                        $this->dao->where("a.i_status", $v);
                    }
                }
            }
            $this->dao->groupBy('a.pk_i_id');
            $this->dao->limit($start, $length);
            
            $result = $this->dao->get();
            if( !$result ) {
                return array() ;
            }
            
            return $result->result();
            
        }

        public function searchCount($conditions = null) {
            
            $this->dao->select( "a.pk_i_id" ) ;
            $this->dao->from($this->getTable_JobsApplicants()." a");
            $this->dao->join(DB_TABLE_PREFIX."t_item_description d", "d.fk_i_item_id = a.fk_i_item_id");
            if($conditions!=null) {
                foreach($conditions as $k => $v) {
                    if($k=='item') {
                        $this->dao->where('a.fk_i_item_id', $v);
                    }
                    if($k=='item_text') {
                        $this->dao->where("d.s_title LIKE '%%".$v."%%'");
                    }
                    if($k=='email') {
                        $this->dao->where("a.s_email LIKE '%%".$v."%%'");
                    }
                    if($k=='name') {
                        $this->dao->where("a.s_name LIKE '%%".$v."%%'");
                    }
                    if($k=='status') {
                        $this->dao->where("a.i_status", $v);
                    }
                }
            }
            $this->dao->groupBy('a.pk_i_id');
            
            $result = $this->dao->get();
            if( !$result ) {
                $searchTotal = 0;
            } else {
                $searchTotal = count($result->result());
            }
            
            $this->dao->select( "COUNT(*) as total" ) ;
            $this->dao->from($this->getTable_JobsApplicants()." a");
            $result = $this->dao->get();
            if( !$result ) {
                $total = 0;
            } else {
                $total = $result->row();
            }
            
            return array($searchTotal, $total['total']);
            
        }
        
        /**
         * Set applicants rating
         * 
         * @param $applicantId
         * @param $rating 
         */
        public function setRating($applicantId, $rating) {
            $this->dao->update(
                    $this->getTable_JobsApplicants()
                    ,array('i_rating' => $rating)
                    ,array('pk_i_id' => $applicantId));
        }
        
        /**
         * Get applicant
         * 
         * @param $id
         * 
         * @return array
         */
        public function getApplicant($id) {
            
            $this->dao->select();
            $this->dao->from($this->getTable_JobsApplicants());
            $this->dao->where("pk_i_id", $id);
            
            $result = $this->dao->get();
            if( !$result ) {
                return array() ;
            }
            
            return $result->row();
            
        }
        
        /**
         * Get applicant's CV
         * 
         * @param $id
         * 
         * @return array
         */
        public function getCVFromApplicant($id) {
            
            $this->dao->select();
            $this->dao->from($this->getTable_JobsFiles());
            $this->dao->where("fk_i_applicant_id", $id);
            
            $result = $this->dao->get();
            if( !$result ) {
                return array() ;
            }
            
            return $result->row();
            
        }
        
        /**
         * Get applicant's CV
         * 
         * @param $id
         * 
         * @return array
         */
        public function getNotesFromApplicant($id) {
            
            $this->dao->select();
            $this->dao->from($this->getTable_JobsNotes());
            $this->dao->where("fk_i_applicant_id", $id);
            
            $result = $this->dao->get();
            if( !$result ) {
                return array() ;
            }
            
            return $result->result();
            
        }
        
        /**
         * Delete entries at jobs attr description table given a locale code
         *
         * @param type $locale 
         */
        public function deleteLocale($locale)
        {
            return $this->dao->delete($this->getTable_JobsAttrDescription(), array('fk_c_locale_code' => $locale) );
        }
        
        /**
         * Delete entries at jobs tables given a item id
         *
         * @param type $locale 
         */
        public function deleteItem($item_id)
        {
            $this->dao->delete($this->getTable_JobsAttr(), array('fk_i_item_id' => $item_id) );
            return $this->dao->delete($this->getTable_JobsAttrDescription(), array('fk_i_item_id' => $item_id) );
        }
        
        public function deleteNote($id)
        {
            return $this->dao->delete($this->getTable_JobsNotes(), array('pk_i_id' => $id));
        }
        
        
        public function insertNote($id, $text)
        {
            return $this->dao->insert($this->getTable_JobsNotes(), array('dt_date' => date("Y-m-d H:i:s"), 's_text' => $text, 'fk_i_applicant_id' => $id));
        }
        
        public function updateNote($id, $text)
        {
            return $this->dao->update($this->getTable_JobsNotes(), array('dt_date' => date("Y-m-d H:i:s"), 's_text' => $text), array('pk_i_id' => $id));
        }
        
        public function changeStatus($applicantId, $status)
        {
            return $this->dao->update($this->getTable_JobsApplicants(), array('i_status' => $status), array('pk_i_id' => $applicantId));
        }
        
    }
?>