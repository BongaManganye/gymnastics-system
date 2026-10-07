<?php
require_once 'db.php';

$query = "SELECT * FROM gymnasts ORDER BY enrollment_date DESC";
$result = $conn->query($query);
$gymnasts = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gymnast Management Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
        body { background: #0f172a; color: #f8fafc; padding: 40px 20px; }
        .dashboard-card { max-width: 1100px; margin: 0 auto; background: #1e293b; border-radius: 16px; border: 1px solid #334155; padding: 32px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3); }
        .header-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
        h2 { font-size: 1.6rem; font-weight: 700; color: #f8fafc; letter-spacing: -0.02em; }
        .subtitle { font-size: 0.875rem; color: #94a3b8; margin-top: 4px; }
        .btn-add { background: #3b82f6; color: #fff; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.875rem; transition: background 0.2s; display: inline-flex; align-items: center; }
        .btn-add:hover { background: #2563eb; }
        .toolbar { display: flex; gap: 12px; background: #0f172a; padding: 14px; border-radius: 10px; border: 1px solid #334155; margin-bottom: 24px; flex-wrap: wrap; }
        .toolbar input[type="text"], .toolbar select { padding: 10px 14px; border: 1px solid #334155; border-radius: 8px; font-size: 0.875rem; background: #1e293b; color: #f8fafc; outline: none; }
        .toolbar input[type="text"] { flex: 2; min-width: 220px; }
        .toolbar select { flex: 1; min-width: 150px; }
        .toolbar input[type="text"]:focus, .toolbar select:focus { border-color: #3b82f6; }
        .table-wrap { overflow-x: auto; border: 1px solid #334155; border-radius: 10px; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        thead { background: #0f172a; }
        th { padding: 14px 18px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; border-bottom: 1px solid #334155; }
        td { padding: 16px 18px; font-size: 0.875rem; border-bottom: 1px solid #334155; vertical-align: middle; color: #e2e8f0; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: rgba(255, 255, 255, 0.02); }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; }
        .badge-active { background: rgba(34, 197, 94, 0.2); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); }
        .badge-on_hold { background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
        .badge-completed { background: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
        .badge-pending { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
        .action-group { display: flex; gap: 8px; }
        .btn-act { padding: 6px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; text-decoration: none; transition: opacity 0.15s; }
        .btn-act:hover { opacity: 0.85; }
        .btn-act.view { background: #3b82f6; color: #fff; }
        .btn-act.edit { background: #eab308; color: #000; }
        .btn-act.del { background: #ef4444; color: #fff; }
    </style>
</head>
<body>
    <div class="dashboard-card">
        <div class="header-section">
            <div>
                <h2>Gymnast Management Dashboard</h2>
                <div class="subtitle">Sports Academy Membership & Training Portal</div>
            </div>
            <a href="register.php" class="btn-add">+ Add New Gymnast</a>
        </div>

        <div class="toolbar">
            <input type="text" id="searchInput" placeholder="Search by name or email...">
            <select id="programFilter">
                <option value="">All Programs</option>
                <option value="Beginner">Beginner</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Advanced">Advanced</option>
            </select>
            <select id="statusFilter">
                <option value="">All Statuses</option>
                <option value="ACTIVE">ACTIVE</option>
                <option value="ON_HOLD">ON_HOLD</option>
                <option value="COMPLETED">COMPLETED</option>
                <option value="PENDING">PENDING</option>
            </select>
        </div>

        <div class="table-wrap">
            <table id="gymnastTable">
                <thead>
                    <tr>
                        <th>Membership ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Program</th>
                        <th>Enrollment Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($gymnasts)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center; padding: 32px; color: #94a3b8;">
                            No gymnasts registered yet.
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($gymnasts as $row): ?>
                        <tr>
                            <td><strong style="color: #60a5fa;"><?= htmlspecialchars($row['membership_id']) ?></strong></td>
                            <td class="col-name"><?= htmlspecialchars($row['full_name']) ?></td>
                            <td class="col-email" style="color: #94a3b8;"><?= htmlspecialchars($row['email']) ?></td>
                            <td class="col-program"><?= htmlspecialchars($row['training_program']) ?></td>
                            <td><?= htmlspecialchars($row['enrollment_date']) ?></td>
                            <td class="col-status">
                                <span class="badge badge-<?= strtolower($row['status']) ?>">
                                    <?= htmlspecialchars($row['status']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-group">
                                    <a href="profile.php?id=<?= urlencode($row['membership_id']) ?>" class="btn-act view">View</a>
                                    <a href="edit.php?id=<?= urlencode($row['membership_id']) ?>" class="btn-act edit">Edit</a>
                                    <a href="delete.php?id=<?= urlencode($row['membership_id']) ?>" 
                                       class="btn-act del" 
                                       onclick="return confirm('Are you sure you want to delete this gymnast?');">Delete</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            const programFilter = document.getElementById('programFilter');
            const statusFilter = document.getElementById('statusFilter');
            const tableRows = document.querySelectorAll('#gymnastTable tbody tr');

            function filterTable() {
                const query = searchInput.value.toLowerCase();
                const selectedProgram = programFilter.value.toLowerCase();
                const selectedStatus = statusFilter.value.toLowerCase();

                tableRows.forEach(row => {
                    const nameEl = row.querySelector('.col-name');
                    const emailEl = row.querySelector('.col-email');
                    const programEl = row.querySelector('.col-program');
                    const statusEl = row.querySelector('.col-status');

                    if (!nameEl) return;

                    const name = nameEl.innerText.toLowerCase();
                    const email = emailEl.innerText.toLowerCase();
                    const program = programEl.innerText.toLowerCase();
                    const status = statusEl.innerText.toLowerCase();

                    const matchesSearch = name.includes(query) || email.includes(query);
                    const matchesProgram = !selectedProgram || program.includes(selectedProgram);
                    const matchesStatus = !selectedStatus || status.includes(selectedStatus);

                    row.style.display = (matchesSearch && matchesProgram && matchesStatus) ? '' : 'none';
                });
            }

            if (searchInput) searchInput.addEventListener('input', filterTable);
            if (programFilter) programFilter.addEventListener('change', filterTable);
            if (statusFilter) statusFilter.addEventListener('change', filterTable);
        });
    </script>
</body>
</html>
