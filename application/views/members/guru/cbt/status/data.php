<?php
/**
 * Created by IntelliJ IDEA.
 * User: multazam
 * Date: 07/07/20
 * Time: 17:20
 */
?>

<div class="content-wrapper bg-white">
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
                    <button class="card-tools btn btn-default btn-sm mr-2 btn-toggle" data-toggle="modal"
                            data-target="#infoModal"><i class="fas fa-info-circle mr-1"></i> Info Error
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-center">TOKEN</span>
                                </div>
                                <input id="token-input" class="form-control text-bold text-center" readonly="readonly"/>
                                <div class="input-group-append" id="kolom-kanan">
                                    <span id="interval" class="input-group-text text-xs d-none">-- : -- : --</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-light mb-4">
                        <div class="table-responsive" id="table-hari-ini"></div>
                    </div>
                    <div class="card card-light">
                        <div class="table-responsive" id="table-all-jadwal"></div>
                    </div>
                </div>
                <div class="overlay d-none" id="loading">
                    <div class="spinner-grow"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xs" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoLabel">Kode error siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table w-100">
                    <tr>
                        <td class="text-bold">001</td>
                        <td>:</td>
                        <td>Token salah atau token belum digenerate</td>
                    </tr>
                    <tr>
                        <td class="text-bold">002</td>
                        <td>:</td>
                        <td>Harus reset izin</td>
                    </tr>
                    <tr>
                        <td class="text-bold">003</td>
                        <td>:</td>
                        <td>Harus reset waktu</td>
                    </tr>
                    <tr>
                        <td class="text-bold">004</td>
                        <td>:</td>
                        <td>Soal belum dibuat atau belum dipilih</td>
                    </tr>
                    <tr>
                        <td class="text-bold">005</td>
                        <td>:</td>
                        <td>Siswa menggunakan browser yang tidak mendukung</td>
                    </tr>
                    <tr>
                        <td class="text-bold">006</td>
                        <td>:</td>
                        <td>internet down atau error dari database/server</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    const ruangs = JSON.parse('<?=json_encode($ruang)?>');
    const arrRuang = JSON.parse('<?=json_encode($ruangs)?>');

    function formatTanggal(dateString) {
        const date = new Date(dateString);
        const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
        return date.toLocaleDateString('id-ID', options);
    }

    $(document).ready(function () {
        ajaxcsrf();

        getToken(function (result) {
            getGlobalToken();
        });

        $('#refresh-token').click(function () {
            getToken(function (result) {
                getGlobalToken();
            });
        });

        function getGlobalToken() {
            if (globalToken != null) {
                const viewToken = $('#token-input');
                if (viewToken.length) viewToken.val(globalToken.token);
                if (globalToken.auto == '1' && adaJadwalUjian > 0) {
                    $('#refresh-token').removeClass('d-none')
                }
            }
        }

        $.ajax({
            type: "GET",
            url: base_url + "cbtstatus/gettgljadwalujian",
            cache: false,
            success: function (response) {
                populateTable(response)
            }
        });
    });

    function populateTable(response) {
        console.log('response', response)

        const guru = response.guru
        const tglKeys = Object.keys(response?.jadwals);
        const keyHariIni = tglKeys.find(k => k === response.hari_ini);
        const jadwalHariIni = response.jadwals[keyHariIni] || [];

        if (jadwalHariIni.length > 0) {
            let tableNow = `<table class="table table-bordered border-top m-0 table-sm">
                                <thead>
                                <tr><th colspan="6" class="bg-gray-light text-center">${formatTanggal(keyHariIni)}</th></tr>
                                <tr>
                                    <th class="text-center align-middle">Ruang</th>
                                    <th class="text-center align-middle">Sesi</th>
                                    <th class="text-center align-middle">Jam Ke</th>
                                    <th class="align-middle">Mapel/Bank Soal</th>
                                    <th class="text-center align-middle">Role</th>
                                    <th class="text-center align-middle">Aksi</th>
                                </tr>
                                </thead>
                                <tbody>`;
            for (let i = 0; i < jadwalHariIni.length; i++) {
                const jadwal = jadwalHariIni[i];
                const idJadwal = jadwal.id_jadwal;
                const ruangs = response.ruang[idJadwal];
                const sesies = response.sesi[idJadwal];
                const isPengampu = jadwal?.pengampu;
                for (const idRuang in ruangs) {
                    const ruang = ruangs[idRuang];
                    for (const idSesi in sesies) {
                        const pengawas = response?.pengawas?.find(p => p.id_jadwal === idJadwal && p.id_ruang === idRuang && p.id_sesi === idSesi);
                        const gp = (pengawas?.id_guru || '').split(',');
                        const isPengawas = gp.includes(guru.id_guru);
                        let role = isPengampu ? `<span class="badge badge-success">Pengampu</span>` : ``;
                        if (role !== '') { role += `<br>` }
                        role += isPengawas ? `<span class="badge badge-primary">Pengawas</span>` : ``;
                        const sesi = sesies[idSesi];

                        if (isPengawas || isPengampu) {
                            const tr = `<tr>
                                        <td class="text-center align-middle">${ruang}</td>
                                        <td class="text-center align-middle">${sesi}</td>
                                        <td class="text-center align-middle">${pengawas.jam_ke}</td>
                                        <td class="align-middle">
                                            ${jadwal.bank?.mapel?.nama_mapel}<br>( ${jadwal.bank?.bank_kode} )
                                        </td>
                                        <td class="text-center align-middle">${role}</td>
                                        <td class="text-center align-middle">
                                            <button class="btn btn-info btn-sm" data-id="${idJadwal}" data-ruang="${idRuang}" data-sesi="${idSesi}" onclick="detail(this)">Lihat Status</button>
                                        </td>
                                    </tr>`;
                            tableNow += tr;
                        }
                    }
                }
            }
            tableNow += `</tbody></table>`;
            $('#table-hari-ini').html(tableNow);
        } else {
            const tableNow = `<table class="table table-bordered border-top m-0 table-sm">
                                <thead>
                                <tr><th colspan="6" class="bg-gray-light text-center">Jadwal Ujian Hari Ini</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td colspan="6" class="text-center">Tidak ada jadwal ujian hari ini</td></tr>
                                </tbody></table>`;
            $('#table-hari-ini').html(tableNow);
        }

        const keySebelumnya = tglKeys.filter(k => k < response.hari_ini);
        if (keySebelumnya.length > 0) {
            for (const tgl of keySebelumnya) {
                let tableAll = `<table class="table table-bordered border-top m-0 table-sm">
                                <thead>
                                <tr><th colspan="6" class="bg-gray-light text-center">${formatTanggal(tgl)}</th></tr>
                                <tr>
                                    <th class="text-center align-middle">Ruang</th>
                                    <th class="text-center align-middle">Sesi</th>
                                    <th class="text-center align-middle">Jam Ke</th>
                                    <th class="align-middle">Mapel/Bank Soal</th>
                                    <th class="text-center align-middle">Role</th>
                                    <th class="text-center align-middle">Aksi</th>
                                </tr>
                                </thead>
                                <tbody>`;
                const jadwals = response.jadwals[tgl];

                for (let i = 0; i < jadwals.length; i++) {
                    const jadwal = jadwals[i];
                    const idJadwal = jadwal.id_jadwal;
                    const ruangs = response.ruang[idJadwal];
                    const sesies = response.sesi[idJadwal];
                    const isPengampu = jadwal?.pengampu;
                    for (const idRuang in ruangs) {
                        const ruang = ruangs[idRuang];
                        for (const idSesi in sesies) {
                            const pengawas = response?.pengawas?.find(p => p.id_jadwal === idJadwal && p.id_ruang === idRuang && p.id_sesi === idSesi);
                            const gp = (pengawas?.id_guru || '').split(',');
                            const isPengawas = gp.includes(guru.id_guru);
                            let role = isPengampu ? `<span class="badge badge-success">Pengampu</span>` : ``;
                            if (role !== '') { role += `<br>` }
                            role += isPengawas ? `<span class="badge badge-primary">Pengawas</span>` : ``;
                            const sesi = sesies[idSesi];

                            if (isPengawas || isPengampu) {
                                const tr = `<tr>
                                        <td class="text-center align-middle">${ruang}</td>
                                        <td class="text-center align-middle">${sesi}</td>
                                        <td class="text-center align-middle">${pengawas.jam_ke}</td>
                                        <td class="align-middle">
                                            ${jadwal.bank?.mapel?.nama_mapel}<br>( ${jadwal.bank?.bank_kode} )
                                        </td>
                                        <td class="text-center align-middle">${role}</td>
                                        <td class="text-center align-middle">
                                            <button class="btn btn-info btn-sm" data-id="${idJadwal}" data-ruang="${idRuang}" data-sesi="${idSesi}" onclick="detail(this)">Lihat Status</button>
                                        </td>
                                    </tr>`;
                                tableAll += tr;
                            }
                        }
                    }
                }

                tableAll += `</tbody></table>`;
                $('#table-all-jadwal').append(tableAll);
            }
        } else {
            const tableAll = `<table class="table table-bordered border-top m-0 table-sm">
                                <thead>
                                <tr><th colspan="6" class="bg-gray-light text-center">Jadwal Ujian Sebelumnya</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td colspan="6" class="text-center">Tidak ada jadwal ujian sebelumnya</td></tr>
                                </tbody></table>`;
            $('#table-all-jadwal').html(tableAll);
        }
    }

    function detail(e) {
        window.location.href = base_url + 'cbtstatus/pengawas?' +
            'ruang=' + $(e).data('ruang') +
            '&sesi=' + $(e).data('sesi') +
            '&jadwal=' + $(e).data('id');
    }

</script>
