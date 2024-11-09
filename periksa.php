<?php
include_once("koneksi.php");
if (!isset($_SESSION['user_id'])) { // Cek jika pengguna belum login
    header("Location: loginuser.php"); // Arahkan ke halaman login
    exit(); // Penting untuk menghentikan eksekusi skrip
}

// Add or Update Periksa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;
    $id_pasien = $_POST['id_pasien'];
    $id_dokter = $_POST['id_dokter'];
    $tgl_periksa = $_POST['tgl_periksa'];
    $catatan = $_POST['catatan'];
    $obat = $_POST['obat'];

    if ($id) {
        // Update
        $query = "UPDATE periksa SET id_pasien='$id_pasien', id_dokter='$id_dokter', tgl_periksa='$tgl_periksa', catatan='$catatan', obat='$obat' WHERE id=$id";
    } else {
        // Add
        $query = "INSERT INTO periksa (id_pasien, id_dokter, tgl_periksa, catatan, obat) VALUES ('$id_pasien', '$id_dokter', '$tgl_periksa', '$catatan', '$obat')";
    }

    mysqli_query($mysqli, $query);
    header("Location: index.php?page=periksa");
}

// Delete Periksa
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($mysqli, "DELETE FROM periksa WHERE id=$id");
    header("Location: index.php?page=periksa");
}
?>

<!-- Form & Table for Periksa -->
<div class="container mt-4">
    <h2>Data Periksa</h2>
    <form action="periksa.php" method="post">
        <input type="hidden" name="id" value="<?php echo $_GET['id'] ?? ''; ?>">
        
        <div class="mb-3">
            <label for="id_pasien" class="form-label">Pasien</label>
            <select name="id_pasien" id="id_pasien" class="form-select" required>
                <?php
                $pasien = mysqli_query($mysqli, "SELECT * FROM pasien");
                while ($data = mysqli_fetch_array($pasien)) {
                    echo "<option value='{$data['id']}'>{$data['nama']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="id_dokter" class="form-label">Dokter</label>
            <select name="id_dokter" id="id_dokter" class="form-select" required>
                <?php
                $dokter = mysqli_query($mysqli, "SELECT * FROM dokter");
                while ($data = mysqli_fetch_array($dokter)) {
                    echo "<option value='{$data['id']}'>{$data['nama']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="tgl_periksa" class="form-label">Tanggal Periksa</label>
            <input type="datetime-local" name="tgl_periksa" id="tgl_periksa" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="catatan" class="form-label">Catatan</label>
            <textarea name="catatan" id="catatan" class="form-control" rows="4" placeholder="Catatan"></textarea>
        </div>

        <div class="mb-3">
            <label for="obat" class="form-label">Obat</label>
            <input type="text" name="obat" id="obat" class="form-control" placeholder="Obat">
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>

    <h2 class="mt-5">Riwayat Pemeriksaan</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Pasien</th>
                <th>Dokter</th>
                <th>Tgl Periksa</th>
                <th>Catatan</th>
                <th>Obat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = mysqli_query($mysqli, "SELECT pr.*, d.nama AS nama_dokter, p.nama AS nama_pasien FROM periksa pr LEFT JOIN dokter d ON pr.id_dokter=d.id LEFT JOIN pasien p ON pr.id_pasien=p.id");
            $no = 1;
            while ($data = mysqli_fetch_array($result)) {
                echo "<tr>
                    <td>{$no}</td>
                    <td>{$data['nama_pasien']}</td>
                    <td>{$data['nama_dokter']}</td>
                    <td>{$data['tgl_periksa']}</td>
                    <td>{$data['catatan']}</td>
                    <td>{$data['obat']}</td>
                    <td>
                        <a href='index.php?page=periksa&id={$data['id']}' class='btn btn-warning btn-sm'>Ubah</a>
                        <a href='periksa.php?aksi=hapus&id={$data['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Anda yakin ingin menghapus?\")'>Hapus</a>
                    </td>
                </tr>";
                $no++;
            }
            ?>
        </tbody>
    </table>
</div>
