"use strict";

var KTAppEcommerceBooks = function () {
    var table;
    var datatable;

    var initDatatable = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [],
            'pageLength': 10,
            'columnDefs': [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 7 },
            ]
        });

        datatable.on('draw', function () {
            handleDeleteRows();
        });
    }

    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-ecommerce-product-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    var handleStatusFilter = () => {
        const filterStatus = document.querySelector('[data-kt-ecommerce-product-filter="status"]');
        $(filterStatus).on('change', e => {
            let value = e.target.value;
            if (value === 'all') {
                value = '';
            }
            datatable.column(6).search(value).draw();
        });
    }

    var handleDeleteRows = () => {
        const deleteButtons = table.querySelectorAll('.btn-delete');

        deleteButtons.forEach(d => {
            d.addEventListener('click', function (e) {
                e.preventDefault();

                const parent = e.target.closest('tr');
                const bookName = parent.querySelector('[data-kt-ecommerce-product-filter="product_name"]').innerText;
                const bookId = d.getAttribute('data-id');
                const deleteForm = document.getElementById('delete-form-' + bookId);

                Swal.fire({
                    text: "Apakah Anda yakin ingin menghapus \"" + bookName + "\"?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        if (deleteForm) {
                            deleteForm.submit();
                        }
                    }
                });
            })
        });
    }

    return {
        init: function () {
            table = document.querySelector('#kt_ecommerce_products_table');

            if (!table) {
                return;
            }

            initDatatable();
            handleSearchDatatable();
            handleStatusFilter();
            handleDeleteRows();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTAppEcommerceBooks.init();
});
