<script>
    function editDevice(device) {
        document.getElementById('editMobileDeviceForm').action = '/mobile-devices/' + device.id;
        document.getElementById('edit_device_type').value = device.device_type;
        document.getElementById('edit_brand').value = device.brand || '';
        document.getElementById('edit_model').value = device.model;
        document.getElementById('edit_serial_number').value = device.serial_number || '';
        document.getElementById('edit_imei_number').value = device.imei_number || '';
        document.getElementById('edit_imei_number_2').value = device.imei_number_2 || '';
        document.getElementById('edit_imei_number_3').value = device.imei_number_3 || '';
        document.getElementById('edit_tax').value = device.tax || '';
        new bootstrap.Modal(document.getElementById('editMobileDeviceModal')).show();
    }

    function confirmDelete(id, name) {
        document.getElementById('deleteMobileDeviceForm').action = '/mobile-devices/' + id;
        document.getElementById('delete_device_name').textContent = name;
        new bootstrap.Modal(document.getElementById('deleteMobileDeviceModal')).show();
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
