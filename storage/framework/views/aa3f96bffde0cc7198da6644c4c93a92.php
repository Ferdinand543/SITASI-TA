

<?php $__env->startSection('content'); ?>

<div class="container mt-4">

    <!-- TITLE -->
    <div class="text-center mb-4 form-header-box">
        <h3 class="fw-bold">Form Pengajuan Proposal TA</h3>
        <p class="text-muted small">
            Silakan lengkapi data untuk mengajukan Judul Tugas Akhir
        </p>
    </div>

    <!-- FORM -->
    <div class="d-flex justify-content-center">
        <div style="width: 350px;">

            <form id="formProposal" enctype="multipart/form-data">

                <!-- Nama -->
                <div class="mb-3">
                    <label>Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" id="nama" class="form-control" placeholder="Nama Lengkap">
                </div>

                <!-- NIM -->
                <div class="mb-3">
                    <label>NIM <span class="text-danger">*</span></label>
                    <input type="text" id="nim" class="form-control" placeholder="NIM">
                </div>

                <!-- Judul -->
                <div class="mb-3">
                    <label>Judul TA</label>
                    <input type="text" id="judul" class="form-control" placeholder="Judul TA">
                </div>

                <!-- Upload Proposal -->
                <div class="mb-3">
                    <label>Proposal TA</label>
                    <input type="file" id="proposal" class="form-control">
                </div>

                <!-- Dosen 1 -->
                <div class="mb-3">
                    <label>Dosen Pembimbing 1 <span class="text-danger">*</span></label>
                    <select id="dosen1" class="form-control">
                        <option value="">Pilih dosen pembimbing</option>
                        <option>Dr. Budi</option>
                        <option>Dr. Andi</option>
                    </select>
                </div>

                <!-- Dosen 2 -->
                <div class="mb-3">
                    <label>Dosen Pembimbing 2 <span class="text-danger">*</span></label>
                    <select id="dosen2" class="form-control">
                        <option value="">Pilih dosen pembimbing</option>
                        <option>Dr. Rina</option>
                        <option>Dr. Sari</option>
                    </select>
                </div>

                <!-- BUTTON -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-warning px-4">
                        Submit Proposal
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<!-- ALERT -->
<div class="container mt-3" style="max-width:400px;">
    <div id="errorAlert" class="alert alert-danger d-none">
        Semua field wajib diisi!
    </div>
</div>

<!-- SCRIPT -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("formProposal");
    const alertBox = document.getElementById("errorAlert");

    form.addEventListener("submit", function(e){

        let nama = document.getElementById("nama").value.trim();
        let nim = document.getElementById("nim").value.trim();
        let dosen1 = document.getElementById("dosen1").value;
        let dosen2 = document.getElementById("dosen2").value;

        if(nama === "" || nim === "" || dosen1 === "" || dosen2 === ""){
            e.preventDefault();
            alertBox.classList.remove("d-none");
            return;
        }

        alertBox.classList.add("d-none");
        alert("Proposal berhasil dikirim!");
    });

});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\sitasita2\sitasita\resources\views/pengajuan/proposal.blade.php ENDPATH**/ ?>