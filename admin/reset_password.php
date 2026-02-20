<?php
/**
 * Reset Admin Password
 */

define('KREASI_PRO_LOADED', true);

require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/auth.php';

requireLogin();

$csrfToken  = generateCsrfToken();
$pageTitle  = "Reset Password";
require_once 'layout/header.php';
require_once 'layout/sidebar.php';
require_once 'layout/topbar.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex align-items-center gap-2">
                <i class="fas fa-key text-warning"></i>
                <span class="fw-bold">Reset Password Admin</span>
            </div>
            <div class="card-body p-4">

                <div id="pwAlert" class="d-none mb-3"></div>

                <form id="resetPasswordForm">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">PASSWORD SAAT INI</label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="currentPassword"
                                   class="form-control" placeholder="••••••••" required autocomplete="current-password">
                            <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="currentPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">PASSWORD BARU</label>
                        <div class="input-group">
                            <input type="password" name="new_password" id="newPassword"
                                   class="form-control" placeholder="Min. 8 karakter" required autocomplete="new-password">
                            <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="newPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <!-- Strength indicator -->
                        <div class="mt-2">
                            <div class="progress" style="height: 4px;">
                                <div id="strengthBar" class="progress-bar" role="progressbar" style="width: 0; transition: width 0.3s;"></div>
                            </div>
                            <small id="strengthLabel" class="text-muted"></small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold">KONFIRMASI PASSWORD BARU</label>
                        <div class="input-group">
                            <input type="password" name="confirm_password" id="confirmPassword"
                                   class="form-control" placeholder="Ulangi password baru" required autocomplete="new-password">
                            <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="confirmPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <small id="matchLabel" class="text-muted"></small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning fw-bold" id="submitBtn">
                            <i class="fas fa-save me-2"></i> Simpan Password Baru
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Toggle show/hide password
    document.querySelectorAll('.toggle-pw').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const input = document.getElementById(this.dataset.target);
            const icon  = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });

    // Password strength meter
    document.getElementById('newPassword').addEventListener('input', function() {
        const val = this.value;
        const bar   = document.getElementById('strengthBar');
        const label = document.getElementById('strengthLabel');

        let score = 0;
        if (val.length >= 8)  score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const levels = [
            { label: '',            cls: '',          width: 0   },
            { label: 'Lemah',       cls: 'bg-danger', width: 25  },
            { label: 'Cukup',       cls: 'bg-warning',width: 50  },
            { label: 'Kuat',        cls: 'bg-info',   width: 75  },
            { label: 'Sangat Kuat', cls: 'bg-success',width: 100 },
        ];

        const lvl = levels[score];
        bar.style.width = lvl.width + '%';
        bar.className   = 'progress-bar ' + lvl.cls;
        label.textContent = lvl.label;
    });

    // Match check
    document.getElementById('confirmPassword').addEventListener('input', function() {
        const matchLabel = document.getElementById('matchLabel');
        if (this.value === document.getElementById('newPassword').value) {
            matchLabel.textContent = '✅ Password cocok';
            matchLabel.className   = 'text-success small';
        } else {
            matchLabel.textContent = '❌ Password tidak cocok';
            matchLabel.className   = 'text-danger small';
        }
    });

    // Submit
    document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const np   = document.getElementById('newPassword').value;
        const cp   = document.getElementById('confirmPassword').value;
        const alert = document.getElementById('pwAlert');

        if (np !== cp) {
            showAlert('danger', '❌ Password baru dan konfirmasi tidak cocok!');
            return;
        }
        if (np.length < 8) {
            showAlert('danger', '⚠️ Password baru minimal 8 karakter.');
            return;
        }

        const btn = document.getElementById('submitBtn');
        const orig = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';

        $.ajax({
            url: 'save_password.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if (res.success) {
                    showAlert('success', '✅ Password berhasil diubah!');
                    document.getElementById('resetPasswordForm').reset();
                    document.getElementById('strengthBar').style.width = '0';
                    document.getElementById('strengthLabel').textContent = '';
                    document.getElementById('matchLabel').textContent = '';
                } else {
                    showAlert('danger', '❌ ' + res.message);
                }
            },
            error: function() {
                showAlert('danger', '❌ Terjadi kesalahan jaringan.');
            },
            complete: function() {
                btn.disabled = false;
                btn.innerHTML = orig;
            }
        });
    });

    function showAlert(type, msg) {
        const alert = document.getElementById('pwAlert');
        alert.className = 'alert alert-' + type + ' py-2 small';
        alert.textContent = msg;
        alert.classList.remove('d-none');
        setTimeout(function() { alert.classList.add('d-none'); }, 5000);
    }
});
</script>

<?php require_once 'layout/footer.php'; ?>
