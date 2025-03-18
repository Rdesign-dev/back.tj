<?= $this->session->flashdata('pesan'); ?>
    <div class="brand-selection bg-white mb-4 shadow-sm">
        <h4 class="h5 align-middle m-0 font-weight-bold text-primary" style="padding: 20px 0 10px 20px;">Pilih Brand</h4>
        <div class="row gap-1" style="padding: 20px 0 20px 20px;">
            <?php if ($brands) :
                foreach ($brands as $brand) : ?>
                    <div class="col-md-1 col-2 mb-1" style="cursor: pointer;">
                        <img src="<?= base_url('../ImageTerasJapan/logo/' . $brand['image']) ?>" 
                             class="img-fluid brand-image" 
                             alt="<?= $brand['name'] ?>" 
                             data-id="<?= $brand['id'] ?>"
                             style="width: 50px; height: 50px; object-fit: contain;">
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <!-- <div class="col-md-1 col-2 mb-3 d-flex align-items-center justify-content-center">
                <a href="<?= base_url('brand/add') ?>" class="text-decoration-none"></a>
                    <div class="btn btn-primary border" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                        <i class='bx bx-plus' style="font-size: 2rem;"></i>
                    </div>
                </a>
            </div> -->
        </div>
    </div>
    <div class="card shadow-sm mb-4 border-bottom-primary">
        <div class="divider"></div>
        <div class="card-header bg-white py-3">
            <div class="row">
                <div class="col">
                    <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                        Data Brand
                    </h4>
                </div>
                <div class="col-auto">
                    <a href="<?= base_url('brand/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                        <span class="icon">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span class="text">
                            Tambah Data Brand
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped dt-responsive nowrap" id="dataTable">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Banner</th>
                        <th>Nama Brand</th>
                        <th>Deskripsi</th>
                        <th>Instagram</th>
                        <th>Tiktok</th>
                        <th>WhatsApp</th>
                        <th>Website</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="brandTableBody">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-sm mb-4 border-bottom-primary">
        <div class="divider"></div>
        <div class="card-header bg-white py-3">
            <div class="row">
                <div class="col">
                    <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                        Promo Brand
                    </h4>
                </div>
                <div class="col-auto">
                    <!-- Change this button to be initially hidden and show when a brand is selected -->
                    <a href="#" id="addPromoButton" class="btn btn-sm btn-primary btn-icon-split" style="display: none;">
                        <span class="icon">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span class="text">
                            Tambah Promo Brand
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped dt-responsive nowrap" id="promoTable">
                <thead>
                    <tr>
                        <th>Nama Promo</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Tersedia Sejak</th>
                        <th>Batas Waktu</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="promoTableBody">
                    <!-- Promo data will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Voucher Brand
                </h4>
            </div>
            <div class="col-auto">
                <a href="#" id="addVoucherButton" class="btn btn-sm btn-primary btn-icon-split" style="display: none;">
                    <span class="icon">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">
                        Tambah Voucher Brand
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped dt-responsive nowrap" id="voucherTable">
            <thead>
                <tr>
                    <th width="30">No.</th>
                    <th>Judul</th>
                    <th>Gambar</th>
                    <th>Poin</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th>Berlaku Sampai</th>
                    <th>Total Hari Aktif</th>
                    <th>Qty</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="voucherTableBody">
                <!-- Voucher data will be loaded here -->
            </tbody>
        </table>
    </div>
</div>

<style>
.brand-image {
    border: 2px solid transparent;
    transition: border-color 0.3s ease;
}

.brand-image.border-primary {
    border-color: #4e73df;
}

/* Add styles for table images */
.table img {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 2px;
}

