import React, {Fragment} from 'react';
import {Link} from 'react-router-dom';

const NoCart = ({text}) => {
    
    return (
        <Fragment>
            <article className="card mb-4">
                <div className="card-body">
                    <p>{text}</p>
                    <Link to={`/products`} className="btn btn-primary btn-block">Continue Shopping</Link>
                </div>
            </article>
        </Fragment>
    );
};

export default NoCart;