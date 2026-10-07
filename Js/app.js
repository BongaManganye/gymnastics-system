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
            const name = row.querySelector('.col-name').innerText.toLowerCase();
            const email = row.querySelector('.col-email').innerText.toLowerCase();
            const program = row.querySelector('.col-program').innerText.toLowerCase();
            const status = row.querySelector('.col-status').innerText.toLowerCase();

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