.status-badge {
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 0.85em;
    font-weight: bold;
}
.status-Available { background-color: #28a745; color: white; }
.status-Coming { background-color: #ffc107; color: black; }
.status-Expired { background-color: #dc3545; color: white; }
</style>

<script>
// Define BASE_URL
const BASE_URL = '<?= base_url() ?>';

document.addEventListener('DOMContentLoaded', function() {
    const brandImages = document.querySelectorAll('.brand-image');
    const brandTableBody = document.getElementById('brandTableBody');
    const promoTableBody = document.getElementById('promoTableBody');
    const addPromoButton = document.getElementById('addPromoButton');
    const addVoucherButton = document.getElementById('addVoucherButton');
    let selectedBrandId = null;

    // Update the fetchBrandData function
    async function fetchBrandData(brandId) {
        selectedBrandId = brandId; // Store the selected brand ID
        
        // Show and update the Add Promo button
        addPromoButton.style.display = 'inline-block';
        addPromoButton.href = `${BASE_URL}brand/addpromo/${brandId}`;
        addVoucherButton.style.display = 'inline-block';
        addVoucherButton.href = `${BASE_URL}brand/add_voucher/${brandId}`;
        
        try {
            const [brandResponse, promoResponse, voucherResponse] = await Promise.all([
                fetch(`${BASE_URL}brand/get_brand_details/${brandId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    method: 'GET'
                }),
                fetch(`${BASE_URL}brand/get_brand_promos/${brandId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    method: 'GET'
                }),
                fetch(`${BASE_URL}brand/get_brand_vouchers/${brandId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    method: 'GET'
                })
            ]);

            if (!brandResponse.ok) {
                throw new Error(`Brand fetch failed: ${brandResponse.status}`);
            }
            if (!promoResponse.ok) {
                throw new Error(`Promo fetch failed: ${promoResponse.status}`);
            }
            if (!voucherResponse.ok) {
                throw new Error(`Voucher fetch failed: ${voucherResponse.status}`);
            }

            const brandData = await brandResponse.json();
            const promoData = await promoResponse.json();
            const voucherData = await voucherResponse.json();

            if (brandData.error) {
                throw new Error(brandData.error);
            }

            updateBrandTable(brandData);
            updatePromoTable(promoData);
            updateVoucherTable(voucherData);

        } catch (error) {
            console.error('Error:', error);
            brandTableBody.innerHTML = '<tr><td colspan="9" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            promoTableBody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            document.getElementById('voucherTableBody').innerHTML = 
            '<tr><td colspan="10" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            
            // Hide the Add Promo button if there's an error
            addPromoButton.style.display = 'none';
        }
    }

    function updateBrandTable(brand) {
        if (!brand) return;
        
        const row = `
            <tr>
                <td>
                    <img src="${BASE_URL}../ImageTerasJapan/logo/${brand.image}" 
                         alt="${brand.name}" 
                         style="width: 50px; height: 50px; object-fit: contain;">
                </td>
                <td>
                    <img src="${BASE_URL}../ImageTerasJapan/banner/${brand.banner}" 
                         alt="${brand.name} banner" 
                         style="width: 100px; height: 50px; object-fit: cover;">
                </td>
                <td>${brand.name}</td>
                <td style="white-space: normal;">${brand.desc || '-'}</td>
                <td>${brand.instagram || '-'}</td>
                <td>${brand.tiktok || '-'}</td>
                <td>${brand.wa || '-'}</td>
                <td>${brand.web || '-'}</td>
                <td>
                    <a href="<?= base_url('brand/edit/' . $brand['id']) ?>" class="btn btn-circle btn-sm btn-warning"><i class="fa fa-fw fa-edit"></i></a>
                    <a onclick="return confirm('Yakin ingin menghapus data?')" href="<?= base_url('brand/delete/' . $brand['id']) ?>" class="btn btn-circle btn-sm btn-danger"><i class="fa fa-fw fa-trash"></i></a>
                </td>
            </tr>`;
        brandTableBody.innerHTML = row;
    }

    function updatePromoTable(promos) {
        let rows = '';
        promos.forEach(promo => {
            rows += `
                <tr>
                    <td>${promo.promo_name}</td>
                    <td style="white-space: normal;">${promo.promo_desc || '-'}</td>
                    <td>
                        <span class="status-badge status-${promo.status}">
                            ${promo.status}
                        </span>
                    </td>
                    <td>${promo.available_from ? new Date(promo.available_from).toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    }) : '-'}</td>
                    <td>${promo.valid_until ? new Date(promo.valid_until).toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    }) : '-'}</td>
                    <td>
                        <img src="${BASE_URL}../ImageTerasJapan/promo/${promo.promo_image}" 
                             alt="${promo.promo_name}" 
                             style="width: 100px; height: 50px; object-fit: cover;">
                    </td>
                    <td>
                        <a href="${BASE_URL}brand/editpromo/${promo.id}" class="btn btn-circle btn-sm btn-warning">
                            <i class="fa fa-fw fa-edit"></i>
                        </a>
                        <a onclick="return confirm('Yakin ingin menghapus promo ini?')" 
                           href="${BASE_URL}brand/deletepromo/${promo.id}" 
                           class="btn btn-circle btn-sm btn-danger">
                            <i class="fa fa-fw fa-trash"></i>
                        </a>
                    </td>
                </tr>`;
        });
        promoTableBody.innerHTML = rows || '<tr><td colspan="7" class="text-center">Tidak ada promo untuk brand ini</td></tr>';
    }

    function updateVoucherTable(vouchers) {
        const tbody = document.getElementById('voucherTableBody');
        if (!vouchers || vouchers.length === 0) {
            tbody.innerHTML = '<tr><td colspan="10" class="text-center">Tidak ada voucher untuk brand ini</td></tr>';
            return;
        }
    
        let rows = '';
        vouchers.forEach((voucher, index) => {
            rows += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${voucher.title}</td>
                    <td>
                        ${voucher.image_name ? 
                            `<img src="${BASE_URL}../ImageTerasJapan/reward/${voucher.image_name}" 
                                  alt="Voucher Image" 
                                  width="150px" 
                                  height="100px"
                                  class="img-thumbnail">` : 
                            '<span class="text-muted">No image</span>'}
                    </td>
                    <td>${voucher.points_required}</td>
                    <td>${getCategoryLabel(voucher.category)}</td>
                    <td>${voucher.description || '-'}</td>
                    <td>${formatDate(voucher.valid_until)}</td>
                    <td>${voucher.total_days || '-'}</td>
                    <td>${voucher.qty}</td>
                    <td>
                        <a href="${BASE_URL}brand/edit_voucher/${voucher.id}" 
                           class="btn btn-circle btn-sm btn-warning">
                            <i class="fa fa-fw fa-edit"></i>
                        </a>
                        <a onclick="return confirm('Yakin ingin menghapus data?')" 
                           href="${BASE_URL}brand/delete_voucher/${voucher.id}" 
                           class="btn btn-circle btn-sm btn-danger">
                            <i class="fa fa-fw fa-trash"></i>
                        </a>
                    </td>
                </tr>`;
        });
        tbody.innerHTML = rows;
    }
    
    function getCategoryLabel(category) {
        switch(category) {
            case 'oldmember': return 'Member Biasa';
            case 'newmember': return 'Member Baru';
            case 'code': return 'Kode Referal';
            default: return 'Undefined';
        }
    }
    
    function formatDate(dateString) {
        if (!dateString) return '-';
        return new Date(dateString).toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }

    brandImages.forEach(img => {
        img.addEventListener('click', function() {
            const brandId = this.dataset.id;
            fetchBrandData(brandId);
            
            // Remove active class from all images
            brandImages.forEach(img => img.classList.remove('border-primary'));
            // Add active class to clicked image
            this.classList.add('border-primary');
        });
    });
});
</script>