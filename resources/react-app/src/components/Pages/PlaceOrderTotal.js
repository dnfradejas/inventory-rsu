import React, {Fragment} from 'react';


const PlaceOrderTotal = ({grandTotal}) => {

    return (
        <Fragment>
            <aside className="col-md-4">
                <div className="card">
                    <div className="card-body">
                        <dl className="dlist-align">
                            <dt>Total price:</dt>
                            <dd className="text-right">PHP{grandTotal}</dd>
                        </dl>
                        
                    </div>
                </div>
            </aside>
        </Fragment>
    );
};

export default PlaceOrderTotal;