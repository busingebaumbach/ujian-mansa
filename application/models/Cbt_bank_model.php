<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
class Cbt_bank_model extends MY_Model
{
	public function __construct()
	{
        $this->table = 'cbt_bank_soal';
        $this->primary_key = 'id_bank';
        $this->timestamps = false;
        $this->soft_deletes = false;

        $this->has_one['jadwal'] = array('local_key'=>'id_bank', 'foreign_key'=>'id_bank', 'foreign_model'=> 'Cbt_jadwal_model');
        $this->has_one['mapel'] = array('local_key'=>'bank_mapel_id', 'foreign_key'=>'id_mapel', 'foreign_model'=> 'Mapel_model');
        $this->has_one['guru'] = array('local_key'=>'bank_guru_id', 'foreign_key'=>'id_guru', 'foreign_model'=> 'Guru_model');
        $this->has_one['level'] = array('local_key'=>'bank_level', 'foreign_key'=>'id_level', 'foreign_model'=> 'Level_model');
        $this->has_many['soal'] = array('local_key'=>'id_bank', 'foreign_key'=>'bank_id', 'foreign_model'=> 'Cbt_soal_model');
        parent::__construct();
	}
}