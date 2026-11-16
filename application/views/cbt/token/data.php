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
                    <div class="card-tools">
                        <button id="generate" onclick="simpanToken('1')" class="btn btn-success">
                            GENERATE NEW TOKEN
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="alert border border-success alert-default-success pt-3">
                                <ul>
                                    <li>
                                        Jika mengklik <span class="badge badge-btn btn-success"> GENERATE NEW TOKEN</span>
                                        segera beritahukan guru/admin lain yang sedang login agar merefresh token untuk
                                        mendapatkan token terbaru.
                                    </li>
                                    <li>
                                        Token akan digenerate otomatis jika :
                                        <ul>
                                            <li>
                                                pilihan <b>Otomatis: YA</b>.
                                            </li>
                                            <li>
                                                ada jadwal ujian pada hari ini.
                                            </li>
                                            <li>
                                                ada akun Admin/Pengawas yang login minimal 1 akun.
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 p-1">
                            <div class="card border border-light" id="card-set">
                                <div class="row card-body">
                                    <div class="col-6">
                                        <label>Otomatis ?</label>
                                        <?php
                                        $arrVal = ["TIDAK", "YA"];
                                        echo form_dropdown(
                                            'auto',
                                            $arrVal,
                                            $token->auto,
                                            'id="auto" class="form-control"'
                                        ); ?>
                                    </div>
                                    <div class="col-6">
                                        <label>Interval (menit)</label>
                                        <input id="jarak" type="number" class="form-control" name="jarak"
                                               value="<?= $token->jarak ?>" <?= $token->auto == '0' ? 'disabled="disabled"' : '' ?>>
                                        <button class="float-right mt-3 btn btn-info" onclick="simpanToken('0')">Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 p-1">
                            <div class="card card-light p-4" id="card-view">
                                <div class="text-center my-auto">
                                    <span>TOKEN SAAT INI</span>
                                    <h1 id="token-view"><?= $token->token ?></h1>
                                    <small id="info-interval" class="mt-3 text-center d-none">Token akan dibuat otomatis dalam <b
                                                id="interval">-- : -- : --</b></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function simpanToken(force) {
        var auto = $('#auto').val();
        var jarak = $('#jarak').val();
        if (auto == '1' && jarak == '0') {
            swal.fire({
                title: "Ups!",
                text: "Interval menit harus diisi",
                icon: "warning"
            });
        } else {
            if (force === '1') {
                forceGenerate = 1;
            } else {
                if (auto === globalToken.auto && jarak === globalToken.jarak) {
                    forceGenerate = 0;
                } else {
                    forceGenerate = 1
                }
            }

            globalToken.auto = auto;
            globalToken.jarak = jarak;
            generateToken();
            showSuccessToast('Token berhasil disimpan')
        }
    }

    $(document).ready(function () {
        $('#auto').on('change', function () {
            var idAuto = $(this).val();
            var token = {};
            token ["token"] = globalToken.token;
            token ["auto"] = idAuto;
            $('#jarak').attr('disabled', idAuto == '0');
        });

        console.log('height', $("#card-set").height());
        $("#card-view").css({'height':($("#card-set").height()+'px')});
    });
</script>
