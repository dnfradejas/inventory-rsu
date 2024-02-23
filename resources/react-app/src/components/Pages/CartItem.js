import React, {Fragment} from 'react';
import {Input, Button} from 'antd';
import {Link} from 'react-router-dom';
import {useDispatch} from 'react-redux';

import {removeCartItem} from '../../store/cart-actions';


const CartItem = ({item, changeInputHandler}) => {
    const dispatch = useDispatch();

    const removeItemHandler = item => {
        dispatch(removeCartItem(item));
    };
    
    return (
        <Fragment>
            <tr>
                <td>
                    <figure className="itemside align-items-center">
                        <div className="aside"><img src={item.image} className="img-sm" alt={item.product_name}/></div>
                        <figcaption className="info">
                            <Link className="title text-dark" to={`/products/${item.slug}`}>{item.product_name}</Link>
                            {<SizeColorComponent item={item}/>}
                        </figcaption>
                    </figure>
                </td>
                <td>
                    <div className="col"> 
                        <div className="input-group input-spinner">
                            <div className="input-group-prepend">
                                <Button className="btn btn-light"><i className="fa fa-minus"></i></Button>
                            </div>
                            <Input min={1} onKeyUp={e => changeInputHandler(e, item)} className='form-control' defaultValue={item.quantity}/>
                            <div className="input-group-append">
                                <Button className="btn btn-light"><i className="fa fa-plus"></i></Button>
                            </div>
                        </div>
                    </div>
                </td>
                <td> 
                    <div className="price-wrap"> 
                        <var className="price">PHP{item.total}</var> 
                        <small className="text-muted"> PHP{item.final_price} each </small> 
                    </div>
                </td>
                <td className="text-right">
                    <Button onClick={() => removeItemHandler(item)} className="btn btn-danger">Remove</Button>
                </td>
            </tr>
        </Fragment>
    );
};

const SizeColorComponent = ({item}) => {

    let size = null;
    let color = null;
    if (item.size) {
        size = 'Size: ' + item.size;
    }
    
    if (item.color) {
        color = 'Color: ' + item.color;
    }
    

    return (
        <>
        <p className="text-muted small">{size} <br/> {color}</p>
        </>
    );
}

export default CartItem;