import {toggleModal} from '../modal/modal';
import {debounce} from '../util/util';

let productId;


function plusIconClickHandler(params) {

    return function(evt){
        evt.preventDefault();
        toggleModal();
        productId = $(this).data('id');
        params.id = productId;
        // get current quantity from database
        __fetchProductCurrentQuantity(params);
        
    }
}

function closeModalButtonHandler(evt) {
    evt.preventDefault();
    toggleModal();
};


function __fetchProductCurrentQuantity(params) {
    $.ajax({
        url: params.url,
        method: "POST",
        data: params,
        success: function(response){
            const {currentQuantity} = response;
            let content = '<input id="input-quantity" type="number" min="1" oninput="this.value = Math.abs(this.value)" class="input modal-input" value="' + currentQuantity + '" placeholder="Enter quantity">';
            $(".modal-input-cont").html(content);
            $('.modal').find('#input-quantity').focus();
        }
    });
}



function inputQuantityEnterKeyPressHandler(params) {

    return function(evt){
        if (evt.which == 13) {
            const val = parseInt($(this).val());
            params.quantity = val;
            params.id = productId;
            params.store = parseInt(params.store_id);
            __createOrder(params);
            toggleModal();
        }
    }
}


function __createOrder(params) {
    
    $.ajax({
        url: params.url,
        method: "POST",
        data: params,
        success: function(response){
            __updateOrderListUi(response);
            console.log(response);
        }
    });
}

function __updateOrderListUi(response) {
    const {html} = response;
    $('.main_content--orderlist').html(html);
}


function scanBarcodeButtonHandler(evt) {
    evt.preventDefault();
    toggleModal();

    let content = '<input id="input-barcode" type="text" class="input modal-input" value="" placeholder="Scan barcode">';
    $(".modal-input-cont").html(content);
    $('.modal').find('#input-barcode').focus();
    
}


function createOrderOnBarcodeScanHandler(params) {
    return function(){
        let $this = $(this);
        let barcode = $this.val();
        params.store = parseInt(params.store_id);
        params.code = barcode;
        __createOrder(params);
        $this.val('');

        console.log(params);
    };
}


function orderNow(params) {
    return function(evt){
        evt.preventDefault();
        
        Swal.fire({
            title: "Are you sure?",
            text: "An order will be created after you confirm this action.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Create Order'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: params.url,
                    method: "POST",
                    data: {
                        store: params.store_id
                    },
                    success: function(response){
                        Swal.fire({
                            icon: 'success',
                            title: 'Order has been processed!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = window.location.href;
                        });

                    }
                });
            }
        });
    };
}


function removeProductFromOrderListHandler() {
    return function(evt){

        evt.preventDefault();
        
        let id = $(this).data('id');
        $.ajax({
            url: `/pos/order/product/${id}`, // cashier.order.delete.product
            method: "DELETE",
            success: function(response){
                __updateOrderListUi(response);
            }
        });
    };
}


function setUpPosEvents() {
    $('.btn__order').on('click', plusIconClickHandler({url: window.cnf.orderProductQuantityUrl, store_id: window.cnf.storeId}));
    // handle when enter key is press on input quantity modal
    $('body').on('keypress', '#input-quantity', inputQuantityEnterKeyPressHandler({store_id: window.cnf.storeId, url: window.cnf.orderProductUrl}));
    $(".close-button").on('click', closeModalButtonHandler);

    $(".pos-button-scan").on('click', scanBarcodeButtonHandler);

    $('.modal').on('keyup', '#input-barcode', debounce(createOrderOnBarcodeScanHandler({store_id: window.cnf.storeId, url: window.cnf.orderProductUrl}), 500));
    
    $('.pos-button-ordernow').on('click', orderNow({store_id: window.cnf.storeId, url: window.cnf.orderNowUrl}));
    
    $('.main_content--orderlist').on('click', '.remove__order-action', removeProductFromOrderListHandler());
}

export default setUpPosEvents;