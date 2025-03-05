<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?= $title; ?> | Admin Resto</title>
    <script src="<?= base_url(); ?>assets/tinymce/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <link rel="icon" href="<?= base_url(); ?>assets/img/terasjapan.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Custom fonts for this template-->
    <link href="<?= base_url(); ?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/css/fonts.min.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url(); ?>assets/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Datepicker -->
    <link href="<?= base_url(); ?>assets/vendor/daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="<?= base_url(); ?>assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/vendor/datatables/buttons/css/buttons.bootstrap4.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/vendor/datatables/responsive/css/responsive.bootstrap4.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/vendor/gijgo/css/gijgo.min.css" rel="stylesheet">
    
    

    <style>
        #accordionSidebar,
        .topbar {
            z-index: 1;
        }
        
        .is-invalid {
            border-color: #dc3545 !important;
        }

        .text-danger {
            color: #dc3545;
        }
        .chart-container {
        position: relative;
        width: 100%;
        height: auto;
    }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-white sidebar sidebar-light accordion shadow-sm" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex text-white align-items-center bg-primary justify-content-center" style="background-image: url('<?= base_url() ?>assets/img/merah.png'); background-size: cover; background-position: center;" href="">
                <div class="sidebar-brand-icon">
                <img src="<?= base_url() ?>assets/img/terasjapan.png" alt="Admin Logo" style="width: 24px; height: 24px; object-fit: cover;" class="mr-2">
            <div class="sidebar-brand-text mx-2">Admin resto</div>
            </div></a>
            

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('dashboard'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            

            <?php if (is_admin()) : ?>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Heading -->
                <div class="sidebar-heading">
                    Super Admin
                </div>
            <li class="nav-item">
                <a class="nav-link pb-0" href="<?= base_url('cabang'); ?>">
                <i class="fas fa-store"></i>
                    <span>Cabang</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link pb-0" href="<?= base_url('iklan'); ?>">
                <i class="fas fa-clone"></i>
                    <span>Iklan Promosi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link pb-0" href="<?= base_url('blog'); ?>">
                <i class="fas fa-blog"></i>
                    <span>Blog</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link pb-0" href="<?= base_url('voucher'); ?>">
                <i class="fas fa-file-invoice-dollar"></i>
                    <span>Voucher</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link pb-0" href="<?= base_url('bantuan'); ?>">
                <i class="fas fa-tasks"></i>
                    <span>Pusat Bantuan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link pb-0" href="<?= base_url('content'); ?>">
                <i class="fas fa-file-contract"></i>
                    <span>Konten PopUp</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link pb-0" href="<?= base_url('laporan'); ?>">
                <i class="fas fa-file-pdf"></i>
                    <span>Laporan Transaksi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link pb-0" href="<?= base_url('undian/inputPoinUndian'); ?>">
                <i class="fas fa-gift"></i>
                    <span>Input Poin Undian</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link pb-0" href="<?= base_url('undian'); ?>">
                <i class="fas fa-gift"></i>
                    <span>Data Undian</span>
                </a>
            </li>
            
            <?php endif; ?>
            <hr class="sidebar-divider d-none d-md-block">
            <div class="sidebar-heading">
                Admin Pusat
            </div>
            <li class="nav-item">
                    <a class="nav-link pb-0" href="<?= base_url('user'); ?>">
                        <i class="fas fa-fw fa-user-plus"></i>
                        <span>User Management</span>
                    </a>
                </li>
                <li class="nav-item">
                <a class="nav-link pb-0" href="<?= base_url('member'); ?>">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Member</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link pb-0" href="<?= base_url('transaksi'); ?>">
                <i class="fas fa-history"></i>
                    <span>Transaksi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link pb-0" href="<?= base_url('transaksi/historyTransaksi'); ?>">
                <i class="fas fa-file-alt"></i>
                    <span>Riwayat Transaksi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link pb-0" href="<?= base_url('transaksi/saldo'); ?>">
                <i class="fas fa-money-bill-wave-alt"></i>
                    <span>Top Up Saldo</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link pb-0" href="<?= base_url('transaksi/getHistorysaldo'); ?>">
                <i class="fas fa-file"></i>
                    <span>Riwayat Top Up Saldo</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link pb-0" href="<?= base_url('member/getLoggingMember'); ?>">
                <i class="fas fa-history"></i>
                    <span>Tracking Login</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-dark bg-primary topbar mb-4 static-top shadow-sm" style="background-image: url('<?= base_url() ?>assets/img/merah.png'); background-size: cover; background-position: center;">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link bg-transparent d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars text-white"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-lg-inline small text-capitalize">
                                    <?= userdata('name'); // Menggunakan 'name' bukan 'nama' ?>
                                </span>
                                <img class="img-profile rounded-circle" 
                                     src="<?= base_url() ?>assets/img/avatar/<?= userdata('photo'); // Menggunakan 'photo' bukan 'foto' ?>">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="<?= base_url('profile'); ?>">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="<?= base_url('profile/setting'); ?>">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="<?= base_url('profile/ubahpassword'); ?>">
                                    <i class="fas fa-lock fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Change Password
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

                    <?= $contents; ?>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-light">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Admin Resto &bull; Amigos Group 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yakin ingin logout?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">Klik "Logout" dibawah ini jika anda yakin ingin logout.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batalkan</button>
                    <a class="btn btn-primary" href="<?= base_url('logout'); ?>">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    
    
    <script src="<?= base_url(); ?>assets/vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url(); ?>assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url(); ?>assets/js/sb-admin-2.min.js"></script>

    <!-- Datepicker -->
    <script src="<?= base_url(); ?>assets/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/daterangepicker/daterangepicker.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?= base_url(); ?>assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/jszip/jszip.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/pdfmake/pdfmake.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/pdfmake/vfs_fonts.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/buttons/js/buttons.html5.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/buttons/js/buttons.print.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/buttons/js/buttons.colVis.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/gijgo/js/gijgo.min.js"></script>
    
    
</body>
<script>
$(function() {
            var start = moment().subtract(29, 'days');
            var end = moment();

            function cb(start, end) {
                $('#tangal').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
            }

            $('#tanggal').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Hari ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
                    '7 hari terakhir': [moment().subtract(6, 'days'), moment()],
                    '30 hari terakhir': [moment().subtract(29, 'days'), moment()],
                    'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Tahun ini': [moment().startOf('year'), moment().endOf('year')],
                    'Tahun lalu': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
                }
            }, cb);

            cb(start, end);
        });
    $(document).ready(function() {
            var table = $('#dataTable').DataTable({
                buttons: ['copy', 'csv', 'print', 'excel', 'pdf'],
                dom: "<'row px-2 px-md-4 pt-2'<'col-md-3'l><'col-md-5 text-center'B><'col-md-4'f>>" +
                    "<'row'<'col-md-12'tr>>" +
                    "<'row px-2 px-md-4 py-3'<'col-md-5'i><'col-md-7'p>>",
                lengthMenu: [
                    [5, 10, 25, 50, 100, -1],
                    [5, 10, 25, 50, 100, "All"]
                ],
                columnDefs: [{
                    targets: -1,
                    orderable: false,
                    searchable: false
                }]
            });

            table.buttons().container().appendTo('#dataTable_wrapper .col-md-5:eq(0)');
        });
    
    $(document).ready(function() {
        // Function to toggle voucher code field visibility
        function toggleVoucherCodeField() {
            var isChecked = $('#tukarVoucher').prop('checked');
            if (isChecked) {
                $('#divKodevoucher').show();
                $('#kodevouchertukar').prop('disabled', false);
            } else {
                $('#divKodevoucher').hide();
                $('#kodevouchertukar').prop('disabled', true);
            }
        }

        // Initial state check
        toggleVoucherCodeField();

        // Event handler for checkbox change
        $('#tukarVoucher').change(function() {
            toggleVoucherCodeField();
        });
    });
    function clearCacheAndCookies() {
        document.cookie.split(";").forEach(function(c) {
            document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
        });
        localStorage.clear();
    }
    tinymce.init({
        selector: '#syarat',
         plugins: 'advlist autolink lists link image charmap print preview anchor',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | image',
            content_css: 'assets/tinymce/js/tinymce/skins/content/default/content.min.css'
    })
    tinymce.init({
        selector: '#syarattukar',
         plugins: 'advlist autolink lists link image charmap print preview anchor',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | image',
            content_css: 'assets/tinymce/js/tinymce/skins/content/default/content.min.css'
    })
    tinymce.init({
        selector: '#isi',
         plugins: 'advlist autolink lists link image charmap print preview anchor',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | image',
            content_css: 'assets/tinymce/js/tinymce/skins/content/default/content.min.css'
    })
    tinymce.init({
        selector: '#konten',
         plugins: 'advlist autolink lists link image charmap print preview anchor',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | image',
            content_css: 'assets/tinymce/js/tinymce/skins/content/default/content.min.css'
    })
    tinymce.init({
        selector: '#kontenBlog',
         plugins: 'advlist autolink lists link image charmap print preview anchor',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | image',
            content_css: 'assets/tinymce/js/tinymce/skins/content/default/content.min.css'
    })
//     tinymce.init({
//     selector: '#syarat',
//     plugins: 'ai tinycomments mentions anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
//     toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
//     tinycomments_mode: 'embedded',
//     tinycomments_author: 'Author name',
//     forced_root_block: false,
//     force_br_newlines: true,
//     mergetags_list: [
//       { value: 'First.Name', title: 'First Name' },
//       { value: 'Email', title: 'Email' },
//     ],
//     ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
//   });
//   tinymce.init({
//     selector: '#syarattukar',
//     plugins: 'ai tinycomments mentions anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
//     toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
//     tinycomments_mode: 'embedded',
//     tinycomments_author: 'Author name',
//     forced_root_block: false,
//     force_br_newlines: true,
//     mergetags_list: [
//       { value: 'First.Name', title: 'First Name' },
//       { value: 'Email', title: 'Email' },
//     ],
//     ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
//   });
</script>

</html>