<?= $this->session->flashdata('pesan'); ?>
    <div class="brand-selection bg-white mb-4 shadow-sm">
        <h4 class="h5 align-middle m-0 font-weight-bold text-primary" style="padding: 20px 0 10px 20px;">Select Brand</h4>
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
            <div class="col-md-1 col-2 mb-3 d-flex align-items-center justify-content-center">
                <a href="<?= base_url('brand/add') ?>" class="text-decoration-none"></a>
                    <div class="btn btn-primary border" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                        <i class='bx bx-plus' style="font-size: 2rem;"></i>
                    </div>
                </a>
            </div>
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
                        <th width="30">No.</th>
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
</style>

<script>
// Define BASE_URL
const BASE_URL = '<?= base_url() ?>';

document.addEventListener('DOMContentLoaded', function() {
    const brandImages = document.querySelectorAll('.brand-image');
    const tableBody = document.getElementById('brandTableBody');

    async function fetchBrandDetails(brandId) {
        try {
            const response = await fetch(`${BASE_URL}brand/get_brand_details/${brandId}`);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const data = await response.json();
            updateTable(data);
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function updateTable(brand) {
        const row = `
            <tr>
                <td>1</td>
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
                <td>${brand.desc || '-'}</td>
                <td>${brand.instagram || '-'}</td>
                <td>${brand.tiktok || '-'}</td>
                <td>${brand.wa || '-'}</td>
                <td>${brand.web || '-'}</td>
                <td>
                    <a href="${BASE_URL}brand/edit/${brand.id}" class="btn btn-circle btn-sm btn-warning"><i class="fa fa-fw fa-edit"></i></a>
                    <a onclick="return confirm('Yakin ingin menghapus data?')" href="${BASE_URL}brand/delete/${brand.id}" class="btn btn-circle btn-sm btn-danger"><i class="fa fa-fw fa-trash"></i></a>
                </td>
            </tr>`;
        tableBody.innerHTML = row;
    }

    brandImages.forEach(img => {
        img.addEventListener('click', function() {
            const brandId = this.dataset.id;
            fetchBrandDetails(brandId);
            
            // Remove active class from all images
            brandImages.forEach(img => img.classList.remove('border-primary'));
            // Add active class to clicked image
            this.classList.add('border-primary');
        });
    });
});
</script>