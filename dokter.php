<?php
include_once("koneksi.php");
if (!isset($_SESSION['user_id'])) { // Cek jika pengguna belum login
    header("Location: loginuser.php"); // Arahkan ke halaman login
    exit(); // Penting untuk menghentikan eksekusi skrip
}

// Add or Update Dokter
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];

    if ($id) {
        // Update existing doctor
        $query = "UPDATE dokter SET 
                    nama = '$nama', 
                    alamat = '$alamat', 
                    no_hp = '$no_hp' 
                  WHERE id = $id";
    } else {
        // Add new doctor
        $query = "INSERT INTO dokter (nama, alamat, no_hp) 
                  VALUES ('$nama', '$alamat', '$no_hp')";
    }

    mysqli_query($mysqli, $query);
    header("Location: index.php?page=dokter");
    exit; // Ensure no further code is executed after redirect
}

// Delete Dokter
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($mysqli, "DELETE FROM dokter WHERE id = $id");
    header("Location: index.php?page=dokter");
    exit; // Ensure no further code is executed after redirect
}
?>

<!-- Form & Table for Dokter -->
<div class="container mt-4">
    <form action="dokter.php" method="post" class="mb-4">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>">
        <input type="text" name="nama" placeholder="Nama" required class="form-control mb-2">
        <input type="text" name="alamat" placeholder="Alamat" required class="form-control mb-2">
        <input type="text" name="no_hp" placeholder="No HP" required class="form-control mb-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>No HP</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = mysqli_query($mysqli, "SELECT * FROM dokter");
            $no = 1;
            while ($data = mysqli_fetch_array($result)) {
                echo "<tr>
                        <td>{$no}</td>
                        <td>" . htmlspecialchars($data['nama']) . "</td>
                        <td>" . htmlspecialchars($data['alamat']) . "</td>
                        <td>" . htmlspecialchars($data['no_hp']) . "</td>
                        <td>
                            <a href='index.php?page=dokter&id={$data['id']}' class='btn btn-warning btn-sm'>Ubah</a> 
                            <a href='dokter.php?aksi=hapus&id={$data['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this doctor?\");'>Hapus</a>
                        </td>
                      </tr>";
                $no++;
            }
            ?>
        </tbody>
    </table>
</div>
