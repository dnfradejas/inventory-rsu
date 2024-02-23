const initProductDeliveryDetailsEvent = () => {


    $('#add-product-btn').on('click', function(evt){
        evt.preventDefault();
        let form = {
            delivery_date: $('#deliverydatetime').find('input').val(),
            supplier: $('input[name=supplier]').val(),
            product: $('input[name=product]').val(),
            quantity: parseInt($('#quantity').val()),
            production_date: $('#productiondate').find('input').val(),
            expiration_date: $('#expirationdate').find('input').val(),
        };
        console.log(form);
      });
};

export default initProductDeliveryDetailsEvent;