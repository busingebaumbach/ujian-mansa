<div class="container pt-4">
    <div class="info-box bg-transparent shadow-none">
        <?php
        $logo_app = $setting->logo_kanan == null ? base_url() . 'assets/img/favicon.png' : base_url() . $setting->logo_kanan;
        ?>
        <img src="<?= $logo_app ?>" width="60" height="60">
        <div class="info-box-content ml-2" style="text-shadow: 1px 1px 2px #000000">
            <h5 class="info-box-text text-white text-wrap"><b><?= $setting->nama_aplikasi ?></b></h5>
            <span class="info-box-text text-white"><?= $setting->alamat ?></span>
        </div>
    </div>

    <div class="slideshow-container">
        <div class="slide" style="background-image: url('<?= base_url("/assets/img/wall1.jpg") ?>');"></div>
        <div class="slide" style="background-image: url('<?= base_url("/assets/img/wall2.png") ?>');"></div>
        <div class="slide" style="background-image: url('<?= base_url("/assets/img/wall3.jpg") ?>');"></div>
    </div>

    <div class="container-fluid h-100">
        <div class="row h-100 justify-content-center">
            <div class="login-box">
                <div class="login-logo text-white" style="text-shadow: 1px 1px 2px #000000">
                    <b>S</b>elamat <b>D</b>atang
                </div>

                <div class="card form-signin">
                    <div class="card-body">
                        <p class="login-box-msg">L O G I N</p>
                        <div id="infoMessage"></div>

                        <?= form_open("auth/login", array('id' => 'login')); ?>
                        <div class="input-group mb-3 has-feedback">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                            <input type="text" name="username" value="" id="identity" placeholder="Username" autofocus="autofocus" class="form-control" autocomplete="off" required="">
                            <div class="help-block"></div>
                        </div>
                        <div class="input-group mb-3 has-feedback">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            <input type="password" name="password" value="" id="password" placeholder="Password" class="form-control" required="">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span id="toggle-password" class="fas fa-eye-slash" style="cursor: pointer"></span>
                                </div>
                            </div>
                            <div class="help-block"></div>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <select class="form-control" id="cbt-only" name="cbt-only">
                                    <option value="1">Login CBT</option>
                                    <option value="0">Login Elearning & CBT</option>
                                </select>
                                <!--
                                <div class="icheck-cyan">
                                    <input type='checkbox' id="cbt-only" name='cbt-only'
                                           value='1' checked="checked"/>
                                    <label for="cbt-only">Login CBT</label>
                                </div>
                                -->
                            </div>
                            <div class="col-4">
                                <?= form_submit('submit', 'login', array('id' => 'submit', 'class' => 'btn btn-primary btn-block btn-flat')); ?>
                            </div>
                        </div>
                        <?= form_close(); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
    let base_url = '<?=base_url();?>';
    const slides = document.querySelectorAll('.slide');
    let currentSlide = 0;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.remove('active');
            if (i === index) {
                slide.classList.add('active');
            }
        });
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    // Show the first slide initially
    showSlide(currentSlide);

    // Change slides every 5 seconds
    setInterval(nextSlide, 10000);

    $(document).ready(function(){
        const isLogin = localStorage.getItem('garudaCBT.login')
        $('#cbt-only').val(isLogin || '0')

        $('form#login input').on('change', function(){
            $(this).parent().removeClass('has-error');
            $(this).next().next().text('');
        });

        $('form#login').on('submit', function(e){
            e.preventDefault();
            e.stopImmediatePropagation();

            var infobox = $('#infoMessage');
            infobox.addClass('info-box align-items-center justify-content-center bg-gradient-info').text('Checking...');

            var btnsubmit = $('#submit');
            btnsubmit.attr('disabled', 'disabled').val('Wait...');

            const cbtOnly = $('#cbt-only').val()
            localStorage.setItem('garudaCBT.login', cbtOnly)

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(data){
                    infobox.removeAttr('class').text('');
                    btnsubmit.removeAttr('disabled').val('Login');
                    console.log('login', data);
                    if(data.success){
                        infobox.addClass('info-box align-items-center justify-content-center bg-gradient-success').text('Login Sukses');

                        const isLogin = localStorage.getItem('garudaCBT.login')
                        const isCbtMode = isLogin ? isLogin === '1' : false
                        if (data.role === '0') {
                            infobox.addClass('info-box align-items-center justify-content-center bg-gradient-danger').text('Anda tidak diizinkan!');
                        } else {
                            let go = base_url;
                            if (isCbtMode && data.role === '3') {
                                go = 'cbtsiswa/cbt'
                            }
                            window.location.href = go;
                        }
                    } else {
                        infobox.addClass('info-box align-items-center justify-content-center bg-gradient-danger').text(data.message);
                        if(data.error.length > 0){
                            $.each(data.error, function(key, val){
                                $('[name="'+key+'"').parent().addClass('has-error');
                                $('[name="'+key+'"').next().next().text(val);
                                if(val == ''){
                                    $('[name="'+key+'"').parent().removeClass('has-error');
                                    $('[name="'+key+'"').next().next().text('');
                                }
                            });
                        }
                    }
                }
            });
        });

        $('#toggle-password').on('click', function (e) {
            // toggle the type attribute
            const type = $('#password').attr('type') === 'password' ? 'text' : 'password';
            $('#password').attr('type', type);
            // toggle the eye / eye slash icon
            $(this).toggleClass('fa-eye-slash fa-eye');
        });
    });
</script>