import React, {Fragment} from 'react';

const PlaceOrderItem = ({item}) => {

    return (
        <Fragment>
            <div className="row">
                <div className="col-md-6">
                    <figure className="itemside  mb-4">
                        <div className="aside"><img src={item.image} className="border img-sm" alt=""/></div>
                        <figcaption className="info">
                            <p>{item.product_name}</p>
                            <span className="text-muted">{item.quantity}x = PHP{item.final_price} </span>
                        </figcaption>
                    </figure>
                </div>
            </div>
        </Fragment>
    );
};

export default PlaceOrderItem;