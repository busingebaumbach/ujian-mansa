# V 1.6.0 RC-3
### DONATE VERSION

#### Database
- versi db terakhir: `20250623000000`
- perubahan type id key menjadi INT AI di tabel: 
    * `kelas_jadwal_kbm`
    * `kelas_jadwal_mapel`
    * `kelas_jadwal_materi`
    * `log_materi`
    * `cbt_pengawas`
    * `cbt_sesi_siswa`
    * `cbt_nomor_peserta`
- penambahan field tabel:
    * field `kbm_jam_selesai` & `libur` di tabel `kelas_jadwal_kbm`
    * field `dari` & `sampai` di tabel `kelas_jadwal_mapel`
    * field `istirahat` untuk kebutuhan cetak berita acara
    * field `id_sesi_siswa` sebagai PRIMARY KEY
- penambahan tabel:
    * `cbt_siswa` pengganti `cbt_soal_siswa`
    * `cbt_rekap_jadwal` pengganti `cbt_rekap`
    * `cbt_rekap_siswa` pengganti `cbt_rekap_nilai`
    * `ci_session` jika menggunakan sess_driver `database`
- tabel yang tidak digunakan lagi:
    * `api_setting` dari awal memang tidak digunakan
    * `api_token` dari awal memang tidak digunakan
    * `cbt_soal_siswa` diganti `cbt_siswa`
    * `cbt_durasi_siswa` digabung ke `cbt_siswa`
    * `cbt_nilai` digabung ke `cbt_siswa`
    * `cbt_rekap` diganti `cbt_rekap_jadwal`
    * `cbt_rekap_nilai` diganti `cbt_rekap_siswa`
- auto migrasi jika menggunakan db dari versi sebelumnya

#### System
- fixing minor bug installer
- hapus plugin `dropipy.js` di menu import siswa/guru
- hapus plugin Pace.js
- updated jquery 3.7.1
- kompatibilitas PHP 7.4 dan 8.x
- ganti dari serialize ke data json

#### Data Umum
- simpan kelas/rombel baru tanpa siswa
- auto generate kelas ketika kenaikan kelas
- user auto aktif setelah import file excel data siswa/guru
- support session driver `database` atau `file`
- copy kelas dan siswa dari TP atau SMT sebelumnya
- notifikasi jika TP atau SMT belum diatur sesuai tahun ajaran
- tambah beberapa kelas sekaligus
- konfirmasi hapus siswa

#### E-Learning 
- jadwal elearning flexible
- hari libur elearning flexible
- copy jadwal dari smt sebelumnya
- fixing beberapa bug di menu nilai dan kehadiran
- membagi waktu sesi untuk 2 jadwal ujian atau lebih
- arsip materi/tugas
- copy materi/tugas dari smt sebelumnya tanpa harus edit kelas

#### CBT
- status siswa per tanggal di akun admin
- Drag & Drop ganti urutan nomor soal
- Analisis harus setelah Rekap dan menu Analisis pindah ke menu REKAP
- REKAP bisa dilakukan sebelum KOREKSI
- KOREKSI bisa dilakukan dari menu REKAP
- penambahan hasil analisis Indeks Kesukaran Distraktor dan RELIABILITAS
- filter tanggal di menu pengawas
- soal audio dan video dibatasi 3x play
- nomor peserta diatur per semester
- generate nomor peserta flexible
- ekspor analisis
- ruang dan sesi siswa diatur per semester
- filter jadwal mapel agama