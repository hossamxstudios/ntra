<script>
    // Edit Machine
    function editMachine(id, name, area, place, serialNumber, status) {
        document.getElementById('editMachineForm').action = '/machines/' + id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_area').value = area || '';
        document.getElementById('edit_place').value = place || '';
        document.getElementById('edit_serial_number').value = serialNumber || '';
        document.getElementById('edit_status').value = status;
        new bootstrap.Modal(document.getElementById('editMachineModal')).show();
    }

    // Delete Machine
    function confirmDelete(id, name) {
        document.getElementById('deleteMachineForm').action = '/machines/' + id;
        document.getElementById('delete_machine_name').textContent = name;
        new bootstrap.Modal(document.getElementById('deleteMachineModal')).show();
    }

    // Bulk Selection
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const bulkActionsBar = document.getElementById('bulkActionsBar');
        const selectedCount = document.getElementById('selectedCount');

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateBulkActionsBar();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkActionsBar);
        });

        function updateBulkActionsBar() {
            const checked = document.querySelectorAll('.item-checkbox:checked');
            if (checked.length > 0) {
                bulkActionsBar.classList.remove('d-none');
                selectedCount.textContent = checked.length;
            } else {
                bulkActionsBar.classList.add('d-none');
            }
        }
    });

    function clearSelection() {
        document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = false);
        document.getElementById('selectAll').checked = false;
        document.getElementById('bulkActionsBar').classList.add('d-none');
    }

    function bulkDelete() {
        const checked = document.querySelectorAll('.item-checkbox:checked');
        if (checked.length === 0) return;

        const idsContainer = document.getElementById('bulkDeleteIds');
        idsContainer.innerHTML = '';
        checked.forEach(cb => {
            idsContainer.innerHTML += `<input type="hidden" name="ids[]" value="${cb.value}">`;
        });
        document.getElementById('bulk_delete_count').textContent = checked.length;
        new bootstrap.Modal(document.getElementById('bulkDeleteModal')).show();
    }
</script>
