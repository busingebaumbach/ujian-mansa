<?php
/**
 * Created by IntelliJ IDEA.
 * User: multazam
 * Date: 07/07/20
 * Time: 17:20
 */

?>
<div class="bg-white mx-5">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $judul ?></h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default my-shadow mb-4">
                <div class="card-header">
                    <h6 class="card-title"><?= $subjudul ?></h6>
                </div>

                <div class="card-body">
                    <div class="col-lg-12 p-0 mt-3">
                        <div class="alert alert-default-warning align-content-center" role="alert">
                            <div class="row py-1" id="update-info">Memeriksa versi aplikasi</div>
                        </div>
                    </div>
                </div>

                <div class="overlay d-none" id="loading">
                    <div class="spinner-grow"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= form_open('convert', array('id' => 'post_converted')) ?>
<?= form_close() ?>

<script>
    const limitAntrian = 500
    const limitPost = 99 // jangan dirubah kecuali maximum_post sudah diatur lebih dari 1000
    // temp database
    let db;
    const request = indexedDB.open("GarudaCbtSoal", 1);
    const table = {
        masterTp: 'master_tp',
        masterSmt: 'master_smt',
        masterMapel: 'master_mapel',
        masterKelas: 'master_kelas',
        masterGuru: 'master_guru',
        cbtBankSoal: 'cbt_bank_soal',
        cbtJadwal: 'cbt_jadwal',
        cbtSoal: 'cbt_soal',
        cbtPengawas: 'cbt_pengawas',
        sesiSiswa: 'cbt_sesi_siswa',
        cbtRuang: 'cbt_ruang',
        cbtSesi: 'cbt_sesi',
        soalSiswa: 'soalsiswa',
        cbtSiswa: 'cbtsiswa',
        cbtNilai: 'cbt_nilai',
        cbtDurasi: 'cbt_durasi_siswa',
        cbtLog: 'log_ujian',
        rekapJadwal: 'rekapjadwal',
        rekapNilai: 'rekapsiswa',
        kbmJadwal: 'jadwalkbm',
        kbmMapel: 'jadwalmapel',
        kbmMateri: 'jadwalmateri',
        kbmLog: 'logmateri',
    }

    request.onerror = function(event) {
        console.log("Gagal membuka database", event);
    };

    request.onsuccess = function(event) {
        db = event.target.result;
        console.log("Database berhasil dibuka");
    };

    request.onupgradeneeded = function(event) {
        db = event.target.result;
        if (!db.objectStoreNames.contains(table.masterTp)) {
            const soalStore = db.createObjectStore(table.masterTp, { keyPath: "id_tp" });
        }
        if (!db.objectStoreNames.contains(table.masterSmt)) {
            const soalStore = db.createObjectStore(table.masterSmt, { keyPath: "id_smt" });
        }
        if (!db.objectStoreNames.contains(table.masterMapel)) {
            const soalStore = db.createObjectStore(table.masterMapel, { keyPath: "id_mapel" });
        }
        if (!db.objectStoreNames.contains(table.masterKelas)) {
            const soalStore = db.createObjectStore(table.masterKelas, { keyPath: "id_kelas" });
        }
        if (!db.objectStoreNames.contains(table.masterGuru)) {
            const soalStore = db.createObjectStore(table.masterGuru, { keyPath: "id_guru" });
        }
        if (!db.objectStoreNames.contains(table.cbtBankSoal)) {
            const soalStore = db.createObjectStore(table.cbtBankSoal, { keyPath: "id_bank" });
        }
        if (!db.objectStoreNames.contains(table.cbtJadwal)) {
            const soalStore = db.createObjectStore(table.cbtJadwal, { keyPath: "id_jadwal" });
        }
        if (!db.objectStoreNames.contains(table.cbtSoal)) {
            const soalStore = db.createObjectStore(table.cbtSoal, { keyPath: "id_soal" });
        }
        if (!db.objectStoreNames.contains(table.soalSiswa)) {
            const soalStore = db.createObjectStore(table.soalSiswa, { keyPath: "id_soal_siswa" });
            soalStore.createIndex("id_jadwal", "id_jadwal", { unique: false });
        }
        if (!db.objectStoreNames.contains(table.cbtSiswa)) {
            const siswaStore = db.createObjectStore(table.cbtSiswa, { keyPath: "id", autoIncrement: true });
            siswaStore.createIndex("id_jadwal", "id_jadwal", { unique: false });
        }
        if (!db.objectStoreNames.contains(table.cbtPengawas)) {
            const siswaStore = db.createObjectStore(table.cbtPengawas, { keyPath: "id_pengawas" });
        }
        if (!db.objectStoreNames.contains(table.cbtNilai)) {
            const soalStore = db.createObjectStore(table.cbtNilai, { keyPath: "id_nilai" });
            soalStore.createIndex("id_jadwal", "id_jadwal", { unique: false });
        }
        if (!db.objectStoreNames.contains(table.cbtDurasi)) {
            const soalStore = db.createObjectStore(table.cbtDurasi, { keyPath: "id_durasi" });
            soalStore.createIndex("id_jadwal", "id_jadwal", { unique: false });
        }
        if (!db.objectStoreNames.contains(table.cbtLog)) {
            const soalStore = db.createObjectStore(table.cbtLog, { keyPath: "id_log" });
            soalStore.createIndex("id_jadwal", "id_jadwal", { unique: false });
        }
        if (!db.objectStoreNames.contains(table.rekapJadwal)) {
            const soalStore = db.createObjectStore(table.rekapJadwal, { keyPath: "id_rekap" });
            soalStore.createIndex("id_jadwal", "id_jadwal", { unique: false });
        }
        if (!db.objectStoreNames.contains(table.rekapNilai)) {
            const soalStore = db.createObjectStore(table.rekapNilai, { keyPath: "id_rekap_nilai" });
            soalStore.createIndex("id_jadwal", "id_jadwal", { unique: false });
        }
        if (!db.objectStoreNames.contains(table.sesiSiswa)) {
            db.createObjectStore(table.sesiSiswa, { keyPath: "siswa_id" });
        }
        if (!db.objectStoreNames.contains(table.cbtRuang)) {
            db.createObjectStore(table.cbtRuang, { keyPath: "id_ruang" });
        }
        if (!db.objectStoreNames.contains(table.cbtSesi)) {
            db.createObjectStore(table.cbtSesi, { keyPath: "id_sesi" });
        }
        if (!db.objectStoreNames.contains(table.kbmJadwal)) {
            const soalStore = db.createObjectStore(table.kbmJadwal, { keyPath: "id_kbm" });
            soalStore.createIndex("id_kelas", "id_kelas", { unique: false });
        }
        if (!db.objectStoreNames.contains(table.kbmMapel)) {
            const soalStore = db.createObjectStore(table.kbmMapel, { keyPath: "id", autoIncrement: true });
            soalStore.createIndex("id_kelas", "id_kelas", { unique: false });
        }
        if (!db.objectStoreNames.contains(table.kbmMateri)) {
            const soalStore = db.createObjectStore(table.kbmMateri, { keyPath: "id_kjm" });
            soalStore.createIndex("id_mapel", "id_mapel", { unique: false });
        }
        if (!db.objectStoreNames.contains(table.kbmLog)) {
            const soalStore = db.createObjectStore(table.kbmLog, { keyPath: "id_log" });
            soalStore.createIndex("id_siswa", "id_siswa", { unique: false });
        }
        console.log("Object store berhasil dibuat");
    };

    function deleteDatabase() {
        const deleteRequest = indexedDB.deleteDatabase("GarudaCbtSoal");

        deleteRequest.onsuccess = function() {
            console.log("Database 'DatabaseSoal' berhasil dihapus.");
        };

        deleteRequest.onerror = function(event) {
            console.error("Gagal menghapus database:", event.target.errorCode);
        };

        deleteRequest.onblocked = function() {
            console.warn("Penghapusan database diblokir. Pastikan tidak ada koneksi terbuka ke database ini.");
        };
    }

    let totalConvertedMapel = 0;
    let isNeedConvertMapel = false;
    let totalDataMapel = 0;
    let isNeedConvertKbm = false;
    let totalDataKbm = 0;
    let isNeedConvertMateri = false;
    let totalDataMateri = 0;
    let totalDataLog = 0;

    let totalDataCbt = 0;
    let isNeedConvertCbt = false;
    let totalRekapJadwal = 0;
    let totalRekapNilai = 0;
    let isNeedConvertRekap = false;

    let totalKelass = 0;
    let totalBanks = 0;
    let totalJadwal = 0;
    let totalSoals = 0;
    let totalPengawas = 0;
    let totalCbtDurasi = 0;
    let totalCbtLog = 0;
    let totalCbtNilai = 0;
    let totalSesi = 0;

    $(document).ready(function ($) {
        cekUpdate()
    });

    async function cekUpdate() {
        swal.fire({
            text: "Memeriksa Update",
            allowEscapeKey: false,
            allowOutsideClick: false,
            didOpen: () => {
                swal.showLoading();
            }
        });
        let arrBools = [];
        // cek versi
        setProgress("Memeriksa versi aplikasi...", 1, 1, 5)
        const checkVersi = await ajaxGet('migrate/get_version');
        console.log('cekVersi', checkVersi);
        const needMigrate = checkVersi.current_version < checkVersi.latest_version
        arrBools.push(needMigrate);

        // check convert materi
        setProgress("Memeriksa jadwal mapel...", 1, 1, 5)
        const checkMapel = await ajaxGet('migrate/check_mapel');
        console.log('cekMapel', checkMapel);
        arrBools.push(checkMapel.need_convert);
        isNeedConvertMapel = checkMapel.need_convert;
        totalDataMapel = checkMapel.count

        // convert materi
        setProgress("Memeriksa materi...", 2, 2, 5)
        const checkMateri = await ajaxGet('migrate/check_materi');
        console.log('checkMateri', checkMateri);
        arrBools.push(checkMateri.need_convert)
        isNeedConvertMateri = checkMateri.need_convert
        totalDataMateri = checkMateri.count
        totalDataLog = checkMateri.count_log

        // check convert jadwalKbm
        setProgress("Memeriksa jadwal KBM...", 3, 3, 5)
        const checkJadwalKbm = await ajaxGet('migrate/check_jadwalkbm');
        console.log('checkJadwalKbm', checkJadwalKbm);
        arrBools.push(checkJadwalKbm.need_convert)
        isNeedConvertKbm = checkJadwalKbm.need_convert
        totalDataKbm = checkJadwalKbm.count

        //checkConvertCbt()
        setProgress("Memeriksa data ujian...", 4, 4, 5)
        const checkCbt = await ajaxGet('migrate/check_cbt')
        console.log('checkCbt', checkCbt);
        arrBools.push(checkCbt.need_convert)
        isNeedConvertCbt = checkCbt.need_convert
        totalDataCbt = checkCbt.count
        totalCbtNilai = checkCbt.count_nilai
        totalCbtDurasi = checkCbt.count_durasi
        totalCbtLog = checkCbt.count_log

        //checkConvertRekap()
        setProgress("Memeriksa data rekap...", 5, 5, 5)
        const checkRekap = await ajaxGet('migrate/check_rekap')
        console.log('checkRekap', checkRekap);
        arrBools.push(checkRekap.need_convert)
        isNeedConvertRekap = checkRekap.need_convert
        totalRekapJadwal = checkRekap.count
        totalRekapNilai = checkRekap.count_nilai

        totalSesi = checkRekap.count_sesi
        totalKelass = checkRekap.count_kelas
        totalBanks = checkRekap.count_bank
        totalJadwal = checkRekap.count_jadwal
        totalSoals = checkRekap.count_soal
        totalPengawas = checkRekap.count_pengawas

        let html = ``;
        if (!arrBools.includes(true)) {
            html = `            <div class="col-12">
                                    <p>Aplikasi sudah terbaru, tidak perlu update.</p>
                                </div>`;
        } else {
            html = `            <div class="col-12">
                                    <p>Aplikasi harus diupdate untuk menjalankan fitur terbaru</p>
                                    <ul>
                                        <li>
                                            <strong>BACKUP DATABASE SECARA MANUAL terlebih dahulu!</strong>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-danger float-right" id="update-aplikasi">Update Aplikasi
                                    </button>
                                </div>`;
        }
        $('#update-info').html(html)

        $('#update-aplikasi').on('click', function () {
            alertDialog()
        })
        swal.close()
    }

    async function ajaxPost(url, formData) {
        return new Promise((resolve, reject) => {
            setTimeout(function () {
                $.ajax({
                    url: base_url + url,
                    method: "POST",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function (respon) {
                        if (respon.success) {
                            resolve(respon); // Resolusi Promise ketika sukses
                        } else {
                            reject(respon.message); // Rejeksi Promise ketika ada error
                        }
                    },
                    error: function (xhr, status, error) {
                        reject(error); // Rejeksi Promise ketika ada error
                    }
                });
            }, 500)
        });
    }

    async function ajaxGet(url) {
        return new Promise((resolve, reject) => {
            setTimeout(function () {
                $.ajax({
                    url: base_url + url,
                    type: "GET",
                    success: function (respon) {
                        resolve(respon); // Resolusi Promise ketika sukses
                    },
                    error: function (xhr, status, error) {
                        reject(error); // Rejeksi Promise ketika ada error
                    }
                });
            }, 500)
        });
    }

    function alertDialog() {
        swal.fire({
            title: "Update Aplikasi",
            html: "Database akan diperbarui, BACKUP sebelum anda melakukan update!<br />Tindakan ini tidak bisa dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Update!"
        }).then(result => {
            if (result.value) {
                swal.fire({
                    text: "Silahkan tunggu....",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        swal.showLoading();
                    }
                });
                runAntrianUpdate()
            }
        });
    }

    function setProgress(msg, countTotal, countSub, subtotal) {
        const total = 27;
        const $status = $('.swal2-content');

        const percentTotal = Math.round((countTotal / total) * 100)
        const percentSub = countSub > 0 ? Math.round((countSub / subtotal) * 100) : 0
        if (countSub) {
            $status.html("Update... " + percentTotal + "%" + "<br />" + msg + percentSub + "% <small>(" + countSub + " dari " + subtotal + ")</small>")
        } else {
            $status.html("Update... " + percentTotal + "%" + "<br />" + msg)
        }
    }

    async function runAntrianUpdate() {
        try {
            // before migrate
            // cek tabel kelas_jadwal_mapel id lebih 10 digit
            // cek tabel kelas_jadwal_kbm id lebih 10 digit
            if (isNeedConvertKbm || isNeedConvertMapel) {
                const checkMapel = await ajaxGet('migrate/check_mapel');
                setProgress("Mengambil jadwal KBM...", 6)
                await getJadwalMapel(checkMapel.count);
                // convert jadwal
                const jadwalKbm = await ajaxGet('migrate/get_jadwalkbm');
                //console.log('jadwalKbm', jadwalKbm);
                await convertKbm(jadwalKbm)
                // empty tabel
                await ajaxGet('migrate/empty_table?table=kelas_jadwal_mapel');
                await ajaxGet('migrate/empty_table?table=kelas_jadwal_kbm');
            }

            // cek tabel kelas_jadwal_materi id lebih 10 digit
            // cek tabel log_materi id lebih 10 digit
            if (isNeedConvertMateri) {
                // get materi
                setProgress("Mengambil jadwal Materi...", 7)
                if (totalDataMateri > 0) await getJadwalMateri(totalDataMateri);
                if (totalDataLog > 0) await getLogMateri(totalDataLog);
                await convertMateri();
                // empty tabel
                await ajaxGet('migrate/empty_table?table=kelas_jadwal_materi');
                await ajaxGet('migrate/empty_table?table=log_materi');
            }

            // cek versi
            const checkVersi = await ajaxGet('migrate/get_version');
            console.log('cekVersi', checkVersi);
            const needMigrate = checkVersi.current_version < checkVersi.latest_version
            // migration db
            if (needMigrate) {
                await ajaxGet('migrate/updateApp')
            }

            // after migrations
            if (isNeedConvertMapel) {
                // post converted mapel
                setProgress("Menyimpan jadwal KBM...", 8)
                await postConvertedMapel(totalConvertedMapel) //finished
            }

            if (isNeedConvertKbm) {
                // post converted jadwal
                setProgress("Menyimpan jadwal KBM...", 9)
                await updateKBM(totalDataKbm) //finished
            }

            if (isNeedConvertMateri) {
                // post convert materi
                setProgress("Menyimpan jadwal Materi...", 10)
                await postConvertedMateri(totalDataMateri) //finished
                await postConvertedLogs(totalDataLog) //finished
            }

            if (isNeedConvertCbt || isNeedConvertRekap) {
                setProgress("Mengambil data Ujian...", 11)
                //convertCbt()
                await getSoalSiswa(totalDataCbt);
                await getTable(table.cbtDurasi, totalCbtDurasi)
                await getTable(table.cbtNilai, totalCbtNilai)
                await getTable(table.cbtLog, totalCbtLog)

                const result = await getAllItemsAndGroupByJadwalAndSiswa()
                //console.log('process', result);
                await convertSoalSiswa(result)

                setProgress("Menyimpan data Ujian...", 12)
                await postConvertedSoalSiswa() //finished
            }

            if (isNeedConvertRekap) {
                setProgress("Mengambil data Rekap Ujian...", 13)
                const tp = await ajaxGet('migrate/get_table?table='+table.masterTp);
                clearItems(table.masterTp)
                addItems(table.masterTp, tp.data)

                setProgress("Mengambil data Rekap Ujian...", 14)
                const smt = await ajaxGet('migrate/get_table?table='+table.masterSmt);
                clearItems(table.masterSmt)
                addItems(table.masterSmt, smt.data)

                setProgress("Mengambil data Rekap Ujian...", 15)
                const mapel = await ajaxGet('migrate/get_table?table='+table.masterMapel);
                clearItems(table.masterMapel)
                addItems(table.masterMapel, mapel.data)

                setProgress("Mengambil data Rekap Ujian...", 16)
                const guru = await ajaxGet('migrate/get_table?table='+table.masterGuru);
                clearItems(table.masterGuru)
                addItems(table.masterGuru, guru.data)

                setProgress("Mengambil data Rekap Ujian...", 17)
                const ruang = await ajaxGet('migrate/get_table?table='+table.cbtRuang);
                clearItems(table.cbtRuang)
                addItems(table.cbtRuang, ruang.data)

                setProgress("Mengambil data Rekap Ujian...", 18)
                const sesi = await ajaxGet('migrate/get_table?table='+table.cbtSesi);
                clearItems(table.cbtSesi)
                addItems(table.cbtSesi, sesi.data)

                setProgress("Mengambil data Rekap Ujian...", 19)
                await getTable(table.sesiSiswa, totalSesi)
                await getTable(table.masterKelas, totalKelass)
                await getTable(table.cbtBankSoal, totalBanks)
                await getTable(table.cbtJadwal, totalJadwal)
                await getTable(table.cbtSoal, totalSoals)
                await getTable(table.cbtPengawas, totalPengawas)

                setProgress("Menyimpan Rekap Ujian...", 20)
                await getRekapJadwal(totalRekapJadwal)
                setProgress("Menyimpan Rekap Ujian...", 21)
                await getRekapNilai(totalRekapNilai)

                //convertRekap()
                setProgress("Menyimpan Rekap Ujian...", 22)
                await convertRekapJadwal()
                setProgress("Menyimpan Rekap Ujian...", 23)
                await convertRekapNilai()

                setProgress("Menyimpan Rekap Ujian...", 24)
                await postConvertedRekapJadwal()
                setProgress("Menyimpan Rekap Ujian...", 25)
                await postConvertedRekapNilai()
            }
            swal.fire({
                title: "Selesai",
                text: 'Berhasil memperbarui aplikasi',
                icon: "success",
                allowEscapeKey: false,
                allowOutsideClick: false
            }).then(result => {
                if (result.value) {
                    deleteDatabase()
                    window.location.href = base_url;
                }
            });
        } catch (error) {
            console.log("Error occurred: ", error);
            console.log("Execution stopped.");
            swal.fire({
                title: "Gagal",
                text: 'Gagal mengambil data',
                icon: "error"
            });
        }
    }

    async function getTable(table, count) {
        const pages = Math.ceil(count / limitAntrian)
        console.log('pages', pages)

        let totalSub = 0;
        clearItems(table)
        for (let i = 0; i < pages; i++) {
            let pagePost = i+1;
            const result = await ajaxGet('migrate/get_table?page='+ pagePost + '&limit='+limitAntrian + '&table='+table)
            totalSub += result.data.length
            addItems(table, result.data)
        }
    }

    async function getJadwalMapel(count) {
        const pages = Math.ceil(count / limitAntrian)
        console.log('pages', pages)

        let totalSub = 0;
        clearItems(table.kbmMapel)
        for (let i = 0; i < pages; i++) {
            let pagePost = i+1;
            const result = await ajaxGet('migrate/get_mapel?page='+ pagePost + '&limit='+limitAntrian)
            totalSub += result.data.length
            setProgress("Mengambil jadwal KBM...", 6, totalSub, count)
            addItems(table.kbmMapel, result.data)
        }
    }

    async function getJadwalMateri(count) {
        const pages = Math.ceil(count / limitAntrian)
        console.log('pages', pages)

        let totalSub = 0;
        clearItems(table.kbmMateri)
        for (let i = 0; i < pages; i++) {
            let pagePost = i+1;
            const result = await ajaxGet('migrate/get_materi?page='+ pagePost + '&limit='+limitAntrian)
            totalSub += result.data.length
            setProgress("Mengambil jadwal Materi...", 7, totalSub, count)
            addItems(table.kbmMateri, result.data)
        }
    }

    async function getLogMateri(count) {
        const pages = Math.ceil(count / limitAntrian)
        console.log('pages', pages)

        let totalSub = 0;
        clearItems(table.kbmLog)
        for (let i = 0; i < pages; i++) {
            let pagePost = i+1;
            const result = await ajaxGet('migrate/get_log?page='+ pagePost + '&limit='+limitAntrian)
            totalSub += result.data.length
            setProgress("Mengambil Nilai Materi...", 7, totalSub, count)
            addItems(table.kbmLog, result.data)
        }
    }

    let totalMateri = 0
    let totalLogs = 0
    async function convertMateri() {
        const materis = await getItems(table.kbmMateri);
        const logs = await getItems(table.kbmLog);

        // Jika ada data log, tambahkan id_log
        if (logs.length > 0) {
            logs.forEach((log, keyl) => {
                log.id_log = keyl + 1;
            });
        }

        // Jika ada data materi, lakukan proses update id_kjm
        if (materis.length > 0) {
            for (let [key, materi] of materis.entries()) {
                const id_kjm = key + 1;

                // Cari log yang sesuai dengan id_materi
                const idx_log = logs.findIndex(log => log.id_materi === materi.id_kjm);

                // Jika log ditemukan, update id_materi
                if (idx_log !== -1) {
                    logs[idx_log].id_materi = id_kjm;
                }

                // Update id_kjm pada data materi
                materi.id_kjm = id_kjm;
            }

            // Kosongkan tabel kelas_jadwal_materi dan log_materi
            clearItems(table.kbmMateri)
            clearItems(table.kbmLog)

            // Insert ulang data ke tabel log_materi dan kelas_jadwal_materi
            addItems(table.kbmMateri, materis)
            addItems(table.kbmLog, logs)
            totalMateri = materis.length
            totalLogs = logs.length
        }
    }

    async function postConvertedMateri(totalCount) {
        const pages = Math.ceil(totalCount / limitPost)
        console.log('pagesMateri', pages)

        let totalSub = 0;
        for (let i = 0; i < pages; i++) {
            let pagePost = i+1;
            const materies = await getTablePagination(table.kbmMateri, pagePost, limitPost);
            let formData = new FormData($('#post_converted')[0]);
            for (let i=0; i < materies.length; i++) {
                const ids = {
                    id_kelas: materies[i].id_kelas || 0,
                    id_mapel: materies[i].id_mapel || 0,
                    id_materi: materies[i].id_materi || 0,
                    id_tp: materies[i].id_tp || 0,
                    id_smt: materies[i].id_smt || 0
                }

                formData.append(`materi[${i}][ids]`, JSON.stringify(ids));
                formData.append(`materi[${i}][jadwal_materi]`, materies[i].jadwal_materi || 0);
                formData.append(`materi[${i}][jenis]`, materies[i].jenis || 0);
            }
            //const totalDataCount = Array.from(formData.values());
            //console.log('convertMateri', totalDataCount.length, totalDataCount)
            const result = await ajaxPost('migrate/convert_materi', formData)
            totalSub += materies.length
            setProgress("Menyimpan jadwal Materi...", 10, totalSub, totalCount)
        }
    }

    async function postConvertedLogs(totalCount) {
        const pages = Math.ceil(totalCount / limitPost)
        console.log('pagesLog', pages)

        let totalSub = 0;
        for (let i = 0; i < pages; i++) {
            let pagePost = i+1;
            const materies = await getTablePagination(table.kbmLog, pagePost, limitPost);
            let formData = new FormData($('#post_converted')[0]);
            for (let i=0; i < materies.length; i++) {
                const ids = {
                    id_mapel: materies[i].id_mapel || 0,
                    id_materi: materies[i].id_materi || 0,
                    id_siswa: materies[i].id_siswa || 0,
                    jam_ke: materies[i].jam_ke || 0,
                    log_type: materies[i].log_type || 0,
                    nilai: materies[i].nilai || 0
                }
                formData.append(`log[${i}][ids]`, JSON.stringify(ids));
                formData.append(`log[${i}][address]`, materies[i].address || 0);
                formData.append(`log[${i}][agent]`, materies[i].agent || 0);
                formData.append(`log[${i}][catatan]`, materies[i].catatan || 0);
                formData.append(`log[${i}][finish_time]`, materies[i].finish_time || 0);
                formData.append(`log[${i}][log_desc]`, materies[i].log_desc || 0);
                formData.append(`log[${i}][log_time]`, materies[i].log_time || 0);
                formData.append(`log[${i}][text]`, materies[i].text || 0);
                formData.append(`log[${i}][device]`, materies[i].device || 0);
            }
            //const totalDataCount = Array.from(formData.values());
            //console.log('convertLog',totalDataCount.length, totalDataCount)
            const result = await ajaxPost('migrate/convert_log', formData)
            totalSub += materies.length
            setProgress("Menyimpan hasil Materi...", 10, totalSub, totalCount)
        }
    }

    // update jadwal mapel & KBM
    async function convertKbm(dataConvert) {
        let arrDataJadwal = [];
        let arrKBM = [];

        const allMapel = await getAllJadwalMapel(table.kbmMapel);
        let result = {};
        allMapel.forEach(function (item) {
            const { id_tp, id_smt, id_hari, id_kelas } = item;

            if (!result[id_tp]) {
                result[id_tp] = {};
            }

            if (!result[id_tp][id_smt]) {
                result[id_tp][id_smt] = {};
            }

            if (!result[id_tp][id_smt][id_hari]) {
                result[id_tp][id_smt][id_hari] = {};
            }

            if (!result[id_tp][id_smt][id_hari][id_kelas]) {
                result[id_tp][id_smt][id_hari][id_kelas] = [];
            }

            result[id_tp][id_smt][id_hari][id_kelas].push(item);
        });

        if (Object.keys(dataConvert.jadwal).length > 0) {
            dataConvert.tps.forEach(tp => {
                dataConvert.smts.forEach(smt => {
                    let kelass = dataConvert.kelas[tp.id_tp]?.[smt.id_smt] || [];
                    let jadk = dataConvert.jadwal[tp.id_tp]?.[smt.id_smt] || [];
                    //let jadwal_mapel = dataConvert.mapel[tp.id_tp]?.[smt.id_smt] || [];
                    let jadwal_mapel = result[tp.id_tp]?.[smt.id_smt] || [];
                    if (Object.keys(jadk).length > 0) {
                        const forResult = createNewJadwal(tp.id_tp, smt.id_smt, jadk, jadwal_mapel, kelass)
                        const flattenedData = flattenJSON(forResult.mapel);
                        arrDataJadwal = arrDataJadwal.concat(flattenedData)
                        for (const kbmKey in forResult.kbm) {
                            arrKBM.push(forResult.kbm[kbmKey])
                        }
                    }
                });
            });
        }
        //console.log('result', arrDataJadwal)
        totalConvertedMapel = arrDataJadwal.length
        clearItems(table.kbmMapel)
        addItems(table.kbmMapel, arrDataJadwal)

        clearItems(table.kbmJadwal)
        addItems(table.kbmJadwal, arrKBM)
    }

    function createNewJadwal(tp, smt, jadk, jadwal_mapels, all_kelas) {
        const jadwalDetail = [];
        for (let idKelas in jadk) {
            let kelas = jadk[idKelas] || {};
            let istirahatData = kelas.istirahat || [];
            let istirahatDurasi = {};
            let istirahatJamKe = [];

            istirahatData.forEach(istirahat => {
                istirahatJamKe.push(istirahat.ist);
                istirahatDurasi[istirahat.ist] = istirahat.dur;
            });

            let jamMulai = new Date(`2000-01-01T${kelas.kbm_jam_mulai || "00:00"}:00Z`);
            let jamSampai = new Date(jamMulai.getTime());
            let jadwalMapel = {};
            let jadwalIstirahat = {};
            let kelasSampai = {};

            for (let i = 0; i <= 50; i++) {
                let jamKe = (i + 1).toString();
                if (istirahatJamKe.includes(jamKe)) {
                    try {
                        jamSampai.setMinutes(jamSampai.getMinutes() + Number(istirahatDurasi[jamKe] || 0));
                        jadwalIstirahat[jamKe] = jamKe;
                        jadwalMapel[jamKe] = {
                            id_tp: tp,
                            id_smt: smt,
                            dari: jamMulai.toISOString().substring(11, 16),
                            sampai: jamSampai.toISOString().substring(11, 16),
                            id_mapel: '-1',
                            jam_ke: jamKe
                        };
                        kelasSampai[jamKe] = jamSampai.toISOString().substring(11, 16);
                        jamMulai.setMinutes(jamMulai.getMinutes() + Number(istirahatDurasi[jamKe] || 0));
                    } catch (e) {
                        console.error("Error processing rest period:", e);
                    }
                } else {
                    try {
                        jamSampai.setMinutes(jamSampai.getMinutes() + Number(kelas.kbm_jam_pel || 0));
                        jadwalMapel[jamKe] = {
                            dari: jamMulai.toISOString().substring(11, 16),
                            sampai: jamSampai.toISOString().substring(11, 16)
                        };
                        kelasSampai[jamKe] = jamSampai.toISOString().substring(11, 16);
                        jamMulai.setMinutes(jamMulai.getMinutes() + Number(kelas.kbm_jam_pel || 0));
                    } catch (e) {
                        console.error("Error processing period:", e);
                    }
                }
            }

            let details = {};
            for (const idHari in jadwal_mapels || {}) {
                const jadwal_mapel = jadwal_mapels[idHari] || [];
                let jadwalKelas = {};
                const kelasKeys = Object.keys(all_kelas || {});
                for (const key of kelasKeys) {
                    jadwalKelas[key] = {};
                }

                for (const mapelKey in jadwal_mapel) {
                    const mapel = jadwal_mapel[mapelKey] || [];
                    mapel.forEach(jadwal => {
                        if (jadwal.id_kelas === idKelas && jadwalMapel[jadwal.jam_ke]) {
                            let formattedMulai = jadwalMapel[jadwal.jam_ke]?.dari || '';
                            details[formattedMulai] = {
                                id_jadwal: jadwal.id_jadwal,
                                id_tp: tp,
                                id_smt: smt,
                                dari: jadwalMapel[jadwal.jam_ke]?.dari || '',
                                sampai: jadwalMapel[jadwal.jam_ke]?.sampai || '',
                                id_kelas: jadwal.id_kelas,
                                id_hari: jadwal.id_hari,
                                jam_ke: jadwal.jam_ke,
                                id_mapel: jadwal.id_mapel
                            };
                        }
                    });
                }

                Object.keys(details).forEach(det => {
                    Object.values(jadwalIstirahat).forEach(ist => {
                        if (jadwalMapel[ist]) {
                            jadwalMapel[ist].id_hari = details[det]?.id_hari || '';
                            jadwalMapel[ist].id_kelas = details[det]?.id_kelas || '';
                            details[jadwalMapel[ist].dari || ''] = jadwalMapel[ist];
                        }
                    });
                });

                jadwalKelas[idKelas] = JSON.parse(JSON.stringify(details));
                jadwalDetail.push(jadwalKelas);
            }

            kelas.kbm_jam_selesai = kelasSampai[(Number(kelas.kbm_jml_mapel_hari || 0) + 2).toString()] || '';
            kelas.istirahat = istirahatData
        }

        return { mapel: jadwalDetail, kbm: jadk };
    }

    function flattenJSON(data) {
        return data.flatMap(dayData =>
            Object.entries(dayData).flatMap(([classId, classData]) =>
                Object.entries(classData || {}).map(([time, timeData]) => ({
                    id_kelas: classId,
                    dari: time,
                    ...timeData
                }))
            )
        );
    }

    async function postConvertedMapel(totalCount) {
        const pages = Math.ceil(totalCount / limitPost)
        console.log('pages', pages)

        let totalSub = 0;
        for (let i = 0; i < pages; i++) {
            let pagePost = i+1;
            const jadwals = await getJadwalKbmWithPagination(pagePost, limitPost);
            let formData = new FormData($('#post_converted')[0]);
            let id = 0;
            for (const detail of jadwals) {
                const ids = {
                    id_mapel: detail.id_mapel || 0,
                    //id_jadwal: detail.id_jadwal || 0,
                    id_kelas: detail.id_kelas || 0,
                    id_tp: detail.id_tp || 0,
                    id_smt: detail.id_smt || 0,
                    id_hari: detail.id_hari || 0,
                    jam_ke: detail.jam_ke || 0,
                }
                formData.append(`mapel[${id}][ids]`, JSON.stringify(ids))
                formData.append(`mapel[${id}][dari]`, detail.dari || 0)
                formData.append(`mapel[${id}][sampai]`, detail.sampai || 0)
                id ++
            }
            //const totalDataCount = Array.from(formData.values()).length;
            //console.log('convertMapel', totalDataCount)
            const result = await ajaxPost('migrate/convert_mapel', formData)
            totalSub += jadwals.length
            setProgress("Menyimpan jadwal KBM...", 8, totalSub, totalCount)
        }
    }

    function getJadwalKbmWithPagination(pageNumber, pageSize) {
        return new Promise((resolve, reject) => {
            const transaction = db.transaction([table.kbmMapel], "readonly");
            const objectStore = transaction.objectStore(table.kbmMapel);

            const students = [];
            let offset = (pageNumber - 1) * pageSize; // Menghitung offset berdasarkan halaman
            let count = 0; // Menghitung item yang sudah dilewati

            const cursorRequest = objectStore.openCursor(); // Membuka kursusor untuk traversal

            cursorRequest.onsuccess = function(event) {
                const cursor = event.target.result;

                if (cursor) {
                    if (count >= offset && students.length < pageSize) {
                        students.push(cursor.value); // Tambahkan data ke array jika sesuai dengan page size
                    }
                    count++;

                    // Lanjutkan ke item berikutnya jika belum mencapai jumlah yang diinginkan
                    if (students.length < pageSize) {
                        cursor.continue();
                    } else {
                        resolve(students); // Mengembalikan hasil jika page size terpenuhi
                    }
                } else {
                    resolve(students); // Mengembalikan hasil jika kursusor telah selesai
                }
            };

            cursorRequest.onerror = function(event) {
                reject("Gagal mengambil data: " + event.target.errorCode);
            };
        });
    }

    function getTablePagination(table, pageNumber, pageSize) {
        return new Promise((resolve, reject) => {
            const transaction = db.transaction([table], "readonly");
            const objectStore = transaction.objectStore(table);

            const students = [];
            let offset = (pageNumber - 1) * pageSize; // Menghitung offset berdasarkan halaman
            let count = 0; // Menghitung item yang sudah dilewati

            const cursorRequest = objectStore.openCursor(); // Membuka kursusor untuk traversal

            cursorRequest.onsuccess = function(event) {
                const cursor = event.target.result;

                if (cursor) {
                    if (count >= offset && students.length < pageSize) {
                        students.push(cursor.value); // Tambahkan data ke array jika sesuai dengan page size
                    }
                    count++;

                    // Lanjutkan ke item berikutnya jika belum mencapai jumlah yang diinginkan
                    if (students.length < pageSize) {
                        cursor.continue();
                    } else {
                        resolve(students); // Mengembalikan hasil jika page size terpenuhi
                    }
                } else {
                    resolve(students); // Mengembalikan hasil jika kursusor telah selesai
                }
            };

            cursorRequest.onerror = function(event) {
                reject("Gagal mengambil data: " + event.target.errorCode);
            };
        });
    }

    async function getAllJadwalMapel(tabel) {
        return new Promise((resolve, reject) => {
            const transaction = db.transaction([tabel], "readonly");
            const objectStore = transaction.objectStore(tabel);
            const request = objectStore.getAll(); // Mengambil semua data

            request.onsuccess = function(event) {
                const allItems = event.target.result;
                resolve(allItems);
            };

            request.onerror = function(event) {
                reject("Gagal membaca data: " + event.target.errorCode);
            };
        })
    }

    async function updateKBM(count) {
        const pages = Math.ceil(count / limitPost)
        console.log('pages', pages)

        let totalSub = 0;
        for (let i = 0; i < pages; i++) {
            let pagePost = i+1;
            const allJadwal = await getTablePagination(table.kbmJadwal, pagePost, limitPost);
            let formData = new FormData($('#post_converted')[0]);
            let id = 0
            for (const jad of allJadwal) {
                const ids = {
                    id_kelas: jad.id_kelas || 0,
                    id_tp: jad.id_tp || 0,
                    id_smt: jad.id_smt || 0,
                    kbm_jml_mapel_hari: jad.kbm_jml_mapel_hari || 0
                }

                formData.append(`kbm[${id}][ids]`, JSON.stringify(ids))
                formData.append(`kbm[${id}][kbm_jam_mulai]`, jad.kbm_jam_mulai || '')
                formData.append(`kbm[${id}][kbm_jam_selesai]`, jad.kbm_jam_selesai || '')
                formData.append(`kbm[${id}][kbm_jam_pel]`, jad.kbm_jam_pel || 0)
                formData.append(`kbm[${id}][istirahat]`, JSON.stringify(jad.istirahat || ''))
                formData.append(`kbm[${id}][libur]`, jad.libur || 0)
                id ++
            }
            setProgress("Menyimpan jadwal KBM...", 9, totalSub, count)
            await ajaxPost("migrate/convert_kbm", formData)
        }
    }

    async function getSoalSiswa(count) {
        const pages = Math.ceil(count / limitAntrian)
        console.log('pages', pages)

        let totalSub = 0;
        clearItems(table.soalSiswa)
        for (let i = 0; i < pages; i++) {
            let pagePost = i+1;
            const result = await ajaxGet('migrate/get_soal_siswa?page='+ pagePost + '&limit='+limitAntrian)
            totalSub += result.data.length
            setProgress("Mengambil data Ujian...", 11, totalSub, count)
            addItems(table.soalSiswa, result.data)
        }
    }

    async function getAllItemsAndGroupByJadwalAndSiswa() {
        return new Promise((resolve, reject) => {
            const transaction = db.transaction([table.soalSiswa], "readonly");
            const objectStore = transaction.objectStore(table.soalSiswa);
            const request = objectStore.getAll(); // Mengambil semua data

            request.onsuccess = function(event) {
                const allItems = event.target.result;

                // Mengelompokkan pertama berdasarkan id_jadwal
                const groupedByJadwal = allItems.reduce((acc, item) => {
                    const id_jadwal = item.id_jadwal;
                    const id_bank = item.id_bank;

                    if (!acc[id_jadwal]) {
                        acc[id_jadwal] = {
                            id_bank,   // Menyimpan id_bank di root untuk setiap id_jadwal
                            siswaGroup: {} // Objek untuk menyimpan data id_siswa
                        };
                    }
                    const id_siswa = item.id_siswa;
                    if (!acc[id_jadwal].siswaGroup[id_siswa]) {
                        acc[id_jadwal].siswaGroup[id_siswa] = [];
                    }

                    acc[id_jadwal].siswaGroup[id_siswa].push(item);
                    return acc;
                }, {});

                // Mengubah hasil menjadi array
                const resultArray = Object.entries(groupedByJadwal).map(([id_jadwal, value]) => {
                    return {
                        id_jadwal,
                        id_bank: value.id_bank, // id_bank berada di root object
                        siswaGroup: Object.entries(value.siswaGroup).map(([id_siswa, items]) => ({
                            id_siswa,
                            items
                        }))
                    };
                });

                resolve(resultArray); // Mengembalikan array data yang sudah dikelompokkan
            };

            request.onerror = function(event) {
                reject("Gagal membaca data: " + event.target.errorCode);
            };
        });
    }

    let totalSoalSiswa = 0;
    async function convertSoalSiswa(result) {
        const grouped = [];
        let lastJadwal = '0';

        const durasies = await getItems(table.cbtDurasi) || [];
        const nilais = await getItems(table.cbtNilai) || [];
        const logs = await getItems(table.cbtLog) || [];
        result?.forEach((value) => {
            const idJadwal = value.id_jadwal || '0';
            const idBank = value.id_bank || '0';

            if (lastJadwal !== idJadwal) {
                lastJadwal = idJadwal;
            }

            for (const siswa of value.siswaGroup || []) {
                const idSiswa = siswa.id_siswa || '0';
                const durasi = durasies.find(d => d.id_jadwal === idJadwal && d.id_siswa === idSiswa)
                const nilaiSiswa = nilais.find(d => d.id_jadwal === idJadwal && d.id_siswa === idSiswa)
                const log = logs.find(d => d.id_jadwal === idJadwal && d.id_siswa === idSiswa && d.log_type === '1')
                const logEnd = logs.find(d => d.id_jadwal === idJadwal && d.id_siswa === idSiswa && d.log_type === '2')

                let jawaban = [];
                let nilai = [];

                siswa.items?.forEach((soal) => {
                    jawaban[idJadwal] = jawaban[idJadwal] || [];
                    jawaban[idJadwal].push({
                        id_jadwal: idJadwal,
                        id_bank: idBank,
                        id_soal: soal.id_soal || '',
                        jenis: soal.jenis_soal || '',
                        no_soal_alias: soal.no_soal_alias || '',
                        opsi_alias_a: soal.opsi_alias_a || '',
                        opsi_alias_b: soal.opsi_alias_b || '',
                        opsi_alias_c: soal.opsi_alias_c || '',
                        opsi_alias_d: soal.opsi_alias_d || '',
                        opsi_alias_e: soal.opsi_alias_e || '',
                        jawaban_alias: soal.jawaban_alias || '',
                        jawaban_siswa: soal.jawaban_siswa || '',
                        jawaban_benar: soal.jawaban_benar || ''
                    });

                    nilai[idJadwal] = nilai[idJadwal] || [];
                    nilai[idJadwal].push({
                        id_jadwal: idJadwal,
                        id_bank: idBank,
                        id_soal: soal.id_soal || '',
                        point_soal: soal.point_soal || '',
                        nilai_koreksi: soal.nilai_koreksi || '',
                        nilai_otomatis: soal.nilai_otomatis || ''
                    });
                });

                let newSoalSiswa = {
                    id_siswa: idSiswa,
                    id_jadwal: idJadwal,
                    id_bank: idBank,
                    jawaban: JSON.stringify(jawaban[lastJadwal] || []),
                    nilai: JSON.stringify(nilai[lastJadwal] || []),
                    nilai_input: JSON.stringify(nilaiSiswa || {}),
                    reset_waktu: durasi.reset || 0,
                    reset_izin: log.reset || 0,
                }

                if (durasi.status || logEnd) newSoalSiswa['status'] = durasi.status || (logEnd.log_time ? 2 : '')
                if (durasi.mulai || log) newSoalSiswa['mulai'] = durasi.mulai || log.log_time
                if (durasi.selesai || logEnd) newSoalSiswa['selesai'] = durasi.selesai || logEnd.log_time
                if (durasi.lama_ujian) newSoalSiswa['lama_ujian'] = durasi.lama_ujian

                grouped.push(newSoalSiswa)
            }
        });

        totalSoalSiswa = grouped.length;
        clearItems(table.cbtSiswa);
        addItems(table.cbtSiswa, grouped);
    }

    async function postConvertedSoalSiswa() {
        const pages = Math.ceil(totalSoalSiswa / limitPost)
        console.log('pages', pages)

        let totalSub = 0;
        for (let i = 0; i < pages; i++) {
            let pagePost = i+1;
            const siswas = await getStudentsWithPagination(pagePost, limitPost)
            let formData = new FormData($('#post_converted')[0]);
            let id = 0
            for (const siswa of siswas) {
                const ids = {
                    id_siswa: siswa.id_siswa || 0,
                    id_jadwal: siswa.id_jadwal || 0,
                    id_bank: siswa.id_bank || 0,
                    status: siswa.status || 0,
                    reset_izin: siswa.reset_izin || 0,
                    reset_waktu: siswa.reset_waktu || 0
                }
                formData.append(`siswa[${id}][ids]`, JSON.stringify(ids))
                formData.append(`siswa[${id}][jawaban]`, siswa.jawaban || "[]")
                formData.append(`siswa[${id}][nilai]`, siswa.nilai || "[]")
                formData.append(`siswa[${id}][nilai_input]`, siswa.nilai_input || "{}")
                formData.append(`siswa[${id}][mulai]`, siswa.mulai || 0)
                formData.append(`siswa[${id}][selesai]`, siswa.selesai || 0)
                formData.append(`siswa[${id}][lama_ujian]`, siswa.lama_ujian || 0)
                id ++
            }
            //const totalDataCount = Array.from(formData.values()).length;
            //console.log('convertSoalSiswa', totalDataCount)
            const result = await ajaxPost('migrate/convert_soal_siswa', formData)
            totalSub += siswas.length
            setProgress("Menyimpan data Ujian...", 12, totalSub, totalSoalSiswa)
        }
    }

    function getStudentsWithPagination(pageNumber, pageSize) {
        return new Promise((resolve, reject) => {
            const transaction = db.transaction([table.cbtSiswa], "readonly");
            const objectStore = transaction.objectStore(table.cbtSiswa);

            const students = [];
            let offset = (pageNumber - 1) * pageSize; // Menghitung offset berdasarkan halaman
            let count = 0; // Menghitung item yang sudah dilewati

            const cursorRequest = objectStore.openCursor(); // Membuka kursusor untuk traversal

            cursorRequest.onsuccess = function(event) {
                const cursor = event.target.result;

                if (cursor) {
                    if (count >= offset && students.length < pageSize) {
                        students.push(cursor.value); // Tambahkan data ke array jika sesuai dengan page size
                    }
                    count++;

                    // Lanjutkan ke item berikutnya jika belum mencapai jumlah yang diinginkan
                    if (students.length < pageSize) {
                        cursor.continue();
                    } else {
                        resolve(students); // Mengembalikan hasil jika page size terpenuhi
                    }
                } else {
                    resolve(students); // Mengembalikan hasil jika kursusor telah selesai
                }
            };

            cursorRequest.onerror = function(event) {
                reject("Gagal mengambil data: " + event.target.errorCode);
            };
        });
    }

    async function getRekapJadwal(count) {
        const pages = Math.ceil(count / limitAntrian)
        console.log('pages', pages)

        let totalSub = 0;
        clearItems(table.rekapJadwal)
        for (let i = 0; i < pages; i++) {
            let pagePost = i+1;
            const result = await ajaxGet('migrate/get_rekap_jadwal?page='+ pagePost + '&limit='+limitAntrian)
            totalSub += result.data.length
            setProgress("Mengambil Rekap Jadwal Ujian...", 20, totalSub, count)
            addItems(table.rekapJadwal, result.data)
        }
    }

    async function getRekapNilai(count) {
        const pages = Math.ceil(count / limitAntrian)
        console.log('pages', pages)

        let totalSub = 0;
        clearItems(table.rekapNilai)
        for (let i = 0; i < pages; i++) {
            let pagePost = i+1;
            const result = await ajaxGet('migrate/get_rekap_nilai?page='+ pagePost + '&limit='+limitAntrian)
            totalSub += result.data.length
            setProgress("Mengambil Rekap Nilai...", 21, totalSub, count)
            addItems(table.rekapNilai, result.data)
        }
    }

    async function convertRekapJadwal() {
        const kelass = await getItems(table.masterKelas) || [];
        const gurus = await getItems(table.masterGuru) || [];
        const bankSoals = await getItems(table.cbtBankSoal) || [];
        const soals = await getItems(table.cbtSoal) || [];
        const jadwals = await getItems(table.cbtJadwal) || [];
        const pengawass = await getItems(table.cbtPengawas) || [];
        const rekapJadwals = await getItems(table.rekapJadwal) || [];

        let arrNewJadwal = [];
        rekapJadwals.forEach(function (rekap) {
            const idJadwal = rekap?.id_jadwal || '';
            const jadwal = jadwals.find(jad => jad.id_jadwal === idJadwal) || {};
            const idBank = rekap?.id_bank || '';
            const bankSoal = bankSoals.find(bank => bank.id_bank === idBank) || {};
            const soalBank = soals.filter(soal => soal.bank_id === idBank) || [];
            const soalPg = soalBank.filter(soal => soal.jenis === '1') || [];
            const soalPg2 = soalBank.filter(soal => soal.jenis === '2') || [];
            const soalJod = soalBank.filter(soal => soal.jenis === '3') || [];
            const soalIs = soalBank.filter(soal => soal.jenis === '4') || [];
            const soalEs = soalBank.filter(soal => soal.jenis === '5') || [];

            const arrKelas = [];
            (rekap?.bank_kelas || []).forEach(function (kelas) {
                const res = kelass.find(kls => kls.id_kelas === kelas.kelas_id) || null;
                arrKelas.push(res);
            });

            const pengawasSesi = pengawass.filter(pengawas => pengawas.id_jadwal === idJadwal) || [];
            const arrPengawas = [];
            pengawasSesi.forEach(function (pengawas) {
                const splitGuru = (pengawas?.id_guru || '').split(',') || [];
                let ruang = {
                    'id_ruang': pengawas?.id_ruang || '',
                    'id_sesi': pengawas?.id_sesi || '',
                    'pengawas': []
                };

                splitGuru.forEach(function (idGuru) {
                    const res = gurus.find(gr => gr.id_guru === idGuru) || null;
                    let resGuru = {};
                    resGuru[idGuru] = res ? (res.nama_guru || '-') : '-';
                    ruang['pengawas'].push(resGuru);
                });
                arrPengawas.push(ruang);
            });

            const newFields = {
                'id_rekap': rekap?.id_rekap || '',
                'id_tp': rekap?.id_tp || '',
                'tp': rekap?.tp || '',
                'id_smt': rekap?.id_smt || '',
                'smt': rekap?.smt || '',
                'id_jadwal': rekap?.id_jadwal || '',
                'jadwal': jadwal,
                'id_bank': rekap?.id_bank || '',
                'bank_soal': bankSoal,
                'kode_bank': rekap?.bank_kode || '',
                'kelas_bank': arrKelas,
                'level_bank': rekap?.bank_level || '',
                'id_jenis': rekap?.id_jenis || '',
                'kode_jenis': rekap?.kode_jenis || '',
                'id_mapel': rekap?.id_mapel || '',
                'kode_mapel': rekap?.kode || '',
                'nama_mapel': rekap?.nama_mapel || '',
                'tgl_mulai': rekap?.tgl_mulai || '',
                'tgl_selesai': rekap?.tgl_selesai || '',
                'soal_pg': soalPg,
                'soal_kompleks': soalPg2,
                'soal_jodohkan': soalJod,
                'soal_isian': soalIs,
                'soal_esai': soalEs,
                'id_guru': rekap?.id_guru || '',
                'nama_guru': rekap?.nama_guru || '',
                'pengawas': arrPengawas
            };

            arrNewJadwal.push(newFields);
        });
        //console.log('newJadwal', arrNewJadwal);
        clearItems(table.rekapJadwal);
        addItems(table.rekapJadwal, arrNewJadwal);
    }

    async function convertRekapNilai() {
        const soalSiswas = await getStudentsWithPagination(1, totalSoalSiswa)
        const rekapNilai = await getItems(table.rekapNilai) || [];
        const nilais = await getItems(table.cbtNilai) || [];
        const sesiSiswa = await getItems(table.sesiSiswa)
        const ruangs = await getItems(table.cbtRuang)
        const sesis = await getItems(table.cbtSesi)

        let arrNewNilai = [];
        rekapNilai.forEach(function (rekap) {
            // mengambil jawaban dari tabel cbt_siswa jika rekap_nilai tidak ada
            const soalSiswa = soalSiswas.find(s => s.id_jadwal === rekap.id_jadwal && s.id_bank === rekap.id_bank && s.id_siswa === rekap.id_siswa)
            // mengambil nilai dari tabel cbt_nilai
            const nilaiSiswa = nilais.find(d => d.id_jadwal === rekap.id_jadwal && d.id_siswa === rekap.id_siswa)
            // mengambil ruang dan sesi siswa
            const ruangSesiSiswa = sesiSiswa.find(r => r.siswa_id === rekap.id_siswa && r.tp_id === rekap.id_tp && r.smt_id === rekap.id_smt)
            const ruangSesi = {
                ruang: ruangs.find(ru => ru.id_ruang === ruangSesiSiswa?.ruang_id),
                sesi: sesis.find(se => se.id_sesi === ruangSesiSiswa?.sesi_id)
            }

            let newFields;
            if (soalSiswa ) {
                newFields = {
                    'id_rekap_nilai': rekap?.id_rekap_nilai || '',
                    'id_siswa': rekap?.id_siswa || '',
                    'nama_siswa': rekap?.nama_siswa || '',
                    'no_peserta': rekap?.no_peserta || '',
                    'id_tp': rekap?.id_tp || '',
                    'tp': rekap?.tp || '',
                    'id_smt': rekap?.id_smt || '',
                    'smt': rekap?.smt || '',
                    'id_jadwal': rekap?.id_jadwal || '',
                    'id_bank': rekap?.id_bank || '',
                    'id_kelas': rekap?.id_kelas || '',
                    'kelas': rekap?.kelas || '',
                    'ruang_sesi': JSON.stringify(ruangSesi),
                    'jawaban': soalSiswa?.jawaban || '[]',
                    'nilai': soalSiswa?.nilai || '[]',
                    'nilai_input': soalSiswa?.nilai_input || '{}',
                    'status': soalSiswa?.status || '',

                    'mulai': rekap?.mulai || '',
                    'selesai': rekap?.selesai || '',
                    'lama_ujian': soalSiswa?.lama_ujian || ''
                };
            } else {
                newFields = {
                    'id_rekap_nilai': rekap?.id_rekap_nilai || '',
                    'id_siswa': rekap?.id_siswa || '',
                    'nama_siswa': rekap?.nama_siswa || '',
                    'no_peserta': rekap?.no_peserta || '',
                    'id_tp': rekap?.id_tp || '',
                    'tp': rekap?.tp || '',
                    'id_smt': rekap?.id_smt || '',
                    'smt': rekap?.smt || '',
                    'id_jadwal': rekap?.id_jadwal || '',
                    'id_bank': rekap?.id_bank || '',
                    'id_kelas': rekap?.id_kelas || '',
                    'kelas': rekap?.kelas || '',
                    'ruang_sesi': JSON.stringify(ruangSesi),
                    'jawaban': '[]',
                    'nilai': '[]',  //fields `nilai` dari tabel `cbt_soal_siswa`
                    'nilai_input': JSON.stringify(nilaiSiswa), // dari tabel cbt_nilai berisi input nilai manual tanpa koreksi
                    'status': '2',

                    'mulai': rekap?.mulai || '',
                    'selesai': rekap?.selesai || '',
                    'lama_ujian': rekap?.durasi || ''
                };

                let arrjawaban = [];
                if (rekap.jawaban_pg) {
                    for (const jawabanPg of rekap.jawaban_pg) {
                        const jwbn = {
                            'id_jadwal': rekap?.id_jadwal || '',
                            'id_bank': rekap?.id_bank || '',
                            'id_soal': jawabanPg.no_soal || '',
                            'jenis': '1',
                            'jawaban_siswa': jawabanPg.jawab,
                            'jawaban_benar': rekap.jawaban_benar
                        }
                        arrjawaban.push(jwbn)
                    }
                }

                if (rekap.soal_kompleks.jawaban.length > 0) {
                    for (const jawabanPg2 of rekap.soal_kompleks.jawaban) {
                        const jwbn = {
                            'id_jadwal': rekap?.id_jadwal || '',
                            'id_bank': rekap?.id_bank || '',
                            'id_soal': jawabanPg2.no_soal || '',
                            'jenis': '2',
                            'jawaban_siswa': jawabanPg2.jawab,
                            'jawaban_benar': rekap.jawaban_benar
                        }
                        arrjawaban.push(jwbn)
                    }
                }

                if (rekap.soal_jodohkan.jawaban.length > 0) {
                    for (const jawabanJod of rekap.soal_jodohkan.jawaban) {
                        const jwbn = {
                            'id_jadwal': rekap?.id_jadwal || '',
                            'id_bank': rekap?.id_bank || '',
                            'id_soal': jawabanJod.no_soal || '',
                            'jenis': '3',
                            'jawaban_siswa': jawabanJod.jawab,
                            'jawaban_benar': rekap.jawaban_benar
                        }
                        arrjawaban.push(jwbn)
                    }
                }

                if (rekap.soal_isian.jawaban.length > 0) {
                    for (const jawabanIs of rekap.soal_isian.jawaban) {
                        const jwbn = {
                            'id_jadwal': rekap?.id_jadwal || '',
                            'id_bank': rekap?.id_bank || '',
                            'id_soal': jawabanIs.no_soal || '',
                            'jenis': '4',
                            'jawaban_siswa': jawabanIs.jawab,
                            'jawaban_benar': rekap.jawaban_benar
                        }
                        arrjawaban.push(jwbn)
                    }
                }

                if (rekap.soal_essai.jawaban.length > 0) {
                    for (const jawabanEs of rekap.soal_essai.jawaban) {
                        const jwbn = {
                            'id_jadwal': rekap?.id_jadwal || '',
                            'id_bank': rekap?.id_bank || '',
                            'id_soal': jawabanEs.no_soal || '',
                            'jenis': '5',
                            'jawaban_siswa': jawabanEs.jawab,
                            'jawaban_benar': rekap.jawaban_benar
                        }
                        arrjawaban.push(jwbn)
                    }
                }

                newFields.jawaban = JSON.stringify(arrjawaban)
            }

            arrNewNilai.push(newFields);
        });
        //console.log('nilai', arrNewNilai);
        clearItems(table.rekapNilai);
        addItems(table.rekapNilai, arrNewNilai);
    }

    async function postConvertedRekapJadwal() {
        const pages = Math.ceil(totalRekapJadwal / limitPost)
        console.log('pages', pages)

        let totalSub = 0;
        for (let i = 0; i < pages; i++) {
            let pagePost = i+1;
            const jadwals = await getTablePagination(table.rekapJadwal, pagePost, limitPost)
            let formData = new FormData($('#post_converted')[0]);
            let index = 0;
            for (const jadwal of jadwals) {
                const ids = {
                    id_tp: jadwal.id_tp || 0,
                    tp: jadwal.tp || 0,
                    id_smt: jadwal.id_smt || 0,
                    smt: jadwal.smt || 0,
                    id_jadwal: jadwal.id_jadwal || 0,
                    id_bank: jadwal.id_bank || 0,
                    level_bank: jadwal.level_bank || 0,
                    id_jenis: jadwal.id_jenis || 0,
                    id_mapel: jadwal.id_mapel || 0,
                    id_guru: jadwal.id_guru || 0,
                    kode_bank: jadwal.kode_bank || 0,
                    kode_jenis: jadwal.kode_jenis || 0,
                    kode_mapel: jadwal.kode_mapel || 0,
                    nama_mapel: jadwal.nama_mapel || 0,
                    tgl_mulai: jadwal.tgl_mulai || 0,
                    tgl_selesai: jadwal.tgl_selesai || 0,
                    nama_guru: jadwal.nama_guru || 0,
                }

                formData.append(`jadwal[${index}][ids]`, JSON.stringify(ids))
                formData.append(`jadwal[${index}][jadwal]`, JSON.stringify(jadwal.jadwal))
                formData.append(`jadwal[${index}][bank_soal]`, JSON.stringify(jadwal.bank_soal))
                formData.append(`jadwal[${index}][kelas_bank]`, JSON.stringify(jadwal.kelas_bank))
                formData.append(`jadwal[${index}][soal_pg]`, JSON.stringify(jadwal.soal_pg))
                formData.append(`jadwal[${index}][soal_kompleks]`, JSON.stringify(jadwal.soal_kompleks))
                formData.append(`jadwal[${index}][soal_jodohkan]`, JSON.stringify(jadwal.soal_jodohkan))
                formData.append(`jadwal[${index}][soal_isian]`, JSON.stringify(jadwal.soal_isian))
                formData.append(`jadwal[${index}][soal_esai]`, JSON.stringify(jadwal.soal_esai))
                formData.append(`jadwal[${index}][pengawas]`, JSON.stringify(jadwal.pengawas))
                index++;
            }
            //const totalDataCount = Array.from(formData.values()).length;
            //console.log('convertRekapJadwal', totalDataCount)
            const result = await ajaxPost('migrate/convert_rekap_jadwal', formData)
            totalSub += jadwals.length
            setProgress("Menyimpan Rekap Jadwal Ujian...", 24, totalSub, totalRekapJadwal)
        }
    }

    async function postConvertedRekapNilai() {
        const pages = Math.ceil(totalRekapNilai / limitPost)
        console.log('pages', pages)

        let totalSub = 0;
        for (let i = 0; i < pages; i++) {
            let pagePost = i+1;
            const jadwals = await getTablePagination(table.rekapNilai, pagePost, limitPost)
            let formData = new FormData($('#post_converted')[0]);
            let index = 0;
            for (const rekap of jadwals) {
                const ids = {
                    id_tp: rekap.id_tp || 0,
                    tp: rekap.tp || '',
                    id_smt: rekap.id_smt || 0,
                    smt: rekap.smt || '',
                    id_siswa: rekap.id_siswa || 0,
                    id_jadwal: rekap.id_jadwal || 0,
                    id_bank: rekap.id_bank || 0,
                    id_kelas: rekap.id_kelas || 0,
                    kelas: rekap.kelas || '',
                    mulai: rekap.mulai || '',
                    selesai: rekap.selesai || '',
                    lama_ujian: rekap.lama_ujian || 0,
                    status: rekap.status || 0,
                    nama_siswa: rekap.nama_siswa || '',
                    no_peserta: rekap.no_peserta || '',
                };
                formData.append(`nilai[${index}][ids]`, JSON.stringify(ids))
                formData.append(`nilai[${index}][jawaban]`, rekap.jawaban || '[]')
                formData.append(`nilai[${index}][nilai]`, rekap.nilai || '[]')
                formData.append(`nilai[${index}][ruang_sesi]`, rekap.ruang_sesi)
                formData.append(`nilai[${index}][nilai_input]`, rekap.nilai_input || '{}')
                index++;
            }
            //const totalDataCount = Array.from(formData.values()).length;
            //console.log('convertRekapNilai', totalDataCount)
            const result = await ajaxPost('migrate/convert_rekap_nilai', formData)
            totalSub += jadwals.length
            const totalData = totalSub === totalRekapNilai ? 27 : 25
            setProgress("Menyimpan Rekap Nilai...", totalData, totalSub, totalRekapNilai)
        }
    }

    async function getItems(table) {
        return new Promise((resolve, reject) => {
            const transaction = db.transaction([table], "readonly");
            const objectStore = transaction.objectStore(table);
            const request = objectStore.getAll(); // Mengambil semua data

            request.onsuccess = function(event) {
                const allItems = event.target.result;
                resolve(allItems);
            };

            request.onerror = function(event) {
                reject("Gagal membaca data: " + event.target.errorCode);
            };
        })
    }

    function addItems(table, items) {
        //console.log('add items to '+table)
        const transaction = db.transaction([table], "readwrite");
        const objectStore = transaction.objectStore(table);

        items.forEach(item => {
            const request = objectStore.add(item);

            request.onsuccess = function(event) {};

            request.onerror = function(event) {
                console.log(`Gagal menambahkan data: ${item}`, event);
            };
        });

        transaction.oncomplete = function() {};

        transaction.onerror = function(event) {
            console.log("Terjadi kesalahan pada transaksi", event);
        };
    }

    function clearItems(table) {
        const transaction = db.transaction([table], "readwrite");
        const objectStore = transaction.objectStore(table);
        const request = objectStore.clear(); // Menghapus semua data

        request.onsuccess = function(event) {};

        request.onerror = function(event) {
            console.log("Gagal menghapus semua data", event);
        };
    }

    function is_Array(a) {
        return (!!a) && (a.constructor === Array);
    }

    function is_Object(a) {
        return (!!a) && (a.constructor === Object);
    }

</script>
