<script>
    function editPassenger(passenger) {
        document.getElementById('editPassengerForm').action = '/passengers/' + passenger.id;
        document.getElementById('edit_first_name').value = passenger.first_name || '';
        document.getElementById('edit_last_name').value = passenger.last_name || '';
        document.getElementById('edit_birthdate').value = passenger.birthdate || '';
        document.getElementById('edit_gender').value = passenger.gender || '';
        document.getElementById('edit_nationality').value = passenger.nationality || '';
        document.getElementById('edit_passport_no').value = passenger.passport_no || '';
        document.getElementById('edit_national_id').value = passenger.national_id || '';
        document.getElementById('edit_document_number').value = passenger.document_number || '';
        document.getElementById('edit_valid_until').value = passenger.valid_until || '';
        document.getElementById('edit_address').value = passenger.address || '';
        new bootstrap.Modal(document.getElementById('editPassengerModal')).show();
    }

    function confirmDelete(id, name) {
        document.getElementById('deletePassengerForm').action = '/passengers/' + id;
        document.getElementById('delete_passenger_name').textContent = name || 'هذا المسافر';
        new bootstrap.Modal(document.getElementById('deletePassengerModal')).show();
    }

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
