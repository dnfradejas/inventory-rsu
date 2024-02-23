import React, {Fragment} from 'react';

const Footer = () => {

    return (
        <Fragment>
            <footer className="section-footer border-top">
                <div className="container">
                    <section className="footer-bottom border-top row">
                        <div className="col-md-2">
                            <p className="text-muted"> Copyright &copy; {(new Date().getFullYear())} </p>
                        </div>
                        <div className="col-md-8 text-md-center">
                            <span  className="px-2">info@pixsellz.io</span>
                            <span  className="px-2">+879-332-9375</span>
                            <span  className="px-2">Street name 123, Avanue abc</span>
                        </div>
                        {/* <div className="col-md-2 text-md-right text-muted">
                            <i className="fab fa-lg fa-cc-visa"></i>
                            <i className="fab fa-lg fa-cc-paypal"></i>
                            <i className="fab fa-lg fa-cc-mastercard"></i>
                        </div> */}
                    </section>
                </div>
            </footer>
        </Fragment>
    );

};

export default Footer;