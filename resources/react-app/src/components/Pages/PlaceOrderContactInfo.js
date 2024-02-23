import React, {Fragment} from 'react';
import * as Yup from "yup";
import { Formik, Form, Field, ErrorMessage } from "formik";
import {Navigate} from 'react-router-dom';



const PlaceOrderContactInfo = ({onSubmit}) => {

    const validationSchema = Yup.object({
        lastname: Yup.string().required(),
        firstname: Yup.string().required(),
        email: Yup.string().email().required(),
        mobile_number: Yup.number().required(),
    });

    const initialValues = {
        lastname: "",
        firstname: "",
        email: "",
        mobile_number: "",
    };

    const renderError = (message) => <p className='text-danger'>{message}</p>;

    return (
        <Fragment>
            
            <article className="card mb-4">
                <div className="card-body">
                    <h4 className="card-title mb-4">Contact info</h4>
                    <Formik
                        initialValues={initialValues}
                        validationSchema={validationSchema}
                        onSubmit={async(values, {resetForm}) => {
                            await onSubmit(values);
                            resetForm();
                        }}
                    >
                        <Form>
                            <div className="row">
                                <div className="form-group col-sm-6">
                                    <label>First name</label>
                                    <Field name="firstname" type="text" className="form-control" placeholder="Enter firstname"/>
                                    <ErrorMessage name="firstname" render={renderError} />
                                </div>
                                <div className="form-group col-sm-6">
                                    <label>Last name</label>
                                    <Field name="lastname" type="text" className="form-control" placeholder="Enter lastname"/>
                                    <ErrorMessage name="lastname" render={renderError} />
                                </div>
                                <div className="form-group col-sm-6">
                                    <label>Mobile #</label>
                                    <Field name="mobile_number" type="text" className="form-control" placeholder="Enter mobile number"/>
                                    <ErrorMessage name="mobile_number" render={renderError} />
                                </div>
                                <div className="form-group col-sm-6">
                                    <label>Email</label>
                                    <Field name="email" type="email" className="form-control" placeholder="Enter email"/>
                                    <ErrorMessage name="email" render={renderError} />
                                </div>
                                <button type="submit" className="btn btn-primary btn-block">
                                    Place order
                                </button>
                            </div>
                        </Form>
                    </Formik>
                </div>
            </article>
        </Fragment>
    );
};

export default PlaceOrderContactInfo;