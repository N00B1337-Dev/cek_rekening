<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- //TOAST -->
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">

</head>

<body>
    <div class="row">
        <div class="d-flex justify-content-center">

            <div class="col-lg-6 col-xs-12 col-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Cek Rekening dan Ewallet</h5>
                        <div id="alert">

                        </div>
                        <form action="">
                            <div class="mb-3">
                                <label for="">Apikey</label>
                                <input type="text" class="form-control" name="apikey" value="d747ccaac52611efa0f6bc24113c8a5eg0h3D0kumv">
                            </div>

                            <div class="mb-3">
                                <label for="">Jenis</label>
                                <select name="jenis" id="" class="form-control">
                                    <option value="">PILIH</option>
                                    <option value="EWALLET">EWALLET</option>
                                    <option value="BANK">BANK</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="">Bank/Ewallet</label>
                                <select name="banklist" id="" class="form-control">
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="">Target</label>
                                <input type="text" class="form-control" name="target">
                            </div>

                            <button type="button" id="cek" class="btn btn-primary">Cek Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var url = "https://apidev.biz.id//api/checker?";
        var apikey = $("input[name=apikey]").val();

        toastr.options = {
            "closeButton": true,
            "newestOnTop": false,
            "progressBar": true,
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        $("button[id=cek]").click(() => {
            var target = $("input[name=target]").val();

            if (checkAPikey() == true && target != "") {
                $("#alert").empty();
                var kd = $("select[name=banklist]").val();
                var target = $("input[name=target]").val();
                var path = "getAccount&kode_bank=" + kd + "&nomor_rekening=" + target;
                $.get(url + path, function(dtk) {
                    if (dtk.success == false) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: dtk.message
                        });
                        toastr.error(dtk.message);
                    } else {

                        var bankName = dtk.data.nama_bank;
                        var label = dtk.success ? "success" : "danger";
                        var bankAccountName = dtk.data.nama_pemilik;
                        var msg = dtk.message + "<br><br>Bank: " + bankName + "<br>Rekening: " + target + "<br>Nama Pemilik: " + bankAccountName;
                        $("#alert").append("<div class='alert alert-" + label + "' role='alert'>" + msg + "</div>");

                        toastr.success('Bank ' + bankName + ' Rekening ' + target + ' a/n ' + bankAccountName);

                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Target/Nomor rekening/Nomor ewallet tidak boleh kosong'
                });

                toastr.error('Target/Nomor rekening/Nomor ewallet tidak boleh kosong');
            }
        });

        function checkAPikey() {
            globalThis.apikey;
            if (apikey == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Apikey tidak boleh kosong'
                });
                toastr.error('Apikey tidak boleh kosong');


                return false;
            } else {
                url += "apikey=" + apikey + "&action=";
                return true;
            }
        }

        $("select[name=jenis]").on('change', function() {
            if (checkAPikey() == true) {
                var jenis = $("select[name=jenis]").val();
                if (jenis == "") {
                    $("select[name=banklist]").empty();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Pilih jenis terlebih dahulu'
                    });

                    toastr.error('Pilih jenis terlebih dahulu');

                } else {

                    globalThis.jenis;
                    globalThis.url;
                    $.get(url + "getBankList", (data) => {
                        var success = data.success;
                        var icon = success ? "success" : "error";
                        var title = success ? "Berhasil" : "Gagal";
                        var msg = data.message;

                        if (success == false) {
                            Swal.fire({
                                icon: icon,
                                title: title,
                                text: msg
                            });

                            toastr.error('msg');

                        }

                        if (success == true) {
                            var banklist = data.data.banks;
                            $("select[name=banklist]").empty();
                            banklist.forEach((bank) => {
                                if (bank.kategori == jenis) {
                                    $("select[name=banklist]").append("<option value='" + bank.kode_bank + "'>" + bank.nama_bank + "</option>");
                                }
                            });
                        }
                    });
                }
            }
        });
    </script>

</body>

</html>