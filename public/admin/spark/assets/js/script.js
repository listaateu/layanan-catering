// sweetalert
function alertConfirm(event, form, text, title) {
    event.preventDefault();
    Swal.fire({
        title: title || 'Apakah anda yakin?',
        text: text || '',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal'
    }).then(function (result) {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    return false;
}
