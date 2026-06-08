"use strict";

var KTAppCrmChateryGroups = function () {
    var table;
    var datatable;

    var initDatatable = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [],
            'pageLength': 10,
            'columnDefs': [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 3 },
            ]
        });
    }

    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-ecommerce-product-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
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
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTAppCrmChateryGroups.init();
});
