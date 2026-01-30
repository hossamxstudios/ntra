<script>
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
