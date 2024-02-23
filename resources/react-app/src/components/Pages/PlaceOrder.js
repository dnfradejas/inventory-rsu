import NoCart from './NoCart';
import React, {Fragment} from 'react';
import {Modal} from 'react-bootstrap';
import { useCookies } from 'react-cookie';
import PlaceOrderItem from './PlaceOrderItem';
import {useNavigate} from 'react-router-dom';
import PlaceOrderTotal from './PlaceOrderTotal';
import {retrieveCart} from '../../store/cart-actions';
import {useDispatch, useSelector} from 'react-redux';
import PlaceOrderContactInfo from './PlaceOrderContactInfo';
import {postPlaceOrder} from '../../store/placeorder-action';

import MainHeader from '../Layout/MainHeader';



const PlaceOrder = () => {

    const notification = useSelector((state) => state.notification.notification);
    const cart = useSelector((state) => state.cart);
    const [cookies, setCookie] = useCookies(['store_cookie']);
    const navigate = useNavigate();
    const storeId = cookies.store_cookie;

    const dispatch = useDispatch();

    const {items, grandTotal} = cart;

    const onSubmit = (values) => {
        dispatch(postPlaceOrder(values, storeId));
    };

    const modalOkayHandler = () => {
        dispatch(retrieveCart(storeId));
        navigate('/products');
    }

    const showModal = notification;

    return (
        <MainHeader>
                {showModal &&
                    <Modal show={showModal}>
                        <Modal.Header>
                            <Modal.Title>{notification.title}</Modal.Title>
                        </Modal.Header>
                        <Modal.Body>
                        <p>{notification.message}</p>
                        </Modal.Body>
                        <Modal.Footer>
                            <button onClick={modalOkayHandler} className="ant-btn ant-btn-default btn btn-primary">Okay</button>
                        </Modal.Footer>
                    </Modal>
                }
            <section className="section-content padding-y bg">
                <div className="container">
                    <div className="row">
                        {grandTotal > 0 ? 
                        <>
                        <main className="col-md-8">
                            <article className="card mb-4">
                                <div className="card-body">
                                    <h4 className="card-title mb-4">Review cart</h4>
                                    {items.map((item) => <PlaceOrderItem key={item.id} item={item}/>)}
                                </div>
                            </article>
                            <PlaceOrderContactInfo onSubmit={onSubmit}/>
                        </main>
                        <PlaceOrderTotal grandTotal={grandTotal}/>
                        </>
                        : 
                        <>
                        <main className='col-lg-12'>
                            <NoCart text="You don't have products to checkout."/>
                        </main>
                        </>
                        }
                    </div>
                </div>
            </section>
        </MainHeader>
    );

};

export default PlaceOrder;