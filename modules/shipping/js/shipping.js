(function($){
  $(document).ready(function(){
    var table = $('#table_shipping').DataTable({
      "columns" : [{"width" : "25%"}, null, null, null, null, null],
      "paging" : false,
      "ordering" : false,
      "searching" : false,
      "info" : false
    });

    $('#add_customer').on('click', function(){
      table.row.add(["{{ form.customer }}",0,0,0,0,0]).draw(false);
    });
  });
})(jQuery);
