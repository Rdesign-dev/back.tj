<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?= $title ?? 'Teras Japan' ?></title>
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
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-white sidebar sidebar-light accordion shadow-sm" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex text-white align-items-center bg-primary justify-content-center" style="background-image: url('<?= base_url() ?>assets/img/merah.png'); background-size: cover; background-position: center;" href="<?= base_url('dashboard/kasir'); ?>">
                <div class="sidebar-brand-icon">
                <img src="<?= base_url() ?>assets/img/terasjapan.png" alt="Admin Logo" style="width: 24px; height: 24px; object-fit: cover;" class="mr-2">
            <div class="sidebar-brand-text mx-2">Kasir Resto</div>
            </div></a>
            

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('dashboard/kasir'); ?>">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Kasir
            </div>

            <!-- Nav Item - Dashboard -->
            
             <!-- Nav Item - Dashboard -->
             <li class="nav-item">
                <a class="nav-link pb-0" href="<?= base_url('transaksikasir/tambahTransaksiKasir'); ?>">
                <i class="fas fa-history"></i>
                    <span>Transaksi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link pb-0" href="<?= base_url('member/indexKasir'); ?>">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Member</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link pb-0" href="<?= base_url('transaksikasir/historyTransaksiKasir'); ?>">
                <i class="fas fa-file-alt"></i>
                    <span>Riwayat Transaksi <?= userdata('namacabang') ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link pb-0" href="<?= base_url('transaksikasir/getHistorysaldoKasir'); ?>">
                <i class="fas fa-file"></i>
                    <span>Riwayat Top Up Saldo</span>
                </a>
            </li>

            <?php if (is_admin()) : ?>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Heading -->
                <div class="sidebar-heading">
                    Admin
                </div>

                <!-- Nav Item -->
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
                <a class="nav-link pb-0" href="<?= base_url('cabang'); ?>">
                <i class="fas fa-home"></i>
                    <span>Cabang</span>
                </a>
            </li>
            
            <?php endif; ?>

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
                <nav class="navbar navbar-expand navbar-dark bg-primary topbar mb-4 static-top shadow-sm" style="background-image: url('<?= base_url() ?>assets/img/merah.png'); background-size: cover; background-position:center;">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link bg-transparent d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars text-white"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline small text-capitalize">
                                <div class="sidebar-brand-text mx-2" style="color: white; font-size: 15pt; font-weight: bold;"><?= userdata('nama'); ?> | <?= userdata('namacabang'); ?> </div>
                                </span>
                                <img class="img-profile rounded-circle" src="<?= base_url() ?>assets/img/avatar/<?= userdata('foto'); ?>">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="<?= base_url('profilekasir'); ?>">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="<?= base_url('profilekasir/settingKasir'); ?>">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="<?= base_url('profilekasir/ubahpassword'); ?>">
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

                    <?= $contents ?>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-light">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Kasir Resto &bull; Amigos Group 2024</span>
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
                        <span aria-hidden="true">×</span>
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
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "paging": true, // aktifkan paging
            "lengthChange": true, // aktifkan perubahan panjang halaman
            "searching": true, // aktifkan fitur pencarian
            "ordering": true, // aktifkan pengurutan kolom
            "info": true, // aktifkan informasi halaman
            "autoWidth": false, // nonaktifkan pengaturan otomatis lebar kolom
            "responsive": true // aktifkan desain responsif
        });
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
</script>

</html>