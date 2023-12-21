$('.datatable-crud').dataTable({
    // stateSave:true,
    pagingType: 'full_numbers_no_ellipses',
    processing: true,
    search: {
        return: true
    },
    serverSide: true,
    ajax: location.href
})

try {
    $('select').select2()
} catch (error) {
    
}