<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
class Cbt_jadwal_model extends MY_Model
{
	public function __construct()
	{
        $this->table = 'cbt_jadwal';
        $this->primary_key = 'id_jadwal';
        $this->timestamps = false;
        $this->soft_deletes = false;

        $this->has_one['jenis'] = array('local_key'=>'id_jenis', 'foreign_key'=>'id_jenis', 'foreign_model'=> 'Cbt_jenis_model');
        $this->has_one['bank'] = array('local_key'=>'id_bank', 'foreign_key'=>'id_bank', 'foreign_model'=> 'Cbt_bank_model');
        $this->has_one['tp'] = array('local_key'=>'id_tp', 'foreign_key'=>'id_tp', 'foreign_model'=> 'Tp_model');
        $this->has_one['smt'] = array('local_key'=>'id_smt', 'foreign_key'=>'id_smt', 'foreign_model'=> 'Smt_model');
        $this->has_one['rekap'] = array('local_key'=>'id_jadwal', 'foreign_key'=>'id_jadwal', 'foreign_model'=> 'Rekap_jadwal_model');

        $this->has_many['sesisiswa'] = array('local_key'=>'id_tp', 'foreign_key'=>'tp_id', 'foreign_model'=> 'Cbt_sesisiswa_model');
        $this->has_many['cbtsiswa'] = array('local_key'=>'id_jadwal', 'foreign_key'=>'id_jadwal', 'foreign_model'=> 'Cbt_siswa_model');
        $this->has_many['pengawas'] = array('local_key'=>'id_jadwal', 'foreign_key'=>'id_jadwal', 'foreign_model'=> 'Cbt_pengawas_model');
		parent::__construct();
	}
}